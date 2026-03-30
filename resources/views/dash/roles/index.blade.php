@extends('layouts.dashboard-admin')
@section('title', 'Dashboard Admin')

@section('preload')
@include("components.dashboard-admin.main.preload")
@endsection

@section('sidebar')
@include("components.dashboard-admin.main.sidebar")
@endsection

@section('header')
@include("components.dashboard-admin.main.header")
@endsection



@section('content')
<div class="container">
    <h3>Manage Roles</h3>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $role->name }}</span>
                    </td>
                    <td>{{ $role->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
