<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Smart College ERP</title>
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
            padding: 30px 15px;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            background: #fff;
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            padding: 40px 30px;
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
        .seed-badge {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .seed-badge:hover {
            opacity: 0.85;
            transform: scale(1.02);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h3 class="fw-bold mb-1"><i class="bi bi-cpu-fill me-2 fs-4"></i>Smart College ERP</h3>
            <p class="mb-0 text-white-50 small">Enter credentials to access your dashboard portal</p>
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

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 small">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control border-start-0 bg-light" placeholder="name@college.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label small fw-bold">Password</label>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control border-start-0 bg-light" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label class="form-check-label small text-muted" for="remember">Remember me on this machine</label>
                </div>

                <button type="submit" class="btn btn-gradient w-100 mb-3 shadow">Sign In</button>
                
                <div class="text-center">
                    <span class="small text-muted">New student? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Create Account</a></span>
                </div>
            </form>

            <!-- Pre-seeded accounts configuration to assist user review -->
            <div class="mt-4 pt-4 border-top">
                <h6 class="fw-bold mb-3 small text-muted"><i class="bi bi-patch-question"></i> Quick Test Accounts (Password: <code>password</code>)</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center seed-badge" onclick="fillCreds('admin@college.com')">
                        <div>
                            <span class="fw-bold d-block small">System Admin</span>
                            <span class="text-muted fs-8">admin@college.com</span>
                        </div>
                        <span class="badge bg-danger rounded-pill">Admin</span>
                    </div>
                    <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center seed-badge" onclick="fillCreds('turing@college.com')">
                        <div>
                            <span class="fw-bold d-block small">Dr. Alan Turing</span>
                            <span class="text-muted fs-8">turing@college.com</span>
                        </div>
                        <span class="badge bg-primary rounded-pill">Faculty</span>
                    </div>
                    <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center seed-badge" onclick="fillCreds('john@college.com')">
                        <div>
                            <span class="fw-bold d-block small">John Doe</span>
                            <span class="text-muted fs-8">john@college.com</span>
                        </div>
                        <span class="badge bg-success rounded-pill">Student</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillCreds(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
