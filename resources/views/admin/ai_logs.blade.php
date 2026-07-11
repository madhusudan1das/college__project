@extends('layouts.app')

@section('title', 'AI Engine Audit Logs')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-journal-code text-indigo"></i> AI Engine Generation Audit Trail</h4>
    
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Feature Called</th>
                    <th>Prompt Spec</th>
                    <th>AI Response Output</th>
                    <th>Approx Tokens</th>
                    <th>Date Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            @if($log->user)
                                <div class="fw-bold small">{{ $log->user->name }}</div>
                                <span class="badge bg-secondary-subtle text-secondary fs-9">{{ $log->user->role->display_name ?? '' }}</span>
                            @else
                                <span class="text-muted small">Guest Session</span>
                            @endif
                        </td>
                        <td><span class="badge bg-indigo-subtle text-primary border border-indigo-subtle px-2.5 py-1.5 small">{{ ucfirst(str_replace('_', ' ', $log->feature_used)) }}</span></td>
                        <td><p class="mb-0 text-muted fs-8 text-truncate" style="max-width: 150px;" title="{{ $log->prompt }}">{{ $log->prompt }}</p></td>
                        <td><p class="mb-0 text-muted fs-8 text-truncate" style="max-width: 250px;" title="{{ $log->response }}">{{ $log->response }}</p></td>
                        <td><small class="fw-bold text-dark">{{ $log->tokens_used }}</small></td>
                        <td><small class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">No AI engine invocations are currently logged. Try triggering chatbot or notices summaries.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
