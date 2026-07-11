<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\AcademicClass;
use App\Models\Subject;
use App\Models\Notice;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\SalaryRecord;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\ActivityLog;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
        //hello
    }

    /**
     * Admin Dashboard with analytical widgets.
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_faculty' => Faculty::count(),
            'total_departments' => Department::count(),
            'notices_published' => Notice::count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'pending_complaints' => Complaint::where('status', 'pending')->count(),
        ];

        // Financial summary
        $totalBilled = Fee::sum('amount');
        $totalCollected = Payment::sum('amount_paid');
        $stats['total_billed'] = $totalBilled;
        $stats['total_collected'] = $totalCollected;
        $stats['outstanding_fees'] = $totalBilled - $totalCollected;

        // Fetch department-wise student distributions for Chart.js
        $deptDistribution = Department::withCount('students')->get();
        $chartDepts = [];
        $chartStudentCounts = [];
        foreach ($deptDistribution as $d) {
            $chartDepts[] = $d->code;
            $chartStudentCounts[] = $d->students_count;
        }

        // Attendance stats
        $presentCount = Attendance::where('status', 'present')->count();
        $absentCount = Attendance::where('status', 'absent')->count();
        $lateCount = Attendance::where('status', 'late')->count();
        $totalAttendance = $presentCount + $absentCount + $lateCount;
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 100;

        return view('admin.dashboard', compact('stats', 'chartDepts', 'chartStudentCounts', 'attendanceRate', 'presentCount', 'absentCount', 'lateCount'));
    }

    /* =========================================================================
     * Student Management (CRUD)
     * ========================================================================= */

    public function students(Request $request)
    {
        $query = Student::with('user', 'department', 'class');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            })->orWhere('roll_no', 'like', "%{$search}%")
              ->orWhere('admission_no', 'like', "%{$search}%");
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->paginate(10);
        $departments = Department::all();
        $classes = AcademicClass::all();

        return view('admin.students', compact('students', 'departments', 'classes'));
    }

    public function storeStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'class_id' => 'required|exists:classes,id',
            'roll_no' => 'required|string|unique:students,roll_no',
            'admission_no' => 'required|string|unique:students,admission_no',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3, // Student Role ID
                'phone' => $request->phone,
                'status' => 'active'
            ]);

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
        });

        return response()->json(['success' => true, 'message' => 'Student enrolled successfully!']);
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'class_id' => 'required|exists:classes,id',
            'roll_no' => 'required|string|unique:students,roll_no,' . $student->id,
            'admission_no' => 'required|string|unique:students,admission_no,' . $student->id,
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
        }

        DB::transaction(function () use ($request, $student, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $student->update([
                'department_id' => $request->department_id,
                'class_id' => $request->class_id,
                'roll_no' => $request->roll_no,
                'admission_no' => $request->admission_no,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'address' => $request->address
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Student record updated.']);
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        // Cascade delete on user table deletes student table profile
        $student->user->delete();
        return response()->json(['success' => true, 'message' => 'Student record and login deleted.']);
    }

    /* =========================================================================
     * Faculty Management (CRUD)
     * ========================================================================= */

    public function faculty(Request $request)
    {
        $query = Faculty::with('user', 'department', 'subjects');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            })->orWhere('designation', 'like', "%{$search}%");
        }

        $faculties = $query->paginate(10);
        $departments = Department::all();
        $subjects = Subject::all();

        return view('admin.faculty', compact('faculties', 'departments', 'subjects'));
    }

    public function storeFaculty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string',
            'qualification' => 'required|string',
            'joining_date' => 'required|date',
            'gender' => 'required|string',
            'address' => 'nullable|string',
            'subjects' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2, // Faculty Role ID
                'phone' => $request->phone,
                'status' => 'active'
            ]);

            $faculty = Faculty::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'designation' => $request->designation,
                'qualification' => $request->qualification,
                'joining_date' => $request->joining_date,
                'gender' => $request->gender,
                'address' => $request->address
            ]);

            if ($request->has('subjects')) {
                $faculty->subjects()->attach($request->subjects);
            }
        });

        return response()->json(['success' => true, 'message' => 'Faculty member added successfully.']);
    }

    public function updateFaculty(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);
        $user = $faculty->user;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string',
            'qualification' => 'required|string',
            'joining_date' => 'required|date',
            'gender' => 'required|string',
            'address' => 'nullable|string',
            'subjects' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
        }

        DB::transaction(function () use ($request, $faculty, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $faculty->update([
                'department_id' => $request->department_id,
                'designation' => $request->designation,
                'qualification' => $request->qualification,
                'joining_date' => $request->joining_date,
                'gender' => $request->gender,
                'address' => $request->address
            ]);

            if ($request->has('subjects')) {
                $faculty->subjects()->sync($request->subjects);
            } else {
                $faculty->subjects()->detach();
            }
        });

        return response()->json(['success' => true, 'message' => 'Faculty record updated.']);
    }

    public function deleteFaculty($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->user->delete(); // Cascades profile deletion
        return response()->json(['success' => true, 'message' => 'Faculty record and login deleted.']);
    }

    /* =========================================================================
     * Department Management (CRUD)
     * ========================================================================= */

    public function departments()
    {
        $departments = Department::withCount('students', 'faculty')->get();
        return view('admin.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code|max:10'
        ]);

        Department::create($request->all());
        return redirect()->route('admin.departments')->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, $id)
    {
        $dept = Department::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,' . $dept->id
        ]);

        $dept->update($request->all());
        return redirect()->route('admin.departments')->with('success', 'Department updated successfully.');
    }

    public function deleteDepartment($id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->route('admin.departments')->with('success', 'Department deleted.');
    }

    /* =========================================================================
     * Notice Management (+ AI Smart Summary)
     * ========================================================================= */

    public function notices()
    {
        $notices = Notice::with('publisher')->orderBy('created_at', 'desc')->get();
        return view('admin.notices', compact('notices'));
    }

    public function storeNotice(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:all,faculty,student'
        ]);

        $notice = new Notice();
        $notice->title = $request->title;
        $notice->content = $request->content;
        $notice->published_by = auth()->id();
        $notice->target_role = $request->target_role;

        // Automatically trigger AI summary
        $notice->summary = $this->aiService->summarizeNotice($request->content, auth()->id());
        $notice->save();

        return redirect()->route('admin.notices')->with('success', 'Notice published successfully (with AI generated summary).');
    }

    public function deleteNotice($id)
    {
        Notice::findOrFail($id)->delete();
        return redirect()->route('admin.notices')->with('success', 'Notice deleted.');
    }

    public function ajaxAIOptimizeNotice(Request $request)
    {
        $request->validate(['content' => 'required']);
        $summary = $this->aiService->summarizeNotice($request->content, auth()->id());
        return response()->json(['success' => true, 'summary' => $summary]);
    }

    /* =========================================================================
     * Leave Requests Management
     * ========================================================================= */

    public function leaves()
    {
        $leaves = LeaveRequest::with('user.role')->orderBy('created_at', 'desc')->get();
        return view('admin.leaves', compact('leaves'));
    }

    public function approveLeave(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => 'approved',
            'actioned_by' => auth()->id()
        ]);
        return response()->json(['success' => true, 'message' => 'Leave request has been approved.']);
    }

    public function rejectLeave(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'actioned_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason ?? 'Rejected by Administrator.'
        ]);
        return response()->json(['success' => true, 'message' => 'Leave request rejected.']);
    }

    /* =========================================================================
     * Attendance Management Logs
     * ========================================================================= */

    public function attendance(Request $request)
    {
        $classes = AcademicClass::all();
        $subjects = Subject::all();
        
        $query = Attendance::with('student.user', 'class', 'subject');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $records = $query->orderBy('date', 'desc')->paginate(15);
        return view('admin.attendance', compact('records', 'classes', 'subjects'));
    }

    public function ajaxAIPredictAttendanceRisk(Request $request)
    {
        // Gather attendance records
        $records = Attendance::with('student.user', 'subject')
            ->select('student_id', 'subject_id', 'status', DB::raw('count(*) as count'))
            ->groupBy('student_id', 'subject_id', 'status')
            ->get();

        $analysisData = [];
        foreach ($records as $r) {
            $sId = $r->student_id;
            $sub = $r->subject ? $r->subject->name : 'Unknown';
            if (!isset($analysisData[$sId])) {
                $analysisData[$sId] = [
                    'student_name' => $r->student->user->name ?? 'Unknown',
                    'roll_no' => $r->student->roll_no ?? '',
                    'subjects' => []
                ];
            }
            if (!isset($analysisData[$sId]['subjects'][$sub])) {
                $analysisData[$sId]['subjects'][$sub] = ['present' => 0, 'absent' => 0, 'late' => 0];
            }
            $analysisData[$sId]['subjects'][$sub][$r->status] = $r->count;
        }

        $analysisText = $this->aiService->analyzeAttendance($analysisData, auth()->id());
        return response()->json(['success' => true, 'analysis' => $analysisText]);
    }

    /* =========================================================================
     * Salary Payroll Management
     * ========================================================================= */

    public function salaries()
    {
        $faculties = Faculty::with('user', 'department', 'salaryRecords')->get();
        $salaries = SalaryRecord::with('faculty.user')->orderBy('created_at', 'desc')->get();
        return view('admin.salaries', compact('faculties', 'salaries'));
    }

    public function generateSalaries(Request $request)
    {
        $request->validate([
            'month' => 'required', // Format: YYYY-MM
        ]);

        $faculties = Faculty::all();
        $date = Carbon::parse($request->month . '-01');

        $generatedCount = 0;
        foreach ($faculties as $f) {
            // Basic logic: base salary depends on designation
            $base = 4000.00;
            if (str_contains(strtolower($f->designation), 'professor')) {
                $base = 8000.00;
            } elseif (str_contains(strtolower($f->designation), 'associate')) {
                $base = 6500.00;
            }

            // Check if record already exists for this faculty and month
            $exists = SalaryRecord::where('faculty_id', $f->id)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->exists();

            if (!$exists) {
                SalaryRecord::create([
                    'faculty_id' => $f->id,
                    'base_salary' => $base,
                    'bonuses' => 0.00,
                    'deductions' => 0.00,
                    'net_salary' => $base,
                    'status' => 'pending'
                ]);
                $generatedCount++;
            }
        }

        return redirect()->route('admin.salaries')->with('success', "Generated {$generatedCount} salary records for " . $date->format('F Y'));
    }

    public function paySalary(Request $request, $id)
    {
        $salary = SalaryRecord::findOrFail($id);
        $salary->update([
            'status' => 'paid',
            'payment_date' => Carbon::now()->toDateString()
        ]);
        return response()->json(['success' => true, 'message' => 'Salary released successfully.']);
    }

    /* =========================================================================
     * Fees & Payment Auditing
     * ========================================================================= */

    public function fees()
    {
        $students = Student::with('user')->get();
        $fees = Fee::with('student.user')->orderBy('created_at', 'desc')->get();
        $payments = Payment::with('student.user', 'fee')->orderBy('created_at', 'desc')->get();
        return view('admin.fees', compact('students', 'fees', 'payments'));
    }

    public function storeFee(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date'
        ]);

        Fee::create($request->all());
        return redirect()->route('admin.fees')->with('success', 'Fee invoice generated.');
    }

    /* =========================================================================
     * Student Complaints Tracker
     * ========================================================================= */

    public function complaints()
    {
        $complaints = Complaint::with('student.user')->orderBy('created_at', 'desc')->get();
        return view('admin.complaints', compact('complaints'));
    }

    public function updateComplaintStatus(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved'
        ]);

        $complaint->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Complaint status updated.']);
    }

    /* =========================================================================
     * Reports Generation
     * ========================================================================= */

    public function reports()
    {
        return view('admin.reports');
    }

    public function downloadReport(Request $request)
    {
        $type = $request->type;
        $filename = "report_{$type}_" . time() . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'students') {
                fputcsv($file, ['Roll No', 'Name', 'Email', 'Department', 'Class', 'Gender']);
                $records = Student::with('user', 'department', 'class')->get();
                foreach ($records as $r) {
                    fputcsv($file, [
                        $r->roll_no,
                        $r->user->name ?? '',
                        $r->user->email ?? '',
                        $r->department->name ?? '',
                        $r->class->name ?? '',
                        $r->gender
                    ]);
                }
            } elseif ($type === 'fees') {
                fputcsv($file, ['Student Name', 'Fee Invoice', 'Amount Billed ($)', 'Due Date', 'Status']);
                $records = Fee::with('student.user')->get();
                foreach ($records as $r) {
                    fputcsv($file, [
                        $r->student->user->name ?? '',
                        $r->title,
                        $r->amount,
                        $r->due_date,
                        ucfirst($r->status)
                    ]);
                }
            } elseif ($type === 'complaints') {
                fputcsv($file, ['Student', 'Complaint', 'Category', 'Status', 'Date Submitted']);
                $records = Complaint::with('student.user')->get();
                foreach ($records as $r) {
                    fputcsv($file, [
                        $r->student->user->name ?? '',
                        $r->title,
                        $r->category ?? 'Unassigned',
                        ucfirst($r->status),
                        $r->created_at->toDateString()
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
