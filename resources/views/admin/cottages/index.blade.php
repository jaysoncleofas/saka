@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Cottages</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List of Cottages</h4>
                        <div class="card-header-action">
                            <a href="{{ route('cottage.create') }}" class="btn btn-primary">Add Cottage</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%" id="datatables">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Cottage</th>
                                        <th>Price</th>
                                        <th>Night Price</th>
                                        <th>Units</th>
                                        <th>Descriptions</th>
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
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                'url': '{!! route("cottage.datatables") !!}',
                'type': 'GET',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            aaSorting: [],
            columns: [{
                    data: 'image',
                    name: 'image'
                },{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'nightPrice',
                    name: 'nightPrice'
                },
                {
                    data: 'units',
                    name: 'units'
                },
                {
                    data: 'descriptions',
                    name: 'descriptions'
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
