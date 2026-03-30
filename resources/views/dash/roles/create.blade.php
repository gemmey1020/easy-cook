@extends('layouts.dashboard-admin')

@section('content')
<div class="container">
    <h3>Create New Role</h3>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter role name" required>
        </div>
        <div class="form-group">
            <label for="description">description :</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $role->description ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
