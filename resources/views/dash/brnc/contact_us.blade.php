@extends('layouts.master')

@section('title', 'BRNC')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>BRNC Dashboard</h1>
            </div>
        </div>
        <div class="row mt-4">
<!-- Loader -->
<div id="loader" class="text-center mt-4">
  <div class="spinner-border text-success" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>

<!-- Table -->
<div class="row mt-4" id="table-container" style="display: none;">
    <div class="col-12">
        <table id="leads-table" class="table table-bordered table-striped">
            <thead class="table-backgroun text-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Platform</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Region</th>
                    <th>Created At</th>
                    <th>Service Message</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['mobile'] }}</td>
                        <td>{{ $item['email'] }}</td>
                        <td>{{ $item['platform_info'] }}</td>
                        <td>{{ $item['ip_country'] }}</td>
                        <td>{{ $item['ip_city'] }}</td>
                        <td>{{ $item['ip_region'] }}</td>
                        <td>{{ $item['created_at'] }}</td>
                     <td>
    {{ Str::limit(strip_tags($item['services']), 100, '...') }}
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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

    <script>
        $(document).ready(function () {
            setTimeout(() => {
                $('#loader').hide();
                $('#table-container').show();

                $('#leads-table').DataTable({
                    pageLength: 5,
                    lengthChange: false,
                    language: {
                        search: "🔍 Search:",
                        paginate: {
                            previous: "⬅️",
                            next: "➡️"
                        }
                    },
                    columnDefs: [
                        {
                            targets: 4, // services column
                            render: $.fn.dataTable.render.ellipsis(100)
                        }
                    ]
                });
            }, 1000);
        });

        // Optional: ellipsis renderer helper
        $.fn.dataTable.render.ellipsis = function (cutoff) {
            return function (data, type, row) {
                if (type !== 'display') return data;
                return data.length > cutoff ? data.substr(0, cutoff) + '…' : data;
            }
        }
    </script>
@endpush
