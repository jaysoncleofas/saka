@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Users</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List of Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%" id="datatables">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>User Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.delete-modal')
@endsection

@section('scripts')
<script>
    $(function () {
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                'url': '{!! route("user.datatables") !!}',
                'type': 'GET',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            aaSorting: [],
            columns: [{
                    data: 'firstName',
                    name: 'firstName'
                },
                {
                    data: 'lastName',
                    name: 'lastName'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role.name',
                    name: 'role.name'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endsection
