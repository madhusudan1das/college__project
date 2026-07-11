<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();
        
        // Load the role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        foreach ($roles as $role) {
            // Check by role name (e.g., 'admin', 'faculty', 'student')
            if ($user->role && strtolower($user->role->name) === strtolower($role)) {
                return $next($request);
            }
        }

        // Access denied: redirect to their respective dashboard or landing
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access that resource.');
        } elseif ($user->isFaculty()) {
            return redirect()->route('faculty.dashboard')->with('error', 'You do not have permission to access that resource.');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard')->with('error', 'You do not have permission to access that resource.');
        }

        return redirect()->route('home')->with('error', 'Access Denied.');
    }
}
