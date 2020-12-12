@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Reservations</h1>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pending</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($pending) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List of Reservations</h4>
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
                                        <th>Status</th>
                                        <th>Approve</th>
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

@endsection

@section('scripts')
<script>
    $(function () {
        var datatables2 = $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                'url': '{!! route("reservation.datatables") !!}',
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
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'approve',
                    name: 'approve'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('change', '.active-mode-switch', function () {
            var _this = $(this);
            var _url = _this.data('action');
            var id = _this.data('id');
            var status = 'pending';
            if (_this.is(':checked')) {
                status = 'active';
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: _url,
                type : 'PUT',
                data: { id: id, status : status },
                success: function(result) {
                    var newResult = JSON.parse(result);
                    swal(newResult.status, {
                        icon: 'success',
                    });
                    datatables2.ajax.reload();
                },
                error : function(error) {
                    console.log('error');
                }
            });
        });
    });
</script>
@endsection
