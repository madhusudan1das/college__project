<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Smart College ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        .reg-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            background: #fff;
            max-width: 850px;
            width: 100%;
            overflow: hidden;
        }
        .reg-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-gradient:hover {
            opacity: 0.95;
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="reg-card shadow-lg">
        <div class="reg-header">
            <h3 class="fw-bold mb-1"><i class="bi bi-person-plus-fill me-2 fs-4"></i>Student Portal Enrollment</h3>
            <p class="mb-0 text-white-50 small">Create your account and complete academic profiling details</p>
        </div>
        
        <div class="card-body p-4 p-md-5">
            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="bi bi-person-fill"></i> Account Credentials</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control bg-light" placeholder="e.g. Alice Cooper" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control bg-light" placeholder="alice@college.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control bg-light" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="••••••••" required>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="bi bi-mortarboard-fill"></i> Academic Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Department Selection</label>
                        <select name="department_id" class="form-select bg-light" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }} ({{ $dept->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Assign Semester Class</label>
                        <select name="class_id" class="form-select bg-light" required>
                            <option value="">Select Academic Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Roll Number</label>
                        <input type="text" name="roll_no" class="form-control bg-light" placeholder="e.g. CSE-2026-025" value="{{ old('roll_no') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Admission Number</label>
                        <input type="text" name="admission_no" class="form-control bg-light" placeholder="e.g. ADM-10025" value="{{ old('admission_no') }}" required>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="bi bi-file-earmark-person-fill"></i> Personal Profiles</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Contact Number</label>
                        <input type="text" name="phone" class="form-control bg-light" placeholder="+1234567890" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Date of Birth</label>
                        <input type="date" name="dob" class="form-control bg-light" value="{{ old('dob') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Gender</label>
                        <select name="gender" class="form-select bg-light" required>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Residential Address</label>
                        <textarea name="address" class="form-control bg-light" rows="3" placeholder="Enter home address...">{{ old('address') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-gradient w-100 mb-3 shadow">Create Profile & Login</button>
                
                <div class="text-center">
                    <span class="small text-muted">Already registered? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Sign In here</a></span>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
