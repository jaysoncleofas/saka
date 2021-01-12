@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Reports</h1>
        <div class="section-header-breadcrumb">
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                    <h4>Montly Bills</h4>
                    </div>
                    <div class="card-body"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                    <canvas id="myChart" height="490" width="932" class="chartjs-render-monitor" style="display: block; height: 392px; width: 746px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-secondary">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pending Transactions</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($pending) }}
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-lg-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Active Transactions</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($confirmed) }}
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-lg-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Completed Transactions</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($completed) }}
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-lg-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-window-close"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Cancelled Transactions</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($cancelled) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Billing Reports</h4>
                        <div class="card-header-action">
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
                                        <th>Service</th>
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
    var ctx = document.getElementById("myChart").getContext('2d');
    var json = '{!! str_replace("\u0022","\\\\\"",json_encode( $monthly_names,JSON_HEX_QUOT)) !!}'; 
    var months = JSON.parse(json);
    var total_transactions = JSON.parse('{!! json_encode($monthly_sales) !!}');
    var total_entrance = JSON.parse('{!! json_encode($total_entrance) !!}');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Bills',
                data: total_transactions,
                borderWidth: 2,
                backgroundColor: '#6777ef',
                borderColor: '#6777ef',
                borderWidth: 2.5,
                pointBackgroundColor: '#ffffff',
                pointRadius: 4
                },{
                label: 'Total Entrance Fees',
                data: total_entrance,
                borderWidth: 2,
                backgroundColor: '#fc544b',
                borderColor: '#fc544b',
                borderWidth: 2.5,
                pointBackgroundColor: '#ffffff',
                pointRadius: 4
                },
            ]
        },
        options: {
    responsive: true,
    title: {
      display: false,
      text: 'Chart.js Line Chart'
    },
    tooltips: {
      mode: 'index',
      intersect: false,
    },
    hover: {
      mode: 'nearest',
      intersect: true
    },
    scales: {
      xAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: 'Months'
        }
      }],
      yAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: 'Bills'
        }
      }]
    }
  }
    });

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
                    data: 'service',
                    name: 'service'
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
