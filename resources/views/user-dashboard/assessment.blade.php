@extends('layouts.user-dashboard')

@section('title', 'My Assessment')

@section('user-dashboard-content')


    <div class="tab-pane h-100" id="assessment">
        <div class="">
            <h2 class="mb-4">Welcome, {{ $user->first_name }} 👋</h2>

            <h4>Assessments</h4>
            @forelse ($user->assessments as $assessment)
                <div class="card mb-3">
                    <div class="card-header">
                        Assessment ID: {{ $assessment->id }} | Status: <span class="badge bg-{{ $assessment->status == 'approved' ? 'success' : 'danger' }}">{{ ucfirst($assessment->status) }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>Weight:</strong> {{ $assessment->weight }} kg</p>
                        <p><strong>Height:</strong> {{ $assessment->height }} cm</p>
                        <p><strong>Fat %:</strong> {{ $assessment->fat_percentage }}</p>
                        <p><strong>Muscle Mass:</strong> {{ $assessment->muscle_mass ?? '-' }}</p>
                        <p><strong>Consultation Type:</strong> {{ str_replace('_', ' ', $assessment->consultation_type) ?? '-' }}</p>
                        <p><strong>Gender:</strong> {{ $assessment->gender }}</p>
                        <p><strong>Age:</strong> {{ $assessment->age }}</p>
                        <p><strong>Goal:</strong> {{ $assessment->goal }}</p>
                        <p><strong>Activity Level:</strong> {{ $assessment->activity_level }}</p>
                        <p><strong>Calories Needed:</strong> {{ $assessment->calories_needed }}</p>

                        <h6 class="mt-3">Notes</h6>
                        @if($assessment->notes && $assessment->notes->count())
                            <ul>
                                @foreach($assessment->notes as $note)
                                    <li>
                                        {{ $note->note }} <small class="text-muted">({{ \Carbon\Carbon::parse($note->created_at)->format('Y-m-d H:i') }})</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No notes available.</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted">No assessments available.</p>
            @endforelse
        </div>
    </div>



@endsection

@push('scripts')
    <script src="{{ asset('assets/js/dashboard/profile.js') }}"></script>
@endpush
