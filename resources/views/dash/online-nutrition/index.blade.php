@extends('layouts.master')


@section('content')
<div class="container">
    <h3>Pending Assessments</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Client Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assessments as $assessment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $assessment->user->first_name . ' ' . $assessment->user->last_name }}</td>
                    <td>{{ ucfirst($assessment->status) }}</td>
                    <td>
                        <a href="{{ route('nutrition.show', $assessment->id) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
