<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart College Assistant - AI ERP Portal</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafbfc;
            color: #1e293b;
        }
        .hero-section {
            background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(30, 41, 59) 90.1%);
            color: #fff;
            padding: 100px 0;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(79, 70, 229, 0) 100%);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .navbar-brand fw-bold {
            font-weight: 800;
        }
        .feature-card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 4px 25px rgba(0,0,0,0.02);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        }
        .circle-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            margin-bottom: 20px;
        }
        .footer {
            background-color: #0f172a;
            color: #94a3b8;
            padding: 50px 0;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 30px;
        }
        .btn-gradient:hover {
            opacity: 0.95;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ route('home') }}">
                <i class="bi bi-cpu-fill me-2 fs-3 text-indigo"></i>
                <span style="font-weight:800; color: #4f46e5;">Smart College ERP</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('about') }}">About System</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="{{ route('contact') }}">Contact Us</a></li>
                    @if(auth()->check())
                        <li class="nav-item ms-3">
                            @if(auth()->user()->isAdmin())
                                <a class="btn btn-gradient rounded-pill px-4" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            @elseif(auth()->user()->isFaculty())
                                <a class="btn btn-gradient rounded-pill px-4" href="{{ route('faculty.dashboard') }}">Faculty Dashboard</a>
                            @else
                                <a class="btn btn-gradient rounded-pill px-4" href="{{ route('student.dashboard') }}">Student Dashboard</a>
                            @endif
                        </li>
                    @else
                        <li class="nav-item ms-3"><a class="nav-link fw-semibold px-3 text-primary" href="{{ route('login') }}">Sign In</a></li>
                        <li class="nav-item"><a class="btn btn-gradient rounded-pill px-4 ms-2" href="{{ route('register') }}">Join Student Portal</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container hero-content text-center py-5">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-3 fw-bold">NEXT GENERATION EDUCATION</span>
            <h1 class="display-3 fw-extrabold mb-3 text-white" style="font-weight: 800;">Smart College Assistant</h1>
            <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 700px;">
                An AI-Powered College ERP System automating administrative tasks, class attendances, exam modules, study materials, and direct chatbot queries under a single responsive dashboard.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-gradient btn-lg px-4">Access ERP Portals</a>
                <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">Explore Features</a>
            </div>
        </div>
    </header>

    <!-- Role Portals Grid -->
    <section class="container my-5 py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Role-Based Secured Panels</h2>
            <p class="text-muted">Direct access portals integrated with strict middleware authorizations.</p>
        </div>
        <div class="row g-4">
            <!-- Admin -->
            <div class="col-md-4">
                <div class="card h-100 p-4 feature-card text-center">
                    <div class="circle-icon mx-auto"><i class="bi bi-shield-lock-fill"></i></div>
                    <h4 class="fw-bold mb-3">Admin Panel</h4>
                    <p class="text-muted">Enroll student classes, manage faculty payrolls, audit student complaints, compile notices, and check leaves requests.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary mt-auto rounded-pill px-3">Sign in as Admin</a>
                </div>
            </div>
            <!-- Faculty -->
            <div class="col-md-4">
                <div class="card h-100 p-4 feature-card text-center">
                    <div class="circle-icon mx-auto"><i class="bi bi-mortarboard-fill"></i></div>
                    <h4 class="fw-bold mb-3">Faculty Panel</h4>
                    <p class="text-muted">Take lecture attendance logs, share slide presentations/PDF study notes, generate quiz papers, and reply to queries.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary mt-auto rounded-pill px-3">Sign in as Faculty</a>
                </div>
            </div>
            <!-- Student -->
            <div class="col-md-4">
                <div class="card h-100 p-4 feature-card text-center">
                    <div class="circle-icon mx-auto"><i class="bi bi-person-check-fill"></i></div>
                    <h4 class="fw-bold mb-3">Student Panel</h4>
                    <p class="text-muted">View class attendance cards, attempt online quizzes with countdown timers, pay semester tuition fees, and open AI chats.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary mt-auto rounded-pill px-3">Sign in as Student</a>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Integration Showcase -->
    <section class="py-5" style="background-color: #f1f5f9;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-indigo-subtle text-primary border border-primary px-3 py-2 rounded-pill mb-3 fw-bold">ADVANCED ARTIFICIAL INTELLIGENCE</span>
                    <h2 class="fw-bold mb-4 display-6">AI Modules Integration</h2>
                    <p class="text-muted mb-4">
                        The Smart ERP leverages state-of-the-art Large Language Models (Gemini/OpenAI) to automate operations, predict attendance indicators, and provide academic support to enrolled students.
                    </p>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-robot text-indigo fs-4 me-3"></i> <strong>AI Student Assistant Chatbot</strong></li>
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-graph-up-arrow text-indigo fs-4 me-3"></i> <strong>AI Attendance Trend Risk Predictor</strong></li>
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-patch-question-fill text-indigo fs-4 me-3"></i> <strong>AI Smart Exam Question Generator</strong></li>
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-chat-text-fill text-indigo fs-4 me-3"></i> <strong>AI Student Complaint Automated Categorizer</strong></li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="card p-4 shadow border-0 rounded-4" style="background: radial-gradient(circle at 10% 20%, rgb(99, 102, 241) 0%, rgb(79, 70, 229) 90%); color:#fff;">
                        <h4 class="fw-bold mb-3"><i class="bi bi-stars"></i> Interactive AI Engine</h4>
                        <p class="small text-white-50">Below is a real response summary provided by our AI module analyzing student records:</p>
                        <hr class="border-light">
                        <div class="p-3 bg-white bg-opacity-10 rounded-3 mb-3">
                            <strong class="d-block mb-1">Prompt:</strong>
                            <span class="text-white-50 font-monospace fs-8">Analyze John Doe's attendance details for subject CS-302...</span>
                        </div>
                        <div class="p-3 bg-white bg-opacity-15 rounded-3">
                            <strong class="d-block mb-1"><i class="bi bi-cpu"></i> Assistant Response:</strong>
                            <p class="mb-0 fs-8 lh-base">
                                "Warning: Student has 20% attendance. High risk of failing semester evaluations. Recommend immediate consultation in DBMS Subject and focus on Relational Normalization topics."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center border-top border-secondary">
        <div class="container">
            <h5 class="fw-bold text-white mb-3">Smart College ERP System</h5>
            <p class="text-muted small">Copyright &copy; 2026. Powered by Laravel, Bootstrap, and Google Gemini.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
