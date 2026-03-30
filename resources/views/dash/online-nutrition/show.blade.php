@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Assessment Details</h3>
    <p><strong>Client Name:</strong> {{ $assessment->user->name }}</p>
    <p><strong>Weight:</strong> {{ $assessment->weight }} kg</p>
    <p><strong>Height:</strong> {{ $assessment->height }} cm</p>
    <p><strong>BMI:</strong> {{ $assessment->bmi }}</p>
    <p><strong>Fat Percentage:</strong> {{ $assessment->fat_percentage }}%</p>
    <p><strong>Muscle Mass:</strong> {{ $assessment->muscle_mass }} kg</p>
    <p><strong>Water Percentage:</strong> {{ $assessment->water_percentage }}%</p>
    <form action="{{ route('nutrition.updateStatus', $assessment->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="approved" {{ $assessment->status == 'approved' ? 'selected' : '' }}>Approve</option>
                <option value="rejected" {{ $assessment->status == 'rejected' ? 'selected' : '' }}>Reject</option>
            </select>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" class="form-control">{{ $assessment->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
