<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\AcademicClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Notice;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\StudyMaterial;
use App\Models\Examination;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\Complaint;
use App\Models\Message;
use App\Models\SalaryRecord;
use App\Models\Timetable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $facultyRole = Role::create(['name' => 'faculty', 'display_name' => 'Faculty Member']);
        $studentRole = Role::create(['name' => 'student', 'display_name' => 'Student']);

        // 2. Seed Departments
        $cseDept = Department::create(['name' => 'Computer Science & Engineering', 'code' => 'CSE']);
        $eceDept = Department::create(['name' => 'Electronics & Communication Engineering', 'code' => 'ECE']);
        $meDept = Department::create(['name' => 'Mechanical Engineering', 'code' => 'ME']);

        // 3. Seed Classes
        $cseClassA = AcademicClass::create([
            'name' => 'CSE Semester 3 - Section A',
            'code' => 'CSE-S3-A',
            'department_id' => $cseDept->id
        ]);
        $cseClassB = AcademicClass::create([
            'name' => 'CSE Semester 3 - Section B',
            'code' => 'CSE-S3-B',
            'department_id' => $cseDept->id
        ]);
        $eceClassA = AcademicClass::create([
            'name' => 'ECE Semester 3 - Section A',
            'code' => 'ECE-S3-A',
            'department_id' => $eceDept->id
        ]);

        // 4. Seed Subjects
        $networksSub = Subject::create([
            'name' => 'Computer Networks',
            'code' => 'CS-301',
            'department_id' => $cseDept->id
        ]);
        $dbmsSub = Subject::create([
            'name' => 'Database Management Systems',
            'code' => 'CS-302',
            'department_id' => $cseDept->id
        ]);
        $digitalSub = Subject::create([
            'name' => 'Digital Electronics',
            'code' => 'EC-201',
            'department_id' => $eceDept->id
        ]);
        $thermoSub = Subject::create([
            'name' => 'Thermodynamics',
            'code' => 'ME-101',
            'department_id' => $meDept->id
        ]);

        // 5. Seed Users & Profiles

        // Admin User
        $adminUser = User::create([
            'name' => 'System Admin',
            'email' => 'admin@college.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'phone' => '+19876543210',
            'status' => 'active'
        ]);

        // Faculty 1 User (Alan Turing)
        $turingUser = User::create([
            'name' => 'Dr. Alan Turing',
            'email' => 'turing@college.com',
            'password' => Hash::make('password'),
            'role_id' => $facultyRole->id,
            'phone' => '+1112223334',
            'status' => 'active'
        ]);
        $turingFaculty = Faculty::create([
            'user_id' => $turingUser->id,
            'department_id' => $cseDept->id,
            'designation' => 'Professor & Head',
            'qualification' => 'Ph.D. in Computer Science',
            'joining_date' => '2020-01-10',
            'gender' => 'Male',
            'address' => 'Bletchley Park, CSE Faculty block Room 102'
        ]);
        // Pivot teaches subjects
        $turingFaculty->subjects()->attach([$networksSub->id, $dbmsSub->id]);

        // Faculty 2 User (Grace Hopper)
        $hopperUser = User::create([
            'name' => 'Dr. Grace Hopper',
            'email' => 'hopper@college.com',
            'password' => Hash::make('password'),
            'role_id' => $facultyRole->id,
            'phone' => '+1222333444',
            'status' => 'active'
        ]);
        $hopperFaculty = Faculty::create([
            'user_id' => $hopperUser->id,
            'department_id' => $cseDept->id,
            'designation' => 'Associate Professor',
            'qualification' => 'Ph.D. in Applied Mathematics',
            'joining_date' => '2021-08-15',
            'gender' => 'Female',
            'address' => 'Cobol Avenue, CSE Faculty Block Room 104'
        ]);
        $hopperFaculty->subjects()->attach([$dbmsSub->id]);

        // Student 1 (John Doe)
        $johnUser = User::create([
            'name' => 'John Doe',
            'email' => 'john@college.com',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
            'phone' => '+1555666777',
            'status' => 'active'
        ]);
        $johnStudent = Student::create([
            'user_id' => $johnUser->id,
            'department_id' => $cseDept->id,
            'class_id' => $cseClassA->id,
            'roll_no' => 'CSE-2026-001',
            'admission_no' => 'ADM-10001',
            'dob' => '2005-04-12',
            'gender' => 'Male',
            'address' => '123 Baker Street, London'
        ]);

        // Student 2 (Jane Smith)
        $janeUser = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@college.com',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
            'phone' => '+1555888999',
            'status' => 'active'
        ]);
        $janeStudent = Student::create([
            'user_id' => $janeUser->id,
            'department_id' => $cseDept->id,
            'class_id' => $cseClassA->id,
            'roll_no' => 'CSE-2026-002',
            'admission_no' => 'ADM-10002',
            'dob' => '2005-09-21',
            'gender' => 'Female',
            'address' => '456 Elm Street, Springfield'
        ]);

        // 6. Seed Attendance
        $dates = [
            Carbon::now()->subDays(4)->toDateString(),
            Carbon::now()->subDays(3)->toDateString(),
            Carbon::now()->subDays(2)->toDateString(),
            Carbon::now()->subDays(1)->toDateString(),
            Carbon::now()->toDateString(),
        ];

        // CS-301 Attendance
        foreach ($dates as $index => $date) {
            // John Doe: Present 4 times, Absent 1 time
            Attendance::create([
                'student_id' => $johnStudent->id,
                'class_id' => $cseClassA->id,
                'subject_id' => $networksSub->id,
                'date' => $date,
                'status' => $index === 2 ? 'absent' : 'present',
                'marked_by_faculty_id' => $turingFaculty->id
            ]);

            // Jane Smith: Present 5 times
            Attendance::create([
                'student_id' => $janeStudent->id,
                'class_id' => $cseClassA->id,
                'subject_id' => $networksSub->id,
                'date' => $date,
                'status' => 'present',
                'marked_by_faculty_id' => $turingFaculty->id
            ]);
        }

        // CS-302 Attendance (John Doe has low attendance here to test AI analysis)
        foreach ($dates as $index => $date) {
            // John Doe: Present 1 time, Absent 4 times
            Attendance::create([
                'student_id' => $johnStudent->id,
                'class_id' => $cseClassA->id,
                'subject_id' => $dbmsSub->id,
                'date' => $date,
                'status' => $index === 0 ? 'present' : 'absent',
                'marked_by_faculty_id' => $hopperFaculty->id
            ]);

            // Jane Smith: Present 4 times, Late 1 time
            Attendance::create([
                'student_id' => $janeStudent->id,
                'class_id' => $cseClassA->id,
                'subject_id' => $dbmsSub->id,
                'date' => $date,
                'status' => $index === 4 ? 'late' : 'present',
                'marked_by_faculty_id' => $hopperFaculty->id
            ]);
        }

        // 7. Seed Notices
        Notice::create([
            'title' => 'Important: Fall Semester 2026 Registration Open',
            'content' => 'All students of Computer Science and Electronics departments are instructed to register for the upcoming Fall Semester 2026. The online registration portal will close on August 15th, 2026. Late fees will be applied after the due date.',
            'published_by' => $adminUser->id,
            'target_role' => 'all',
            'summary' => 'Fall Semester 2026 registration is open for CSE & ECE students. The registration portal closes on August 15th, 2026. Late fee charges will apply post-deadline.'
        ]);

        Notice::create([
            'title' => 'Annual Coding Hackathon - CodeCraft 2026',
            'content' => 'The Computer Science department is organizing CodeCraft 2026, the annual 24-hour coding hackathon. It is scheduled to take place on July 25th in the Central Auditorium. Prizes worth $5000 are up for grabs! Register in teams of 3 to 4 before July 20th.',
            'published_by' => $adminUser->id,
            'target_role' => 'student',
            'summary' => 'Annual 24-hour hackathon (CodeCraft 2026) scheduled for July 25th in Central Auditorium. Register in teams of 3-4 by July 20th. Cash prizes up to $5000.'
        ]);

        // 8. Seed Study Materials
        StudyMaterial::create([
            'title' => 'Introduction to Computer Networks Lecture Slides',
            'description' => 'A basic overview of OSI Reference Model, TCP/IP Suite, and network topologies.',
            'file_path' => 'study_materials/lecture_1_intro_networks.pdf',
            'subject_id' => $networksSub->id,
            'class_id' => $cseClassA->id,
            'uploaded_by_faculty_id' => $turingFaculty->id
        ]);

        StudyMaterial::create([
            'title' => 'Database Normalization Study Guide',
            'description' => 'Detailed notes covering 1NF, 2NF, 3NF, BCNF, with multiple practice queries and relational decompositions.',
            'file_path' => 'study_materials/lecture_dbms_normalization.pdf',
            'subject_id' => $dbmsSub->id,
            'class_id' => $cseClassA->id,
            'uploaded_by_faculty_id' => $hopperFaculty->id
        ]);

        // 9. Seed Fees
        $johnFee1 = Fee::create([
            'student_id' => $johnStudent->id,
            'title' => 'Fall Semester Tuition Fee 2026',
            'amount' => 1500.00,
            'due_date' => '2026-08-30',
            'status' => 'unpaid'
        ]);

        $johnFee2 = Fee::create([
            'student_id' => $johnStudent->id,
            'title' => 'Campus Gym & Sports Facility Fee',
            'amount' => 200.00,
            'due_date' => '2026-07-20',
            'status' => 'unpaid'
        ]);

        $janeFee1 = Fee::create([
            'student_id' => $janeStudent->id,
            'title' => 'Fall Semester Tuition Fee 2026',
            'amount' => 1500.00,
            'due_date' => '2026-08-30',
            'status' => 'paid'
        ]);

        $janeFee2 = Fee::create([
            'student_id' => $janeStudent->id,
            'title' => 'Campus Gym & Sports Facility Fee',
            'amount' => 200.00,
            'due_date' => '2026-07-20',
            'status' => 'unpaid'
        ]);

        // 10. Seed Payments
        $janePayment = Payment::create([
            'fee_id' => $janeFee1->id,
            'student_id' => $janeStudent->id,
            'amount_paid' => 1500.00,
            'payment_date' => Carbon::now()->subDays(2),
            'payment_method' => 'Credit Card',
            'transaction_id' => 'TXN-88776655JANE',
            'status' => 'completed'
        ]);

        // 11. Seed Receipt
        Receipt::create([
            'payment_id' => $janePayment->id,
            'receipt_no' => 'REC-20260703-991',
            'file_path' => 'receipts/rec-20260703-991.html'
        ]);

        // 12. Seed Salary Records
        SalaryRecord::create([
            'faculty_id' => $turingFaculty->id,
            'base_salary' => 8500.00,
            'bonuses' => 500.00,
            'deductions' => 200.00,
            'net_salary' => 8800.00,
            'payment_date' => Carbon::now()->subMonth()->startOfMonth()->addDays(4),
            'status' => 'paid'
        ]);

        SalaryRecord::create([
            'faculty_id' => $turingFaculty->id,
            'base_salary' => 8500.00,
            'bonuses' => 0.00,
            'deductions' => 0.00,
            'net_salary' => 8500.00,
            'payment_date' => null,
            'status' => 'pending'
        ]);

        SalaryRecord::create([
            'faculty_id' => $hopperFaculty->id,
            'base_salary' => 7000.00,
            'bonuses' => 300.00,
            'deductions' => 100.00,
            'net_salary' => 7200.00,
            'payment_date' => Carbon::now()->subMonth()->startOfMonth()->addDays(4),
            'status' => 'paid'
        ]);

        // 13. Seed Examinations
        $networksExam = Examination::create([
            'title' => 'Quiz 1: Network OSI Reference Model',
            'description' => 'A short check on your understanding of OSI layers, protocols, and standard models.',
            'subject_id' => $networksSub->id,
            'class_id' => $cseClassA->id,
            'duration_minutes' => 10,
            'total_marks' => 5,
            'exam_date' => Carbon::now()->subDays(1),
            'is_published' => true,
            'created_by_faculty_id' => $turingFaculty->id
        ]);

        $dbmsExam = Examination::create([
            'title' => 'Midterm: Database Relations and SQL Queries',
            'description' => 'Comprehensive midterm exam covering normalization rules, relational algebra, and SQL scripts.',
            'subject_id' => $dbmsSub->id,
            'class_id' => $cseClassA->id,
            'duration_minutes' => 60,
            'total_marks' => 20,
            'exam_date' => Carbon::now()->addDays(2),
            'is_published' => true,
            'created_by_faculty_id' => $hopperFaculty->id
        ]);

        // 14. Seed Exam Questions
        $q1 = ExamQuestion::create([
            'examination_id' => $networksExam->id,
            'question_text' => 'Which OSI layer is responsible for translating data formats, encrypting data, and compressing data?',
            'option_a' => 'Application Layer',
            'option_b' => 'Presentation Layer',
            'option_c' => 'Session Layer',
            'option_d' => 'Transport Layer',
            'correct_option' => 'B',
            'points' => 1
        ]);

        $q2 = ExamQuestion::create([
            'examination_id' => $networksExam->id,
            'question_text' => 'What is the primary role of the Network Layer in the OSI reference model?',
            'option_a' => 'Bit-level transmission over wires',
            'option_b' => 'Error checking and node-to-node frame delivery',
            'option_c' => 'Routing packets across networks using logical addresses',
            'option_d' => 'End-to-end process communication and port mapping',
            'correct_option' => 'C',
            'points' => 1
        ]);

        $q3 = ExamQuestion::create([
            'examination_id' => $networksExam->id,
            'question_text' => 'Which of the following protocols operates at the Transport Layer of the TCP/IP stack?',
            'option_a' => 'IP (Internet Protocol)',
            'option_b' => 'SMTP (Simple Mail Transfer Protocol)',
            'option_c' => 'TCP (Transmission Control Protocol)',
            'option_d' => 'ARP (Address Resolution Protocol)',
            'correct_option' => 'C',
            'points' => 1
        ]);

        $q4 = ExamQuestion::create([
            'examination_id' => $networksExam->id,
            'question_text' => 'In which OSI layer do you find routers working to direct traffic?',
            'option_a' => 'Physical Layer',
            'option_b' => 'Data Link Layer',
            'option_c' => 'Network Layer',
            'option_d' => 'Application Layer',
            'correct_option' => 'C',
            'points' => 1
        ]);

        $q5 = ExamQuestion::create([
            'examination_id' => $networksExam->id,
            'question_text' => 'Which layer of the OSI model ensures reliable, in-sequence packet delivery with flow control and error recovery?',
            'option_a' => 'Network Layer',
            'option_b' => 'Transport Layer',
            'option_c' => 'Data Link Layer',
            'option_d' => 'Physical Layer',
            'correct_option' => 'B',
            'points' => 1
        ]);

        // 15. Seed Exam Results
        // Jane Smith attempted the Networks Quiz and scored 4/5
        ExamResult::create([
            'examination_id' => $networksExam->id,
            'student_id' => $janeStudent->id,
            'total_questions' => 5,
            'correct_answers' => 4,
            'wrong_answers' => 1,
            'marks_obtained' => 4,
            'passed' => true,
            'answers_json' => [
                $q1->id => 'B', // Correct
                $q2->id => 'C', // Correct
                $q3->id => 'C', // Correct
                $q4->id => 'A', // Incorrect (Correct is C)
                $q5->id => 'B', // Correct
            ]
        ]);

        // 16. Seed Complaints
        Complaint::create([
            'student_id' => $johnStudent->id,
            'title' => 'Unstable Wi-Fi Connectivity in CSE Block',
            'description' => 'The Wi-Fi network (College-Wifi-CS) keeps disconnecting every 5 minutes in CSE Seminar Hall and laboratory rooms. It makes online programming practice very difficult. Kindly fix this node issue.',
            'category' => 'Facilities',
            'status' => 'pending',
            'ai_comment' => 'AI Automated Categorization: Facilities. Recommendation: High priority issue, forward to Network Administrators.'
        ]);

        Complaint::create([
            'student_id' => $janeStudent->id,
            'title' => 'Incorrect Library Late Fee Charges',
            'description' => 'I returned the Database Systems textbook on Monday, which was before the scheduled return date. However, my account is showing a $15 late fee. Please clear this bill.',
            'category' => 'Fees',
            'status' => 'in_progress',
            'ai_comment' => 'AI Automated Categorization: Fees. Recommendation: Medium priority, verify textbook return logs from Library system.'
        ]);

        // 17. Seed Messages
        Message::create([
            'sender_id' => $johnUser->id,
            'receiver_id' => $turingUser->id,
            'subject' => 'Doubt in BCNF Decomposition',
            'body' => 'Dear Professor Turing, I am having trouble understanding how BCNF differs from 3NF when decomposing a relation. Could you spare some time tomorrow after lectures to explain this? Thank you.',
            'read_at' => null
        ]);

        Message::create([
            'sender_id' => $turingUser->id,
            'receiver_id' => $johnUser->id,
            'subject' => 'Re: Doubt in BCNF Decomposition',
            'body' => 'Hello John, yes, certainly. I am available in my cabin (CS-102) between 3:30 PM and 4:30 PM tomorrow. Please bring your notebooks and relational diagrams.',
            'read_at' => Carbon::now()->subHours(10)
        ]);

        // 18. Seed Leave Requests
        LeaveRequest::create([
            'user_id' => $johnUser->id,
            'leave_type' => 'sick',
            'start_date' => Carbon::now()->addDays(5)->toDateString(),
            'end_date' => Carbon::now()->addDays(7)->toDateString(),
            'reason' => 'Need leave for wisdom tooth extraction surgery and recovery.',
            'status' => 'pending'
        ]);

        LeaveRequest::create([
            'user_id' => $turingUser->id,
            'leave_type' => 'casual',
            'start_date' => Carbon::now()->addDays(12)->toDateString(),
            'end_date' => Carbon::now()->addDays(14)->toDateString(),
            'reason' => 'Attending national AI & cryptography symposium.',
            'status' => 'approved',
            'actioned_by' => $adminUser->id
        ]);

        // 19. Seed Timetable Slots
        Timetable::create([
            'class_id' => $cseClassA->id,
            'subject_id' => $networksSub->id,
            'faculty_id' => $turingFaculty->id,
            'day_of_week' => 'Monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'CSE Room 102'
        ]);
        Timetable::create([
            'class_id' => $cseClassB->id,
            'subject_id' => $networksSub->id,
            'faculty_id' => $turingFaculty->id,
            'day_of_week' => 'Monday',
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'room' => 'CSE Room 103'
        ]);
        Timetable::create([
            'class_id' => $cseClassA->id,
            'subject_id' => $networksSub->id,
            'faculty_id' => $turingFaculty->id,
            'day_of_week' => 'Wednesday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'CSE Room 102'
        ]);
        Timetable::create([
            'class_id' => $cseClassB->id,
            'subject_id' => $networksSub->id,
            'faculty_id' => $turingFaculty->id,
            'day_of_week' => 'Wednesday',
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'room' => 'CSE Room 103'
        ]);
        Timetable::create([
            'class_id' => $cseClassA->id,
            'subject_id' => $dbmsSub->id,
            'faculty_id' => $turingFaculty->id,
            'day_of_week' => 'Friday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'CSE Room 102'
        ]);

        Timetable::create([
            'class_id' => $cseClassA->id,
            'subject_id' => $dbmsSub->id,
            'faculty_id' => $hopperFaculty->id,
            'day_of_week' => 'Tuesday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'CSE Room 102'
        ]);
        Timetable::create([
            'class_id' => $cseClassA->id,
            'subject_id' => $dbmsSub->id,
            'faculty_id' => $hopperFaculty->id,
            'day_of_week' => 'Thursday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'CSE Room 102'
        ]);
        Timetable::create([
            'class_id' => $cseClassB->id,
            'subject_id' => $dbmsSub->id,
            'faculty_id' => $hopperFaculty->id,
            'day_of_week' => 'Thursday',
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'room' => 'CSE Room 103'
        ]);
    }
}
