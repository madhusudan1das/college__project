<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Department;
use App\Models\AcademicClass;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show front landing home page.
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Show front About page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Show front Contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle authentication login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is currently inactive. Contact Admin.']);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'description' => 'User logged in successfully.',
                'ip_address' => $request->ip()
            ]);

            return $this->redirectUserBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show student registration form.
     */
    public function showRegister()
    {
        $departments = Department::all();
        $classes = AcademicClass::all();
        return view('auth.register', compact('departments', 'classes'));
    }

    /**
     * Register student.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'class_id' => 'required|exists:classes,id',
            'roll_no' => 'required|string|unique:students,roll_no',
            'admission_no' => 'required|string|unique:students,admission_no',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Student Role is ID 3
        $studentRole = Role::where('name', 'student')->first();
        $roleId = $studentRole ? $studentRole->id : 3;

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
            'phone' => $request->phone,
            'status' => 'active'
        ]);

        // Create Student Profile
        Student::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'class_id' => $request->class_id,
            'roll_no' => $request->roll_no,
            'admission_no' => $request->admission_no,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'address' => $request->address
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'description' => 'Student registration created.',
            'ip_address' => $request->ip()
        ]);

        Auth::login($user);

        return redirect()->route('student.dashboard')->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    /**
     * Show Forgot Password view.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Process simulated Reset Password Link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        return back()->with('success', 'A simulated password reset link has been dispatched to your email address.');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'description' => 'User logged out.',
                'ip_address' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    /**
     * Show profile dashboard.
     */
    public function profile()
    {
        $user = Auth::user();
        if ($user->isStudent()) {
            $user->load('student.department', 'student.class');
        } elseif ($user->isFaculty()) {
            $user->load('faculty.department');
        }
        return view('profile', compact('user'));
    }

    /**
     * Update user profile settings.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:6|confirmed'
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old file
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        // Handle password change
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password matches incorrectly.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Update Address if Student/Faculty
        if ($user->isStudent() && $user->student) {
            $user->student->update(['address' => $request->address]);
        } elseif ($user->isFaculty() && $user->faculty) {
            $user->faculty->update(['address' => $request->address]);
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update_profile',
            'description' => 'User profile updated.',
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Redirect logic helper.
     */
    protected function redirectUserBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFaculty()) {
            return redirect()->route('faculty.dashboard');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
        return redirect()->route('home');
    }
}
