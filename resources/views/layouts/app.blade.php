<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart College Assistant')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom Modern Styling -->
    <style>
        :root {
            --primary-bg: #f8fafc;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --accent-color: #6366f1;
            --accent-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 10px -2px rgba(0, 0, 0, 0.03);
            --glass-bg: rgba(255, 255, 255, 0.8);
            --text-main: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            background-color: var(--sidebar-bg);
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 1.5rem 1.2rem;
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.5px;
            font-size: 1.15rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        #sidebar-wrapper .list-group-item {
            background: none;
            border: none;
            color: #94a3b8;
            padding: 0.85rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            border-radius: 8px;
            margin: 0.2rem 0.8rem;
            transition: all 0.2s ease;
        }

        #sidebar-wrapper .list-group-item i {
            font-size: 1.2rem;
            margin-right: 0.85rem;
            transition: transform 0.2s ease;
        }

        #sidebar-wrapper .list-group-item:hover, 
        #sidebar-wrapper .list-group-item.active {
            background-color: var(--sidebar-hover);
            color: #fff;
        }

        #sidebar-wrapper .list-group-item.active {
            background: var(--accent-gradient);
            color: #fff;
        }

        #sidebar-wrapper .list-group-item:hover i {
            transform: scale(1.1);
        }

        /* Page Content Wrapper */
        #page-content-wrapper {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Navbar Customization */
        .custom-navbar {
            background-color: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0.85rem 1.5rem;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--accent-gradient);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Cards and Badges */
        .glass-card {
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .btn-accent {
            background: var(--accent-gradient);
            color: #fff;
            border: none;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            border-radius: 8px;
            transition: opacity 0.2s ease;
        }

        .btn-accent:hover {
            opacity: 0.9;
            color: #fff;
        }

        /* Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Mobile Responsive adjustments */
        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                margin-left: -260px;
            }
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled #page-content-wrapper {
                position: absolute;
                margin-right: -260px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading d-flex align-items-center">
                <i class="bi bi-cpu-fill text-info me-2 fs-4"></i>
                <div>
                    <span class="d-block lh-1 text-uppercase fw-bold text-white fs-6">Smart ERP</span>
                    <small class="text-muted fs-8">AI Assistant Portal</small>
                </div>
            </div>
            <div class="list-group list-group-flush mt-3">
                @if(auth()->check())
                    @if(auth()->user()->isAdmin())
                        <!-- Admin Navigation links -->
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.students') }}" class="list-group-item {{ Request::routeIs('admin.students') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i>Students
                        </a>
                        <a href="{{ route('admin.faculty') }}" class="list-group-item {{ Request::routeIs('admin.faculty') ? 'active' : '' }}">
                            <i class="bi bi-person-workspace"></i>Faculty
                        </a>
                        <a href="{{ route('admin.departments') }}" class="list-group-item {{ Request::routeIs('admin.departments') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>Departments
                        </a>
                        <a href="{{ route('admin.notices') }}" class="list-group-item {{ Request::routeIs('admin.notices') ? 'active' : '' }}">
                            <i class="bi bi-megaphone-fill"></i>Notices
                        </a>
                        <a href="{{ route('admin.leaves') }}" class="list-group-item {{ Request::routeIs('admin.leaves') ? 'active' : '' }}">
                            <i class="bi bi-calendar-range-fill"></i>Leaves
                        </a>
                        <a href="{{ route('admin.attendance') }}" class="list-group-item {{ Request::routeIs('admin.attendance') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check-fill"></i>Attendance
                        </a>
                        <a href="{{ route('admin.salaries') }}" class="list-group-item {{ Request::routeIs('admin.salaries') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack"></i>Faculty Salaries
                        </a>
                        <a href="{{ route('admin.fees') }}" class="list-group-item {{ Request::routeIs('admin.fees') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-fill"></i>Tuition Fees
                        </a>
                        <a href="{{ route('admin.complaints') }}" class="list-group-item {{ Request::routeIs('admin.complaints') ? 'active' : '' }}">
                            <i class="bi bi-exclamation-triangle-fill"></i>Complaints
                        </a>
                        <a href="{{ route('admin.reports') }}" class="list-group-item {{ Request::routeIs('admin.reports') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>System Reports
                        </a>
                        <a href="{{ route('admin.ai-logs') }}" class="list-group-item {{ Request::routeIs('admin.ai-logs') ? 'active' : '' }}">
                            <i class="bi bi-journal-code"></i>AI Audit Logs
                        </a>
                    @elseif(auth()->user()->isFaculty())
                        <!-- Faculty Navigation links -->
                        <a href="{{ route('faculty.dashboard') }}" class="list-group-item {{ Request::routeIs('faculty.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i>Dashboard
                        </a>
                        <a href="{{ route('faculty.attendance') }}" class="list-group-item {{ Request::routeIs('faculty.attendance') ? 'active' : '' }}">
                            <i class="bi bi-check-all"></i>Mark Attendance
                        </a>
                        <a href="{{ route('faculty.timetable') }}" class="list-group-item {{ Request::routeIs('faculty.timetable') ? 'active' : '' }}">
                            <i class="bi bi-calendar3"></i>Class Timetable
                        </a>
                        <a href="{{ route('faculty.study-materials') }}" class="list-group-item {{ Request::routeIs('faculty.study-materials') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-zip-fill"></i>Study Materials
                        </a>
                        <a href="{{ route('faculty.exams') }}" class="list-group-item {{ Request::routeIs('faculty.exams') || Request::routeIs('faculty.exams.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-check"></i>Online Examinations
                        </a>
                        <a href="{{ route('faculty.leaves') }}" class="list-group-item {{ Request::routeIs('faculty.leaves') ? 'active' : '' }}">
                            <i class="bi bi-calendar-range"></i>Apply Leave
                        </a>
                        <a href="{{ route('faculty.queries') }}" class="list-group-item {{ Request::routeIs('faculty.queries') ? 'active' : '' }}">
                            <i class="bi bi-chat-left-text-fill"></i>Student Queries
                        </a>
                        <a href="{{ route('faculty.salaries') }}" class="list-group-item {{ Request::routeIs('faculty.salaries') ? 'active' : '' }}">
                            <i class="bi bi-wallet2"></i>My Salary
                        </a>
                        <a href="{{ route('faculty.notices') }}" class="list-group-item {{ Request::routeIs('faculty.notices') ? 'active' : '' }}">
                            <i class="bi bi-megaphone"></i>Notices
                        </a>
                    @elseif(auth()->user()->isStudent())
                        <!-- Student Navigation links -->
                        <a href="{{ route('student.dashboard') }}" class="list-group-item {{ Request::routeIs('student.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i>Dashboard
                        </a>
                        <a href="{{ route('student.attendance') }}" class="list-group-item {{ Request::routeIs('student.attendance') ? 'active' : '' }}">
                            <i class="bi bi-check2-square"></i>My Attendance
                        </a>
                        <a href="{{ route('student.timetable') }}" class="list-group-item {{ Request::routeIs('student.timetable') ? 'active' : '' }}">
                            <i class="bi bi-calendar3"></i>Class Timetable
                        </a>
                        <a href="{{ route('student.study-materials') }}" class="list-group-item {{ Request::routeIs('student.study-materials') ? 'active' : '' }}">
                            <i class="bi bi-book"></i>Study Notes
                        </a>
                        <a href="{{ route('student.exams') }}" class="list-group-item {{ Request::routeIs('student.exams') || Request::routeIs('student.exams.*') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square"></i>Online Exams
                        </a>
                        <a href="{{ route('student.fees') }}" class="list-group-item {{ Request::routeIs('student.fees') ? 'active' : '' }}">
                            <i class="bi bi-credit-card"></i>Online Fees
                        </a>
                        <a href="{{ route('student.leaves') }}" class="list-group-item {{ Request::routeIs('student.leaves') ? 'active' : '' }}">
                            <i class="bi bi-calendar-day"></i>Apply Leave
                        </a>
                        <a href="{{ route('student.complaints') }}" class="list-group-item {{ Request::routeIs('student.complaints') ? 'active' : '' }}">
                            <i class="bi bi-shield-exclamation"></i>File Complaint
                        </a>
                        <a href="{{ route('student.messages') }}" class="list-group-item {{ Request::routeIs('student.messages') ? 'active' : '' }}">
                            <i class="bi bi-chat-left-quote"></i>Contact Faculty
                        </a>
                        <a href="{{ route('student.performance') }}" class="list-group-item {{ Request::routeIs('student.performance') ? 'active' : '' }}">
                            <i class="bi bi-stars"></i>AI Advisor Recommendations
                        </a>
                        <a href="{{ route('student.notices') }}" class="list-group-item {{ Request::routeIs('student.notices') ? 'active' : '' }}">
                            <i class="bi bi-megaphone"></i>Notices
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <!-- /Sidebar -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
                <div class="container-fluid">
                    <button class="btn btn-light border me-3" id="menu-toggle">
                        <i class="bi bi-justify fs-5"></i>
                    </button>
                    
                    <h4 class="mb-0 fw-bold d-none d-md-block">Smart ERP Portal</h4>

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Navigation dropdown -->
                        @if(auth()->check())
                            <span class="me-2 text-muted fw-medium d-none d-sm-inline">Hello, {{ auth()->user()->name }}</span>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2" aria-labelledby="profileDropdown" style="border-radius: 12px; min-width: 200px;">
                                    <li>
                                        <div class="px-3 py-2">
                                            <span class="d-block fw-bold">{{ auth()->user()->name }}</span>
                                            <span class="d-block text-muted fs-8">{{ auth()->user()->email }}</span>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 rounded" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger rounded"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Login</a>
                        @endif
                    </div>
                </div>
            </nav>
            <!-- /Top Navbar -->

            <!-- Main Content Container -->
            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 py-3 px-4 mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <strong>Success!</strong> {{ session('success') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 py-3 px-4 mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-octagon-fill text-danger fs-4 me-3"></i>
                            <div>
                                <strong>Access Warning!</strong> {{ session('error') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
            <!-- /Main Content Container -->
        </div>
        <!-- /Page Content -->
    </div>

    <!-- Student Floating Chatbot (only shown for logged-in students) -->
    @if(auth()->check() && auth()->user()->isStudent())
        <!-- Chatbot Floating Launcher -->
        <button class="btn btn-accent rounded-circle shadow-lg" id="chatbot-launcher" style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 9999; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-chat-dots-fill fs-3"></i>
        </button>

        <!-- Chatbot Drawer Window -->
        <div class="card shadow-lg border-0" id="chatbot-window" style="position: fixed; bottom: 100px; right: 30px; width: 370px; height: 500px; z-index: 9999; border-radius: 20px; display: none; overflow: hidden; flex-direction: column;">
            <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between" style="background: var(--accent-gradient) !important;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-robot fs-4 me-2"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">AI Student Assistant</h6>
                        <small class="text-white-50 fs-8">Gemini Intelligence</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" id="chatbot-close" aria-label="Close"></button>
            </div>
            <div class="card-body bg-light p-3" id="chatbot-messages" style="flex: 1; overflow-y: auto; font-size: 0.9rem;">
                <div class="chat-message bot mb-3 d-flex">
                    <div class="avatar bg-white border rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; flex-shrink: 0;">
                        <i class="bi bi-robot text-primary"></i>
                    </div>
                    <div class="msg-bubble p-3 rounded-4 bg-white shadow-sm" style="max-width: 80%;">
                        Hello! I am your AI academic advisor. Ask me anything about your studies, assignments, exam guidelines, or subjects!
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top p-3">
                <form id="chatbot-form" class="d-flex gap-2">
                    <input type="text" id="chatbot-input" class="form-control rounded-pill border-muted py-2" placeholder="Ask a question..." required>
                    <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0; background: var(--accent-gradient) !important; border:none;">
                        <i class="bi bi-send-fill text-white"></i>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap 5 Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Base Scripts -->
    <script>
        $(document).ready(function() {
            // Sidebar toggle
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });

            // Set AJAX header globally
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Chatbot toggling and logic
            $("#chatbot-launcher").click(function() {
                $("#chatbot-window").css("display") === "none" ? $("#chatbot-window").css("display", "flex") : $("#chatbot-window").hide();
            });

            $("#chatbot-close").click(function() {
                $("#chatbot-window").hide();
            });

            $("#chatbot-form").submit(function(e) {
                e.preventDefault();
                const msg = $("#chatbot-input").val().trim();
                if(!msg) return;

                // Append user message
                $("#chatbot-messages").append(`
                    <div class="chat-message user mb-3 d-flex justify-content-end">
                        <div class="msg-bubble p-3 rounded-4 bg-primary text-white shadow-sm" style="max-width: 80%; background: var(--accent-gradient) !important;">
                            ${msg}
                        </div>
                    </div>
                `);

                $("#chatbot-input").val('');
                $("#chatbot-messages").scrollTop($("#chatbot-messages")[0].scrollHeight);

                // Show typing loader
                const loaderId = "typing-" + Date.now();
                $("#chatbot-messages").append(`
                    <div class="chat-message bot mb-3 d-flex" id="${loaderId}">
                        <div class="avatar bg-white border rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; flex-shrink: 0;">
                            <i class="bi bi-robot text-primary"></i>
                        </div>
                        <div class="msg-bubble p-3 rounded-4 bg-white shadow-sm" style="max-width: 80%;">
                            <span class="spinner-grow spinner-grow-sm text-primary" role="status" aria-hidden="true"></span>
                            <span class="ms-1 text-muted">AI is thinking...</span>
                        </div>
                    </div>
                `);
                $("#chatbot-messages").scrollTop($("#chatbot-messages")[0].scrollHeight);

                // Dispatch AJAX request
                $.ajax({
                    url: "{{ route('student.chatbot.chat') }}",
                    type: "POST",
                    data: { message: msg },
                    success: function(res) {
                        $(`#${loaderId}`).remove();
                        if (res.success) {
                            // Simple markdown newlines transformation helper
                            let replyHtml = res.reply
                                .replace(/\n/g, '<br>')
                                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                                .replace(/\*(.*?)\*/g, '<em>$1</em>');

                            $("#chatbot-messages").append(`
                                <div class="chat-message bot mb-3 d-flex">
                                    <div class="avatar bg-white border rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; flex-shrink: 0;">
                                        <i class="bi bi-robot text-primary"></i>
                                    </div>
                                    <div class="msg-bubble p-3 rounded-4 bg-white shadow-sm" style="max-width: 80%;">
                                        ${replyHtml}
                                    </div>
                                </div>
                            `);
                        } else {
                            $("#chatbot-messages").append(`
                                <div class="chat-message bot mb-3 d-flex">
                                    <div class="avatar bg-white border rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; flex-shrink: 0;">
                                        <i class="bi bi-robot text-danger"></i>
                                    </div>
                                    <div class="msg-bubble p-3 rounded-4 bg-white shadow-sm text-danger" style="max-width: 80%;">
                                        Sorry, something went wrong with the AI assistant call.
                                    </div>
                                </div>
                            `);
                        }
                        $("#chatbot-messages").scrollTop($("#chatbot-messages")[0].scrollHeight);
                    },
                    error: function() {
                        $(`#${loaderId}`).remove();
                        $("#chatbot-messages").append(`
                            <div class="chat-message bot mb-3 d-flex">
                                <div class="avatar bg-white border rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; flex-shrink: 0;">
                                    <i class="bi bi-robot text-danger"></i>
                                </div>
                                <div class="msg-bubble p-3 rounded-4 bg-white shadow-sm text-danger" style="max-width: 80%;">
                                    Network connection failed. Let's try again.
                                </div>
                            </div>
                        `);
                        $("#chatbot-messages").scrollTop($("#chatbot-messages")[0].scrollHeight);
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
