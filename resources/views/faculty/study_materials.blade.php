@extends('layouts.app')

@section('title', 'Manage Study Materials')

@section('content')
<div class="row">
    <!-- Upload Resource Form -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-arrow-up text-primary"></i> Upload Study Resource</h5>
            
            <form action="{{ route('faculty.study-materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Resource Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Lecture 2: Routing Algorithms" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Target Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Choose Class</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Choose Subject</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Short Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Brief outline..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select File Attachment</label>
                    <input type="file" name="file" class="form-control" required>
                    <small class="text-muted text-wrap d-block mt-1">Supports PDF, DOCX, PPTX, or ZIP (Max: 10MB).</small>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2 mt-2">Publish Study Material</button>
            </form>
        </div>
    </div>

    <!-- Active Uploads List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Uploaded Study Resources</h4>
            
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Resource</th>
                            <th>Class Target</th>
                            <th>Subject</th>
                            <th>Uploaded On</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $mat)
                            <tr>
                                <td>
                                    <div class="fw-bold small">{{ $mat->title }}</div>
                                    <small class="text-muted">{{ Str::limit($mat->description, 50) }}</small>
                                </td>
                                <td><span class="small">{{ $mat->class->name ?? '' }}</span></td>
                                <td><span class="badge bg-secondary-subtle text-secondary small">{{ $mat->subject->code ?? '' }}</span></td>
                                <td><small>{{ $mat->created_at->format('M d, Y') }}</small></td>
                                <td class="text-end">
                                    <form action="{{ route('faculty.study-materials.delete', $mat->id) }}" method="POST" onsubmit="return confirm('Permanently delete this attachment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">No lecture files have been uploaded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
