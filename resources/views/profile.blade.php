@extends('layouts.app')

@section('title', 'My Profile Settings')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- Profile summary card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center glass-card">
            <div class="position-relative d-inline-block mx-auto mb-3">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Avatar" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="avatar-circle rounded-circle mx-auto d-flex align-items-center justify-content-center border" style="width: 120px; height: 120px; font-size: 2.5rem; background: var(--accent-gradient);">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            
            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
            <span class="badge bg-primary-subtle text-primary border px-3 py-2 rounded-pill mb-3">
                {{ strtoupper($user->role->display_name ?? $user->role->name) }}
            </span>
            
            <div class="text-start mt-4 pt-3 border-top">
                <div class="mb-3">
                    <span class="text-muted d-block small">Email Address</span>
                    <strong class="small">{{ $user->email }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted d-block small">Mobile Phone</span>
                    <strong class="small">{{ $user->phone ?? 'Not Provided' }}</strong>
                </div>
                @if($user->isStudent() && $user->student)
                    <div class="mb-3">
                        <span class="text-muted d-block small">Roll / Admission No</span>
                        <strong class="small">{{ $user->student->roll_no }} / {{ $user->student->admission_no }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Class & Dept</span>
                        <strong class="small">{{ $user->student->class->name ?? '' }} ({{ $user->student->department->code ?? '' }})</strong>
                    </div>
                @elseif($user->isFaculty() && $user->faculty)
                    <div class="mb-3">
                        <span class="text-muted d-block small">Designation</span>
                        <strong class="small">{{ $user->faculty->designation }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Qualifications</span>
                        <strong class="small">{{ $user->faculty->qualification }}</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Settings Form Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 glass-card">
            <h4 class="fw-bold mb-4">Edit Profile Settings</h4>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-on data" id="profile-form">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Contact Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    
                    @php
                        $addressVal = '';
                        if($user->isStudent() && $user->student) {
                            $addressVal = $user->student->address;
                        } elseif($user->isFaculty() && $user->faculty) {
                            $addressVal = $user->faculty->address;
                        }
                    @endphp
                    @if($user->isStudent() || $user->isFaculty())
                        <div class="col-12">
                            <label class="form-label small fw-bold">Address Details</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $addressVal) }}</textarea>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label small fw-bold">Change Profile Avatar</label>
                        <input type="file" name="profile_picture" class="form-control">
                        <small class="text-muted">Supports JPG, PNG, GIF files up to 2MB.</small>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 border-top pt-4">Change Password</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="••••••••">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-accent px-4 py-2">Save Profile Updates</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Enforce multipart form posting for avatars uploading
        $("#profile-form").attr("enctype", "multipart/form-data");
    });
</script>
@endsection
