@extends('layouts.master')

@section('title', 'BRNC')

@push('styles')
<!-- Bootstrap 4 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>BRNC Dashboard</h1>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="text-center mt-4">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Table -->
    <div class="row mt-4" id="table-container" style="display: none;">
        <div class="col-12">
            <table id="clicks-tables" class="table table-bordered table-striped">
                <thead class="table-backgroun">
                    <tr>
                        <th>#</th>
                        <th>Platform</th>
                        <th>IP 1</th>
                        <th>IP 2</th>
                        <th>Click Type</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Region</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['platform'] }}</td>
                        <td>{{ $item['ip_1'] }}</td>
                        <td>{{ $item['ip_2'] }}</td>
                        <td>{{ $item['click_type'] }}</td>
                        <td>{{ $item['country'] }}</td>
                        <td>{{ $item['city'] }}</td>
                        <td>{{ $item['region'] }}</td>
                        <td>{{ $item['created_at'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- Activate DataTables -->
<script>
    $(document).ready(function() {
        setTimeout(() => {
            $('#loader').hide();
            $('#table-container').show();

            $('#clicks-tables').DataTable({
                pageLength: 10,
                lengthChange: false,
                language: {
                    search: "🔍 Search:",
                    paginate: {
                        previous: "⬅️",
                        next: "➡️"
                    }
                }
            });
        }, 1000);
    });
</script>
@endpush