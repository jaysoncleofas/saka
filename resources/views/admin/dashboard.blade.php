@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
            <form action="{{ route('dashboard.index') }}" method="get">
                <div class="d-flex">
                    <div class="form-group mr-2 mb-0">
                        {{-- <label class="d-block">Date Range Picker With Button</label> --}}
                        <a href="javascript:;" class="btn btn-info daterange-btn icon-left btn-icon"><i class="fas fa-calendar"></i> <span>-</span> 
                        </a>
                    </div>
                    <input type="hidden" name="startdate" class="startdate">
                    <input type="hidden" name="enddate" class="enddate">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary submit-filter">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-bolt"></i>
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
        {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-poll"></i>
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
        </div> --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-secondary">
                    <i class="fas fa-walking"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Walk-ins</h4>
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
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Reservations</h4>
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
                    <h4>Active Transactions</h4>
                    <div class="card-header-action">
                        <a href="{{ route('transaction.create') }}" class="btn btn-primary">Add Transaction</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" width="100%" id="datatables">
                            <thead>
                                <tr>
                                    <th>Control Number</th>
                                    <th>Guest</th>
                                    <th>Rent</th>
                                    <th>Use Type</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
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
        var start = moment("{!! isset($_GET['startdate']) ? Carbon\Carbon::parse($_GET['startdate'])->startOfDay() : Carbon\Carbon::now()->startOfDay() !!}");
        var end = moment("{!! isset($_GET['enddate']) ? Carbon\Carbon::parse($_GET['enddate'])->endOfDay() : Carbon\Carbon::now()->endOfDay() !!}");
        console.log(start);
        console.log(end);
        function cb(start, end) {
            $('.daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('.startdate').val(start.format('Y-M-D'));
            $('.enddate').val(end.format('Y-M-D'));
        }

        $('.daterange-btn').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
            'This Week': [moment().startOf('week'), moment().endOf('week')],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                'url': '{!! route("dashboard.transaction_datatables") !!}',
                'type': 'GET',
                'data': {
                    'startdate': '{{ isset($_GET['startdate']) ? date('Y-m-d',strtotime($_GET['startdate'])) : ''}}',
                    'enddate': '{{ isset($_GET['enddate']) ? date('Y-m-d',strtotime($_GET['enddate'])) : ''}}'
                },
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
                    data: 'guest',
                    name: 'guest'
                },
                {
                    data: 'service',
                    name: 'service'
                },
                {
                    data: 'usetype',
                    name: 'usetype'
                },
                {
                    data: 'checkin',
                    name: 'checkin'
                },
                {
                    data: 'checkout',
                    name: 'checkout'
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