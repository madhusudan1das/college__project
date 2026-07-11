<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Smart College ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafbfc; color: #1e293b; }
        .page-header { background: #0f172a; color: #fff; padding: 60px 0; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; }
        .contact-card { border: 1px solid rgba(226,232,240,0.8); border-radius: 20px; background: #fff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}" style="font-weight:800; color: #4f46e5;"><i class="bi bi-cpu-fill me-2 fs-3"></i>Smart College ERP</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('about') }}">About System</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3 active text-primary" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-primary rounded-pill px-4 text-white" href="{{ route('login') }}" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border:none;">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="page-header text-center">
        <div class="container">
            <h1 class="fw-bold mb-2">Contact Administration</h1>
            <p class="text-white-50 lead">Have inquiries regarding enrollment credentials or technical bugs? Message us.</p>
        </div>
    </header>

    <main class="container my-5 py-3">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card p-4 shadow-sm contact-card border-light">
                    <h4 class="fw-bold mb-3">Send Inquiry</h4>
                    @if(session('inquiry_sent'))
                        <div class="alert alert-success">Thank you! Your query has been logged.</div>
                    @endif
                    <form action="#" method="GET">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Subject</label>
                                <input type="text" class="form-control" placeholder="What is this inquiry about?" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Detailed Message</label>
                                <textarea class="form-control" rows="5" placeholder="Write message..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border:none;">Submit Inquiry</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card p-4 shadow-sm contact-card border-light h-100">
                    <h4 class="fw-bold mb-4">Campus Information</h4>
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-geo-alt-fill text-primary fs-3 me-3"></i>
                        <div>
                            <span class="d-block fw-bold small">Campus Location</span>
                            <span class="text-muted small">Academic Square, CSE Block, Engineering Hub</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-envelope-at-fill text-primary fs-3 me-3"></i>
                        <div>
                            <span class="d-block fw-bold small">Support Email</span>
                            <span class="text-muted small">support.erp@smartcollege.edu</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-telephone-fill text-primary fs-3 me-3"></i>
                        <div>
                            <span class="d-block fw-bold small">Administration Hotline</span>
                            <span class="text-muted small">+1 (555) 123-4567 (Mon-Fri)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-muted py-4 text-center mt-5">
        <div class="container small">
            <span class="text-white-50">&copy; 2026 Smart College ERP Portal</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
