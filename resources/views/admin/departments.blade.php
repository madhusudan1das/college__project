@extends('layouts.app')

@section('title', 'Manage Departments')

@section('content')
<div class="row">
    <!-- Inline Create Form -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle text-primary"></i> Create Department</h5>
            <form action="{{ route('admin.departments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Department Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Mechanical Engineering" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Department Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. ME" required>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2 mt-2">Publish Department</button>
            </form>
        </div>
    </div>

    <!-- Departments List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Academic Departments Register</h4>
            
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Department Name</th>
                            <th>Students Enrolled</th>
                            <th>Faculty Members</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $dept)
                            <tr>
                                <td><span class="badge bg-primary text-white fw-bold">{{ $dept->code }}</span></td>
                                <td><strong>{{ $dept->name }}</strong></td>
                                <td>{{ $dept->students_count }} students</td>
                                <td>{{ $dept->faculty_count }} members</td>
                                <td class="text-end">
                                    <!-- Edit Trigger Button -->
                                    <button class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editDeptModal-{{ $dept->id }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.departments.delete', $dept->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deleting this department will delete all associated subjects, classes, students and faculty records. Proceed?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- MODAL: Edit Department -->
                            <div class="modal fade" id="editDeptModal-{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <form action="{{ route('admin.departments.update', $dept->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square"></i> Modify Department</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Department Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $dept->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Department Code</label>
                                                    <input type="text" name="code" class="form-control" value="{{ $dept->code }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer p-3 bg-light border-top">
                                                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No departments created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
