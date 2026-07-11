@extends('layouts.app')

@section('title', 'Contact Faculty')

@section('content')
<div class="row">
    <!-- Compose Message Form -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-chat-left-dots text-primary"></i> Compose Message</h5>
            <form action="{{ route('student.messages.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Professor</label>
                    <select name="receiver_id" class="form-select" required>
                        <option value="">Select Instructor</option>
                        @foreach($faculties as $fac)
                            <option value="{{ $fac->user_id }}">{{ $fac->user->name }} ({{ $fac->designation }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="e.g. DBMS Assignment Clarification" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Message Details</label>
                    <textarea name="body" class="form-control" rows="5" placeholder="Write query details here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2">Dispatch Message</button>
            </form>
        </div>
    </div>

    <!-- Message Lists: Tabulated Inbox/Sent -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <!-- Tabs -->
            <ul class="nav nav-pills mb-4 gap-2" id="msgTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4" id="inbox-tab" data-bs-toggle="pill" data-bs-target="#inbox" type="button" role="tab">Inbox Messages</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="sent-tab" data-bs-toggle="pill" data-bs-target="#sent" type="button" role="tab">Sent Messages</button>
                </li>
            </ul>

            <div class="tab-content" id="msgTabContent">
                <!-- Inbox -->
                <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                    <div class="d-flex flex-column gap-3">
                        @forelse($messages as $msg)
                            <div class="p-3 border rounded-3 bg-light bg-opacity-70">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small">{{ $msg->subject }}</strong>
                                    <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="d-block text-primary fw-semibold fs-9 mb-2">From Professor: {{ $msg->sender->name }}</span>
                                <p class="small text-muted mb-0 lh-relaxed p-2 bg-white rounded border">{{ $msg->body }}</p>
                            </div>
                        @empty
                            <p class="text-muted small py-4 text-center">Your query inbox is currently empty.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Sent -->
                <div class="tab-pane fade" id="sent" role="tabpanel">
                    <div class="d-flex flex-column gap-3">
                        @forelse($sentMessages as $msg)
                            <div class="p-3 border rounded-3 bg-light bg-opacity-70">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small">{{ $msg->subject }}</strong>
                                    <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="d-block text-success fw-semibold fs-9 mb-2">Sent to: {{ $msg->receiver->name ?? '' }}</span>
                                <p class="small text-muted mb-0 lh-relaxed p-2 bg-white rounded border">{{ $msg->body }}</p>
                            </div>
                        @empty
                            <p class="text-muted small py-4 text-center">No outbound messages recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
