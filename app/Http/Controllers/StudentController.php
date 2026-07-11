<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\AcademicClass;
use App\Models\Subject;
use App\Models\Notice;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\StudyMaterial;
use App\Models\Examination;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Complaint;
use App\Models\Message;
use App\Models\Timetable;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Helper to load student profile.
     */
    protected function getStudentProfile()
    {
        return auth()->user()->student;
    }

    /**
     * Student Dashboard.
     */
    public function dashboard()
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect()->route('home')->with('error', 'Student profile not found.');
        }

        // Attendance stats
        $totalLectures = Attendance::where('student_id', $student->id)->count();
        $present = Attendance::where('student_id', $student->id)->whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalLectures > 0 ? round(($present / $totalLectures) * 100, 1) : 0;

        // Results
        $examResultsCount = ExamResult::where('student_id', $student->id)->count();

        // Outstanding fees
        $pendingFees = Fee::where('student_id', $student->id)->where('status', '!=', 'paid')->sum('amount');

        // Recent notices
        $notices = Notice::whereIn('target_role', ['all', 'student'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('student', 'attendanceRate', 'examResultsCount', 'pendingFees', 'notices'));
    }

    /**
     * Notices board.
     */
    public function notices()
    {
        $notices = Notice::whereIn('target_role', ['all', 'student'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('student.notices', compact('notices'));
    }

    /**
     * Attendance log.
     */
    public function attendance()
    {
        $student = $this->getStudentProfile();
        $records = Attendance::with('subject', 'faculty.user')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        // Calculate subject-wise breakdown
        $breakdown = Attendance::where('student_id', $student->id)
            ->select('subject_id', 'status', DB::raw('count(*) as count'))
            ->groupBy('subject_id', 'status')
            ->get()
            ->groupBy('subject_id');

        $subjects = Subject::where('department_id', $student->department_id)->get();

        return view('student.attendance', compact('records', 'breakdown', 'subjects'));
    }

    /* =========================================================================
     * Fees & Simulated Payment Checkout
     * ========================================================================= */

    public function fees()
    {
        $student = $this->getStudentProfile();
        $fees = Fee::where('student_id', $student->id)->orderBy('due_date', 'asc')->get();
        $payments = Payment::with('fee')->where('student_id', $student->id)->orderBy('payment_date', 'desc')->get();
        return view('student.fees', compact('fees', 'payments'));
    }

    public function payFee(Request $request)
    {
        $student = $this->getStudentProfile();

        $request->validate([
            'fee_id' => 'required|exists:fees,id',
            'card_number' => 'required|numeric|digits:16',
            'expiry' => 'required|string',
            'cvv' => 'required|numeric|digits:3'
        ]);

        $fee = Fee::where('id', $request->fee_id)->where('student_id', $student->id)->firstOrFail();

        if ($fee->status === 'paid') {
            return response()->json(['success' => false, 'message' => 'This invoice has already been paid.']);
        }

        DB::transaction(function() use ($fee, $student) {
            $payment = Payment::create([
                'fee_id' => $fee->id,
                'student_id' => $student->id,
                'amount_paid' => $fee->amount,
                'payment_date' => Carbon::now(),
                'payment_method' => 'Credit Card',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'status' => 'completed'
            ]);

            $fee->update(['status' => 'paid']);

            // Generate receipt file_path (HTML)
            $receiptNo = 'REC-' . date('Ymd') . '-' . $payment->id;
            Receipt::create([
                'payment_id' => $payment->id,
                'receipt_no' => $receiptNo,
                'file_path' => "receipts/{$receiptNo}.html"
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Tuition fee transaction approved! Card processed.']);
    }

    public function viewReceipt($id)
    {
        $student = $this->getStudentProfile();
        $payment = Payment::with('fee', 'receipt')->where('id', $id)->where('student_id', $student->id)->firstOrFail();
        return view('student.receipt', compact('payment', 'student'));
    }

    /* =========================================================================
     * Study Materials Browser
     * ========================================================================= */

    public function studyMaterials()
    {
        $student = $this->getStudentProfile();
        // Load materials matching student's class
        $materials = StudyMaterial::with('subject', 'faculty.user')
            ->where('class_id', $student->class_id)
            ->get();

        return view('student.study_materials', compact('materials'));
    }

    /* =========================================================================
     * Online Examination Portal
     * ========================================================================= */

    public function exams()
    {
        $student = $this->getStudentProfile();

        // Get exams scheduled for this class
        $exams = Examination::with(['subject', 'results' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
        ->where('class_id', $student->class_id)
        ->where('is_published', true)
        ->orderBy('exam_date', 'desc')
        ->get();

        return view('student.exams', compact('exams'));
    }

    public function attemptExam($id)
    {
        $student = $this->getStudentProfile();
        $exam = Examination::with('questions')
            ->where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('is_published', true)
            ->firstOrFail();

        // Check if already attempted
        $alreadyAttempted = ExamResult::where('examination_id', $exam->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadyAttempted) {
            return redirect()->route('student.exams')->with('error', 'You have already attempted this examination.');
        }

        return view('student.exam_attempt', compact('exam'));
    }

    public function submitExam(Request $request, $id)
    {
        $student = $this->getStudentProfile();
        $exam = Examination::with('questions')
            ->where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('is_published', true)
            ->firstOrFail();

        $alreadyAttempted = ExamResult::where('examination_id', $exam->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadyAttempted) {
            return response()->json(['success' => false, 'message' => 'Already attempted.']);
        }

        $answers = $request->answers ?? []; // question_id => selected_option

        $totalQuestions = $exam->questions->count();
        $correct = 0;
        $wrong = 0;
        $score = 0;

        foreach ($exam->questions as $q) {
            $selected = $answers[$q->id] ?? null;
            if ($selected === $q->correct_option) {
                $correct++;
                $score += $q->points;
            } else {
                $wrong++;
            }
        }

        // Calculate pass percentage (40% minimum pass score)
        $passRate = $exam->total_marks > 0 ? ($score / $exam->total_marks) * 100 : 0;
        $passed = $passRate >= 40;

        $result = ExamResult::create([
            'examination_id' => $exam->id,
            'student_id' => $student->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correct,
            'wrong_answers' => $wrong,
            'marks_obtained' => $score,
            'passed' => $passed,
            'answers_json' => $answers
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Exam papers submitted successfully!',
            'score' => $score,
            'passed' => $passed,
            'result_id' => $result->id
        ]);
    }

    public function examResultDetails($id)
    {
        $student = $this->getStudentProfile();
        $result = ExamResult::with('exam.questions', 'exam.subject')
            ->where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('student.exam_results', compact('result'));
    }

    /* =========================================================================
     * Leave Application CRUD
     * ========================================================================= */

    public function leaves()
    {
        $leaves = LeaveRequest::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('student.leaves', compact('leaves'));
    }

    public function applyLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string'
        ]);

        LeaveRequest::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]));

        return redirect()->route('student.leaves')->with('success', 'Leave application registered.');
    }

    /* =========================================================================
     * Complaints Desk (with AI Categorization & Auto Comment)
     * ========================================================================= */

    public function complaints()
    {
        $student = $this->getStudentProfile();
        $complaints = Complaint::where('student_id', $student->id)->orderBy('created_at', 'desc')->get();
        return view('student.complaints', compact('complaints'));
    }

    public function storeComplaint(Request $request)
    {
        $student = $this->getStudentProfile();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        // Auto categorize via AI
        $aiResult = $this->aiService->categorizeComplaint($request->description, auth()->id());

        Complaint::create([
            'student_id' => $student->id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $aiResult['category'] ?? 'Others',
            'status' => 'pending',
            'ai_comment' => $aiResult['comment'] ?? null
        ]);

        return redirect()->route('student.complaints')->with('success', 'Complaint logged successfully. (AI analysis determined category: ' . $aiResult['category'] . ')');
    }

    /* =========================================================================
     * Inbox Messaging
     * ========================================================================= */

    public function messages()
    {
        $student = $this->getStudentProfile();
        // Load faculty members in the student's department
        $faculties = Faculty::with('user')->where('department_id', $student->department_id)->get();
        $messages = Message::with('sender')->where('receiver_id', auth()->id())->orderBy('created_at', 'desc')->get();
        $sentMessages = Message::with('receiver')->where('sender_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return view('student.messages', compact('faculties', 'messages', 'sentMessages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body
        ]);

        return redirect()->route('student.messages')->with('success', 'Message dispatched.');
    }

    /* =========================================================================
     * AI Performance Prediction & Study Recommendation Views
     * ========================================================================= */

    public function performanceRecommendation()
    {
        $student = $this->getStudentProfile();

        // 1. Gather historical grade data
        $quizResults = ExamResult::with('exam.subject')->where('student_id', $student->id)->get();

        // 2. Gather attendance rates
        $totalLectures = Attendance::where('student_id', $student->id)->count();
        $present = Attendance::where('student_id', $student->id)->whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalLectures > 0 ? round(($present / $totalLectures) * 100, 1) : 0;

        $subjectsData = [];
        foreach ($quizResults as $res) {
            $sub = $res->exam->subject->name;
            $subjectsData[$sub][] = [
                'exam_title' => $res->exam->title,
                'score' => $res->marks_obtained,
                'total' => $res->exam->total_marks
            ];
        }

        $analyticsPayload = [
            'student_name' => auth()->user()->name,
            'attendance_percentage' => $attendanceRate,
            'quizzes' => $subjectsData
        ];

        // Fetch predictions and recommendations via AI
        $predictionText = $this->aiService->predictPerformance($analyticsPayload, auth()->id());
        $recommendationText = $this->aiService->recommendStudyMaterials($analyticsPayload, auth()->id());

        return view('student.performance', compact('predictionText', 'recommendationText', 'attendanceRate', 'quizResults'));
    }

    /**
     * View student weekly schedule timetable.
     */
    public function timetable()
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect()->route('home')->with('error', 'Student profile not found.');
        }

        $timetable = Timetable::with('subject', 'faculty.user')
            ->where('class_id', $student->class_id)
            ->get();

        $dayOrder = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7
        ];

        $timetable = $timetable->sortBy(function($item) use ($dayOrder) {
            return ($dayOrder[$item->day_of_week] ?? 8) . '_' . $item->start_time;
        });

        return view('student.timetable', compact('timetable'));
    }
}
