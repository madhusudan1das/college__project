<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Student;
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
use App\Models\Message;
use App\Models\SalaryRecord;
use App\Models\ActivityLog;
use App\Models\Timetable;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacultyController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get active faculty model profile.
     */
    protected function getFacultyProfile()
    {
        return auth()->user()->faculty;
    }

    /**
     * Faculty Dashboard.
     */
    public function dashboard()
    {
        $faculty = $this->getFacultyProfile();
        if (!$faculty) {
            return redirect()->route('home')->with('error', 'Faculty profile not found.');
        }

        $subjectsTaughtIds = $faculty->subjects->pluck('id')->toArray();

        // Statistics
        $stats = [
            'subjects_count' => count($subjectsTaughtIds),
            'attendance_marked_count' => Attendance::where('marked_by_faculty_id', $faculty->id)->count(),
            'study_materials_count' => StudyMaterial::where('uploaded_by_faculty_id', $faculty->id)->count(),
            'exams_count' => Examination::where('created_by_faculty_id', $faculty->id)->count(),
            'queries_received' => Message::where('receiver_id', auth()->id())->count()
        ];

        // Fetch students in the subjects/classes taught
        $teachingClasses = AcademicClass::whereHas('department', function($q) use ($faculty) {
            $q->where('id', $faculty->department_id);
        })->get();

        $studentsCount = Student::whereIn('class_id', $teachingClasses->pluck('id'))->count();
        $stats['students_count'] = $studentsCount;

        // Fetch recent active notices
        $notices = Notice::whereIn('target_role', ['all', 'faculty'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('faculty.dashboard', compact('faculty', 'stats', 'teachingClasses', 'notices'));
    }

    /**
     * Notices List.
     */
    public function notices()
    {
        $notices = Notice::whereIn('target_role', ['all', 'faculty'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('faculty.notices', compact('notices'));
    }

    /* =========================================================================
     * Attendance Management
     * ========================================================================= */

    public function showMarkAttendance(Request $request)
    {
        $faculty = $this->getFacultyProfile();
        $classes = AcademicClass::where('department_id', $faculty->department_id)->get();
        $subjects = $faculty->subjects;

        $students = [];
        $selectedClass = null;
        $selectedSubject = null;
        $selectedDate = $request->date ?? Carbon::now()->toDateString();

        if ($request->filled('class_id') && $request->filled('subject_id')) {
            $selectedClass = AcademicClass::findOrFail($request->class_id);
            $selectedSubject = Subject::findOrFail($request->subject_id);

            // Fetch students in the class
            $students = Student::with(['user', 'attendance' => function($q) use ($selectedSubject, $selectedDate) {
                $q->where('subject_id', $selectedSubject->id)->where('date', $selectedDate);
            }])->where('class_id', $selectedClass->id)->get();
        }

        return view('faculty.attendance', compact('classes', 'subjects', 'students', 'selectedClass', 'selectedSubject', 'selectedDate'));
    }

    public function storeAttendance(Request $request)
    {
        $faculty = $this->getFacultyProfile();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array' // student_id => status
        ]);

        $classId = $request->class_id;
        $subjectId = $request->subject_id;
        $date = $request->date;

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'subject_id' => $subjectId,
                    'date' => $date
                ],
                [
                    'status' => $status,
                    'marked_by_faculty_id' => $faculty->id
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Attendance sheets captured successfully.']);
    }

    /* =========================================================================
     * Study Materials (CRUD)
     * ========================================================================= */

    public function studyMaterials()
    {
        $faculty = $this->getFacultyProfile();
        $materials = StudyMaterial::with('subject', 'class')
            ->where('uploaded_by_faculty_id', $faculty->id)
            ->get();
        
        $subjects = $faculty->subjects;
        $classes = AcademicClass::where('department_id', $faculty->department_id)->get();

        return view('faculty.study_materials', compact('materials', 'subjects', 'classes'));
    }

    public function storeStudyMaterial(Request $request)
    {
        $faculty = $this->getFacultyProfile();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240' // max 10MB
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('study_materials', 'public');
            
            StudyMaterial::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'uploaded_by_faculty_id' => $faculty->id
            ]);

            return redirect()->route('faculty.study-materials')->with('success', 'Study resource uploaded successfully.');
        }

        return back()->with('error', 'File upload failed.');
    }

    public function deleteStudyMaterial($id)
    {
        $faculty = $this->getFacultyProfile();
        $material = StudyMaterial::where('id', $id)->where('uploaded_by_faculty_id', $faculty->id)->firstOrFail();
        
        // Delete physical file
        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return redirect()->route('faculty.study-materials')->with('success', 'Study resource deleted.');
    }

    /* =========================================================================
     * Examination Module (+ AI Question Generator)
     * ========================================================================= */

    public function exams()
    {
        $faculty = $this->getFacultyProfile();
        $exams = Examination::with('subject', 'class', 'questions')
            ->where('created_by_faculty_id', $faculty->id)
            ->orderBy('exam_date', 'desc')
            ->get();
        
        $subjects = $faculty->subjects;
        $classes = AcademicClass::where('department_id', $faculty->department_id)->get();

        return view('faculty.exams', compact('exams', 'subjects', 'classes'));
    }

    public function storeExam(Request $request)
    {
        $faculty = $this->getFacultyProfile();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'exam_date' => 'required|date_format:Y-m-d\TH:i'
        ]);

        Examination::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks,
            'exam_date' => Carbon::parse($request->exam_date),
            'created_by_faculty_id' => $faculty->id
        ]);

        return redirect()->route('faculty.exams')->with('success', 'Online quiz schema created. You can now build questions.');
    }

    public function examQuestions($id)
    {
        $faculty = $this->getFacultyProfile();
        $exam = Examination::with('questions', 'subject')
            ->where('id', $id)
            ->where('created_by_faculty_id', $faculty->id)
            ->firstOrFail();

        return view('faculty.exam_questions', compact('exam'));
    }

    public function storeQuestion(Request $request, $examId)
    {
        $faculty = $this->getFacultyProfile();
        $exam = Examination::where('id', $examId)->where('created_by_faculty_id', $faculty->id)->firstOrFail();

        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1'
        ]);

        ExamQuestion::create(array_merge($request->all(), ['examination_id' => $exam->id]));

        return back()->with('success', 'Question appended.');
    }

    public function ajaxAIGenerateQuestions(Request $request, $examId)
    {
        $faculty = $this->getFacultyProfile();
        $exam = Examination::with('subject', 'questions')->where('id', $examId)->where('created_by_faculty_id', $faculty->id)->firstOrFail();

        $subjectName = $exam->subject->name;
        $difficulty = $request->difficulty ?? 'Medium';
        $count = $request->count ?? 5;
        $topics = $request->topics ?? '';

        $existingQuestions = $exam->questions->pluck('question_text')->toArray();

        $generated = $this->aiService->generateExamQuestions($subjectName, $difficulty, $count, $existingQuestions, $topics, auth()->id());

        // Insert into database
        $inserted = 0;
        foreach ($generated as $q) {
            ExamQuestion::create([
                'examination_id' => $exam->id,
                'question_text' => $q['question_text'],
                'option_a' => $q['option_a'],
                'option_b' => $q['option_b'],
                'option_c' => $q['option_c'],
                'option_d' => $q['option_d'],
                'correct_option' => $q['correct_option'],
                'points' => $q['points'] ?? 1
            ]);
            $inserted++;
        }

        return response()->json(['success' => true, 'message' => "Successfully integrated {$inserted} AI-generated questions into this quiz."]);
    }

    public function deleteQuestion($examId, $qId)
    {
        $faculty = $this->getFacultyProfile();
        $exam = Examination::where('id', $examId)->where('created_by_faculty_id', $faculty->id)->firstOrFail();
        
        ExamQuestion::where('id', $qId)->where('examination_id', $exam->id)->delete();
        return back()->with('success', 'Question deleted.');
    }

    public function publishExam($id)
    {
        $faculty = $this->getFacultyProfile();
        $exam = Examination::where('id', $id)->where('created_by_faculty_id', $faculty->id)->firstOrFail();
        $exam->update(['is_published' => true]);
        return back()->with('success', 'Quiz results and scores published to class.');
    }

    public function examResults($id)
    {
        $faculty = $this->getFacultyProfile();
        $exam = Examination::with('results.student.user')
            ->where('id', $id)
            ->where('created_by_faculty_id', $faculty->id)
            ->firstOrFail();

        return view('faculty.exam_results', compact('exam'));
    }

    /* =========================================================================
     * Leave Application CRUD
     * ========================================================================= */

    public function leaves()
    {
        $leaves = LeaveRequest::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('faculty.leaves', compact('leaves'));
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

        return redirect()->route('faculty.leaves')->with('success', 'Leave application registered. Awaiting Administrator action.');
    }

    /* =========================================================================
     * Student Queries Messaging
     * ========================================================================= */

    public function queries()
    {
        $messages = Message::with('sender')->where('receiver_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('faculty.queries', compact('messages'));
    }

    public function replyQuery(Request $request)
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

        return response()->json(['success' => true, 'message' => 'Your reply has been dispatched successfully.']);
    }

    /* =========================================================================
     * Salary Records
     * ========================================================================= */

    public function salaries()
    {
        $faculty = $this->getFacultyProfile();
        $salaries = SalaryRecord::where('faculty_id', $faculty->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('faculty.salaries', compact('salaries'));
    }

    /**
     * View faculty weekly schedule timetable.
     */
    public function timetable()
    {
        $faculty = $this->getFacultyProfile();
        if (!$faculty) {
            return redirect()->route('home')->with('error', 'Faculty profile not found.');
        }

        $timetable = Timetable::with('class', 'subject')
            ->where('faculty_id', $faculty->id)
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

        return view('faculty.timetable', compact('timetable'));
    }
}
