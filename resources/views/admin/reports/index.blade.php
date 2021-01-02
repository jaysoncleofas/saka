@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Reports</h1>
        <div class="section-header-breadcrumb">
            <form action="{{ route('report.index') }}" method="get">
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
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Billing Reports</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="summary">
                                    <div class="summary-info">
                                        <h4>P{{ number_format($TotalEntrancefee, 2) }}</h4>
                                        <div class="text-muted">Total Entrance fees</div>
                                        <div class="d-block mt-2">                              
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="summary">
                                    <div class="summary-info">
                                        <h4>P{{ number_format($TotalBill, 2) }}</h4>
                                        <div class="text-muted">Total Bills</div>
                                        <div class="d-block mt-2">                              
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-striped" width="100%" id="datatables">
                                <thead>
                                    <tr>
                                        <th>Check-In Date</th>
                                        <th>Guest</th>
                                        <th>Room</th>
                                        <th>Cottage</th>
                                        <th>Total Entrance fees</th>
                                        <th>Total Bills</th>
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

@endsection

@section('scripts')
<script>
    $(function () {
        var start = moment("{!! isset($_GET['startdate']) ? Carbon\Carbon::parse($_GET['startdate'])->startOfDay() : Carbon\Carbon::now()->startOfMonth() !!}");
        var end = moment("{!! isset($_GET['enddate']) ? Carbon\Carbon::parse($_GET['enddate'])->endOfDay() : Carbon\Carbon::now()->endOfMonth() !!}");
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
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
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
                'url': '{!! route("report.datatables") !!}',
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
                    data: 'checkIn_at',
                    name: 'checkIn_at'
                }, {
                    data: 'guest',
                    name: 'guest'
                },
                {
                    data: 'room',
                    name: 'room'
                },
                {
                    data: 'cottage',
                    name: 'cottage'
                },
                {
                    data: 'totalEntranceFee',
                    name: 'totalEntranceFee'
                },
                {
                    data: 'totalBill',
                    name: 'totalBill'
                },
            ]
        });
    });

</script>
@endsection
