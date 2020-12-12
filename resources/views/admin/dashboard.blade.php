@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Active Transactions</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($active_transactions) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Transactions</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($total_transactions) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="far fa-file"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Walk in</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($walkin) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Reservation</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($reservation) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Current Transactions</h4>
                    <div class="card-header-action">
                        <a href="{{ route('transaction.create') }}" class="btn btn-primary">Add Transaction</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%" id="datatables">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Client</th>
                                    <th>Cottage/s</th>
                                    <th>Room/s</th>
                                    <th>Check In / Check Out</th>
                                    <th>Type</th>
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
</section>
@endsection

@section('scripts')
<script>
    $(function () {
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                'url': '{!! route("dashboard.transaction_datatables") !!}',
                'type': 'GET',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            aaSorting: [],
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'client',
                    name: 'client'
                },
                {
                    data: 'cottage',
                    name: 'cottage'
                },
                {
                    data: 'room',
                    name: 'room'
                },
                {
                    data: 'checkin',
                    name: 'checkin'
                },
                {
                    data: 'reservation',
                    name: 'reservation'
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