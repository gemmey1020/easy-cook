@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Assessment Details</h3>
    <p><strong>Client Name:</strong> {{ $assessment->user->first_name }} {{ $assessment->user->last_name }}</p>
    <p><strong>Client Email:</strong> {{ $assessment->user->email }}</p>
    <p><strong>Client Phone:</strong> <a href="tel:{{ $assessment->user->phone }}">{{ $assessment->user->phone }}</a></p>
    <p><strong>Gender:</strong> {{ $assessment->gender }}</p>
    <p><strong>Birth Date:</strong> {{ Carbon\Carbon::parse($assessment->birth_date)->format('d-m-Y')}}</p>
    <p><strong>Age:</strong> {{ $assessment->age }}</p>


    <p><strong>Weight:</strong> {{ $assessment->weight }} kg</p>
    <p><strong>Height:</strong> {{ $assessment->height }} cm</p>
    <p><strong>Fat Percentage:</strong> {{ $assessment->fat_percentage }}%</p>
    <p><strong>Carbohydrates:</strong> {{ $assessment->carbohydrates }}</p>
    <p><strong>Calories Needed:</strong> {{ $assessment->calories_needed }}</p>
    <p><strong>Activity Level:</strong> {{ $assessment->activity_level }}</p>
    <p><strong>Goal:</strong> {{ $assessment->goal }}</p>
    <p><strong>Consultation Type:</strong> {{ str_replace('_', ' ', strtoUpper($assessment->consultation_type))}}</p>
    <p><strong>Status:</strong> {{ ucfirst($assessment->status) }}</p>
    <form action="{{ route('assessments.updateStatus', $assessment->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="approved" {{ $assessment->status == 'approved' ? 'selected' : '' }}>Approve</option>
                <option value="rejected" {{ $assessment->status == 'rejected' ? 'selected' : '' }}>Reject</option>
            </select>
        </div>
        <div class="form-group">
            <label for="notes">Previous Notes</label>
            @forelse($assessment->notes ?? [] as $note)
                <div class="p-2 mb-2 border rounded bg-light">
                    <small class="text-muted">{{ $note->created_at->format('d-m-Y H:i') }}</small>
                    <p class="mb-0">{{ $note->note }}</p>
                </div>
            @empty
                <p>No notes yet.</p>
            @endforelse
        </div>

        <div class="form-group">
            <label for="notes">Add New Note</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Write a new note..."></textarea>
        </div>

        <button type="submit" class="btn btn-success mb-5">Update</button>
    </form>
</div>
@endsection
