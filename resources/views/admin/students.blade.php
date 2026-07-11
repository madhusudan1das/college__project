@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <h4 class="fw-bold mb-0">Student Information Register</h4>
        <button class="btn btn-accent rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="bi bi-person-plus-fill me-1"></i> Enroll New Student
        </button>
    </div>

    <!-- Filter and Search controls -->
    <form action="{{ route('admin.students') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, roll no, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">Filter Registers</button>
            </div>
        </div>
    </form>

    <!-- Students Table -->
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Class</th>
                    <th>Admission Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr id="row-student-{{ $student->id }}">
                        <td><span class="badge bg-secondary-subtle text-secondary fw-semibold">{{ $student->roll_no }}</span></td>
                        <td>
                            <div class="fw-bold">{{ $student->user->name ?? 'Unknown' }}</div>
                            <small class="text-muted">{{ $student->gender }}</small>
                        </td>
                        <td>{{ $student->user->email ?? '' }}</td>
                        <td>{{ $student->department->code ?? '' }}</td>
                        <td>{{ $student->class->name ?? '' }}</td>
                        <td><small>{{ $student->created_at->format('M d, Y') }}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1 rounded-circle" onclick="openEditModal({{ json_encode($student) }})" title="Edit Details">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-circle" onclick="deleteStudent({{ $student->id }})" title="Delete Student">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No student profiles match these filter definitions.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $students->appends(request()->query())->links() }}
    </div>
</div>

<!-- =========================================================================
     MODAL: Add Student
     ========================================================================= -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form id="add-student-form">
                <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Enroll Student Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-danger d-none" id="add-error-box"></div>
                    
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Login Credentials</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="john@college.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Login Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Contact Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+1234567890">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Academic Parameters</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Semester Section Class</label>
                            <select name="class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Roll Number</label>
                            <input type="text" name="roll_no" class="form-control" placeholder="CSE-2026-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Admission Number</label>
                            <input type="text" name="admission_no" class="form-control" placeholder="ADM-10001" required>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Profile Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Home Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Home address details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL: Edit Student
     ========================================================================= -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form id="edit-student-form">
                <input type="hidden" id="edit-student-id">
                <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Student Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-danger d-none" id="edit-error-box"></div>
                    
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Login Credentials</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" id="edit-email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Login Password (Leave empty to keep current)</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Contact Number</label>
                            <input type="text" name="phone" id="edit-phone" class="form-control">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Academic Parameters</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department</label>
                            <select name="department_id" id="edit-department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Semester Section Class</label>
                            <select name="class_id" id="edit-class_id" class="form-select" required>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Roll Number</label>
                            <input type="text" name="roll_no" id="edit-roll_no" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Admission Number</label>
                            <input type="text" name="admission_no" id="edit-admission_no" class="form-control" required>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Profile Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Date of Birth</label>
                            <input type="date" name="dob" id="edit-dob" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Gender</label>
                            <select name="gender" id="edit-gender" class="form-select" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Home Address</label>
                            <textarea name="address" id="edit-address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // AJAX: Add Student Form Submission
    $("#add-student-form").submit(function(e) {
        e.preventDefault();
        $("#add-error-box").addClass("d-none").html('');
        
        $.ajax({
            url: "{{ route('admin.students.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    $("#add-error-box").removeClass("d-none").html(res.errors.join('<br>'));
                }
            },
            error: function() {
                $("#add-error-box").removeClass("d-none").html("An internal system error occurred. Please check values.");
            }
        });
    });

    // Helper: Fill edit modal parameters
    function openEditModal(student) {
        $("#edit-student-id").val(student.id);
        $("#edit-name").val(student.user ? student.user.name : '');
        $("#edit-email").val(student.user ? student.user.email : '');
        $("#edit-phone").val(student.user ? student.user.phone : '');
        $("#edit-department_id").val(student.department_id);
        $("#edit-class_id").val(student.class_id);
        $("#edit-roll_no").val(student.roll_no);
        $("#edit-admission_no").val(student.admission_no);
        $("#edit-dob").val(student.dob);
        $("#edit-gender").val(student.gender);
        $("#edit-address").val(student.address);

        $("#edit-error-box").addClass("d-none").html('');
        $("#editStudentModal").modal('show');
    }

    // AJAX: Edit Student Form Submission
    $("#edit-student-form").submit(function(e) {
        e.preventDefault();
        $("#edit-error-box").addClass("d-none").html('');
        const id = $("#edit-student-id").val();

        $.ajax({
            url: `/admin/students/${id}`,
            type: "PUT",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    $("#edit-error-box").removeClass("d-none").html(res.errors.join('<br>'));
                }
            },
            error: function() {
                $("#edit-error-box").removeClass("d-none").html("An internal error occurred while updating.");
            }
        });
    });

    // AJAX: Delete Student
    function deleteStudent(id) {
        if(confirm("Are you absolutely sure you want to delete this student and their login credentials?")) {
            $.ajax({
                url: `/admin/students/${id}`,
                type: "DELETE",
                success: function(res) {
                    if(res.success) {
                        alert(res.message);
                        $(`#row-student-${id}`).remove();
                    } else {
                        alert("Delete action failed.");
                    }
                },
                error: function() {
                    alert("Network connection error.");
                }
            });
        }
    }
</script>
@endsection
