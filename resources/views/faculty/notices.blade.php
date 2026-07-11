@extends('layouts.app')

@section('title', 'Academic Notices')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-megaphone text-primary"></i> Academic Bulletins & Notices</h4>
    
    <div class="d-flex flex-column gap-3">
        @forelse($notices as $notice)
            <div class="p-3 border rounded-3 bg-light bg-opacity-70">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 small">{{ $notice->created_at->format('M d, Y H:i') }}</span>
                    <small class="text-muted">Published by Admin</small>
                </div>
                <h5 class="fw-bold text-dark mb-2">{{ $notice->title }}</h5>
                <p class="small text-muted mb-3">{{ $notice->content }}</p>
                
                @if($notice->summary)
                    <div class="p-2 bg-info bg-opacity-10 border-start border-info border-3 rounded fs-8 text-dark">
                        <strong><i class="bi bi-robot"></i> AI Bullet Point Summary:</strong> {{ $notice->summary }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-muted py-4 text-center">No college notice bulletins are pinned at the moment.</p>
        @endforelse
    </div>
</div>
@endsection
