<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Smart College ERP</title>
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
        .reset-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            background: #fff;
            max-width: 450px;
            width: 100%;
            overflow: hidden;
        }
        .reset-header {
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
    </style>
</head>
<body>

    <div class="reset-card shadow-lg">
        <div class="reset-header">
            <h3 class="fw-bold mb-1"><i class="bi bi-key-fill me-2 fs-4"></i>Reset Password</h3>
            <p class="mb-0 text-white-50 small">Provide email address to retrieve password link</p>
        </div>
        
        <div class="card-body p-4 p-md-5">
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 small">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label small fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control border-start-0 bg-light" placeholder="name@college.com" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-gradient w-100 mb-3 shadow">Send Reset Password Instructions</button>
                
                <div class="text-center">
                    <span class="small text-muted"><a href="{{ route('login') }}" class="text-decoration-none fw-bold"><i class="bi bi-arrow-left"></i> Back to login</a></span>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
