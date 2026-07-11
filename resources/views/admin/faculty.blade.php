@extends('layouts.app')

@section('title', 'Manage Faculty')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <h4 class="fw-bold mb-0">Faculty Information Register</h4>
        <button class="btn btn-accent rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
            <i class="bi bi-person-plus-fill me-1"></i> Add Faculty Member
        </button>
    </div>

    <!-- Search Controls -->
    <form action="{{ route('admin.faculty') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, designation..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">Search Faculty</button>
            </div>
        </div>
    </form>

    <!-- Faculty Table -->
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Subjects Taught</th>
                    <th>Joining Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faculties as $fac)
                    <tr id="row-faculty-{{ $fac->id }}">
                        <td>
                            <div class="fw-bold">{{ $fac->user->name ?? 'Unknown' }}</div>
                            <small class="text-muted">{{ $fac->qualification }}</small>
                        </td>
                        <td>{{ $fac->user->email ?? '' }}</td>
                        <td>{{ $fac->department->code ?? '' }}</td>
                        <td><span class="badge bg-primary-subtle text-primary fw-semibold">{{ $fac->designation }}</span></td>
                        <td>
                            @forelse($fac->subjects as $sub)
                                <span class="badge bg-secondary-subtle text-secondary small mb-1">{{ $sub->code }}</span>
                            @empty
                                <span class="text-muted small">None Assigned</span>
                            @endforelse
                        </td>
                        <td><small>{{ Carbon\Carbon::parse($fac->joining_date)->format('M d, Y') }}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1 rounded-circle" onclick="openEditModal({{ json_encode($fac) }}, {{ json_encode($fac->subjects->pluck('id')) }})" title="Edit Details">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-circle" onclick="deleteFaculty({{ $fac->id }})" title="Delete Faculty">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No faculty members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $faculties->appends(request()->query())->links() }}
    </div>
</div>

<!-- =========================================================================
     MODAL: Add Faculty
     ========================================================================= -->
<div class="modal fade" id="addFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form id="add-faculty-form">
                <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Enroll Faculty Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-danger d-none" id="add-error-box"></div>
                    
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Login Credentials</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Alan Turing" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="turing@college.com" required>
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

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Academic Profile</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Professor" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Qualifications</label>
                            <input type="text" name="qualification" class="form-control" placeholder="e.g. Ph.D. in CS" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Date of Joining</label>
                            <input type="date" name="joining_date" class="form-control" required>
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
                            <label class="form-label small fw-bold">Office Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Room no, block, etc..."></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Assign Subject Classes</h6>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label small text-muted mb-2">Check all subjects this member is teaching:</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($subjects as $sub)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="subjects[]" value="{{ $sub->id }}" id="chk-add-sub-{{ $sub->id }}">
                                        <label class="form-check-label small" for="chk-add-sub-{{ $sub->id }}">
                                            {{ $sub->name }} ({{ $sub->code }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
     MODAL: Edit Faculty
     ========================================================================= -->
<div class="modal fade" id="editFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <form id="edit-faculty-form">
                <input type="hidden" id="edit-faculty-id">
                <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Faculty Record</h5>
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

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Academic Profile</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Department</label>
                            <select name="department_id" id="edit-department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Designation</label>
                            <input type="text" name="designation" id="edit-designation" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Qualifications</label>
                            <input type="text" name="qualification" id="edit-qualification" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Date of Joining</label>
                            <input type="date" name="joining_date" id="edit-joining_date" class="form-control" required>
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
                            <label class="form-label small fw-bold">Office Address</label>
                            <textarea name="address" id="edit-address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Assign Subject Classes</h6>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label small text-muted mb-2">Check all subjects this member is teaching:</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($subjects as $sub)
                                    <div class="form-check">
                                        <input class="form-check-input chk-edit-sub" type="checkbox" name="subjects[]" value="{{ $sub->id }}" id="chk-edit-sub-{{ $sub->id }}">
                                        <label class="form-check-label small" for="chk-edit-sub-{{ $sub->id }}">
                                            {{ $sub->name }} ({{ $sub->code }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
    // AJAX: Add Faculty Form Submission
    $("#add-faculty-form").submit(function(e) {
        e.preventDefault();
        $("#add-error-box").addClass("d-none").html('');
        
        $.ajax({
            url: "{{ route('admin.faculty.store') }}",
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
                $("#add-error-box").removeClass("d-none").html("An internal system error occurred. Please verify values.");
            }
        });
    });

    // Helper: Fill edit modal parameters
    function openEditModal(faculty, subjects) {
        $("#edit-faculty-id").val(faculty.id);
        $("#edit-name").val(faculty.user ? faculty.user.name : '');
        $("#edit-email").val(faculty.user ? faculty.user.email : '');
        $("#edit-phone").val(faculty.user ? faculty.user.phone : '');
        $("#edit-department_id").val(faculty.department_id);
        $("#edit-designation").val(faculty.designation);
        $("#edit-qualification").val(faculty.qualification);
        $("#edit-joining_date").val(faculty.joining_date);
        $("#edit-gender").val(faculty.gender);
        $("#edit-address").val(faculty.address);

        // Reset check boxes
        $(".chk-edit-sub").prop("checked", false);
        // Bind assigned check boxes
        if(subjects && subjects.length > 0) {
            subjects.forEach(subId => {
                $(`#chk-edit-sub-${subId}`).prop("checked", true);
            });
        }

        $("#edit-error-box").addClass("d-none").html('');
        $("#editFacultyModal").modal('show');
    }

    // AJAX: Edit Faculty Form Submission
    $("#edit-faculty-form").submit(function(e) {
        e.preventDefault();
        $("#edit-error-box").addClass("d-none").html('');
        const id = $("#edit-faculty-id").val();

        $.ajax({
            url: `/admin/faculty/${id}`,
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

    // AJAX: Delete Faculty
    function deleteFaculty(id) {
        if(confirm("Are you absolutely sure you want to delete this faculty member and their login credentials?")) {
            $.ajax({
                url: `/admin/faculty/${id}`,
                type: "DELETE",
                success: function(res) {
                    if(res.success) {
                        alert(res.message);
                        $(`#row-faculty-${id}`).remove();
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
