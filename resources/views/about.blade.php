<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Smart College Assistant</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafbfc; color: #1e293b; }
        .page-header { background: #0f172a; color: #fff; padding: 60px 0; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; }
        .badge-ai { background-color: #4f46e5; color: #fff; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .card-feature { border: 1px solid rgba(226,232,240,0.8); border-radius: 15px; background: #fff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}" style="font-weight:800; color: #4f46e5;"><i class="bi bi-cpu-fill me-2 fs-3"></i>Smart College ERP</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3 active text-primary" href="{{ route('about') }}">About System</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-primary rounded-pill px-4 text-white" href="{{ route('login') }}" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border:none;">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="page-header text-center">
        <div class="container">
            <h1 class="fw-bold mb-2">About The System</h1>
            <p class="text-white-50 lead">Discover how AI automation is revolutionizing traditional college administrations.</p>
        </div>
    </header>

    <main class="container my-5 py-3">
        <div class="row align-items-center mb-5 g-4">
            <div class="col-md-6">
                <h3 class="fw-bold">Smart College Assistant</h3>
                <p class="text-muted leading-relaxed">
                    This platform integrates core ERP capabilities (student enrollment records, teaching assignments, salary tracking, fee billing invoicing) with Generative Artificial Intelligence. Built on a secured, modern PHP framework (Laravel 11), it features high-availability APIs, clean databases, and AJAX-driven UI cards.
                </p>
                <p class="text-muted">
                    We leverage Large Language Models to analyze statistical outliers (such as student absentees and low internal grades) to predict failures and compile automatic summaries.
                </p>
            </div>
            <div class="col-md-6">
                <div class="p-4 rounded-4 shadow-sm bg-white border border-light">
                    <h5 class="fw-bold"><i class="bi bi-gear-fill text-primary"></i> Technical Architecture</h5>
                    <hr>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-chevron-right text-primary me-2"></i><strong>Backend:</strong> Laravel PHP (MVC, Query Builder, Middlewares)</li>
                        <li class="mb-2"><i class="bi bi-chevron-right text-primary me-2"></i><strong>Database:</strong> MySQL (Relational Schema, Keys)</li>
                        <li class="mb-2"><i class="bi bi-chevron-right text-primary me-2"></i><strong>UI System:</strong> Bootstrap 5 (CSS Grid, Modals, Forms)</li>
                        <li class="mb-2"><i class="bi bi-chevron-right text-primary me-2"></i><strong>Dynamic Actions:</strong> jQuery AJAX (Async forms, live checks)</li>
                        <li><i class="bi bi-chevron-right text-primary me-2"></i><strong>AI Service:</strong> Google Gemini API Integration</li>
                    </ul>
                </div>
            </div>
        </div>

        <h3 class="fw-bold text-center mb-4">Core AI Features In-Depth</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4 h-100 card-feature shadow-sm">
                    <span class="badge-ai mb-3 d-inline-block" style="width:fit-content;">MODULE 1</span>
                    <h5 class="fw-bold">Student Chatbot</h5>
                    <p class="text-muted small mb-0">Floating student sidebar chat window answering student inquiries on syllabus, programming code, and academic tutorials.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100 card-feature shadow-sm">
                    <span class="badge-ai mb-3 d-inline-block" style="width:fit-content;">MODULE 2</span>
                    <h5 class="fw-bold">Attendance Analytics</h5>
                    <p class="text-muted small mb-0">Evaluates class-wide attendance databases, flags profiles under the 75% threshold, and suggests engaging solutions for lecturers.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100 card-feature shadow-sm">
                    <span class="badge-ai mb-3 d-inline-block" style="width:fit-content;">MODULE 3</span>
                    <h5 class="fw-bold">Performance Predictor</h5>
                    <p class="text-muted small mb-0">Calculates student passing risk levels (Low/Medium/High) by analyzing past marks, lesson attendances, and study habits.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100 card-feature shadow-sm">
                    <span class="badge-ai mb-3 d-inline-block" style="width:fit-content;">MODULE 4</span>
                    <h5 class="fw-bold">Study Recommendations</h5>
                    <p class="text-muted small mb-0">Matches quiz failures with appropriate library files and recommends customized guidelines to cover weaker subject topics.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100 card-feature shadow-sm">
                    <span class="badge-ai mb-3 d-inline-block" style="width:fit-content;">MODULE 5</span>
                    <h5 class="fw-bold">Smart Notice Summary</h5>
                    <p class="text-muted small mb-0">Automatically summarizes verbose notice sheets into single-sentence headlines for student dashboard notifications.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100 card-feature shadow-sm">
                    <span class="badge-ai mb-3 d-inline-block" style="width:fit-content;">MODULE 6</span>
                    <h5 class="fw-bold">Complaint Categorizer</h5>
                    <p class="text-muted small mb-0">Automatically indexes student text grievances into Facilities, Academic, or Fees, and outputs recommended actions.</p>
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
