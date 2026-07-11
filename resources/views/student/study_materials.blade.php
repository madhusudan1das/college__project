@extends('layouts.app')

@section('title', 'Study Notes & Materials')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-file-earmark-zip-fill text-primary"></i> Academic Study Notes Repository</h4>
    
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Material Name</th>
                    <th>Subject</th>
                    <th>Uploaded By</th>
                    <th>Uploaded Date</th>
                    <th class="text-end">Download</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $mat)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $mat->title }}</div>
                            <small class="text-muted">{{ $mat->description }}</small>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary fw-semibold">{{ $mat->subject->code }}</span></td>
                        <td><small>{{ $mat->faculty->user->name ?? 'System' }}</small></td>
                        <td><small>{{ $mat->created_at->format('M d, Y') }}</small></td>
                        <td class="text-end">
                            <a href="{{ asset('storage/' . $mat->file_path) }}" class="btn btn-sm btn-accent rounded-pill px-3" target="_blank">
                                <i class="bi bi-cloud-arrow-down-fill"></i> Download File
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No study materials are uploaded for your class level target.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
