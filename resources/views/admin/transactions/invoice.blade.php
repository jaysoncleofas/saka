@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4><a href="{{ route('transaction.show', $transaction->id) }}"><h4>Invoice #{{ $transaction->id }}</h4></a></h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.edit', $transaction->id) }}" class="mr-2">Edit <i class="fa fa-edit"></i></a>
                            @if ($transaction->status == 'paid')
                            <a href="#" class="btn btn-success trigger-unpaid mr-2" data-action="{{ route('transaction.unpaid', $transaction->id) }}">Paid</a>
                            @else
                            <a href="#" class="btn btn-danger trigger-pay mr-2" data-action="{{ route('transaction.pay', $transaction->id) }}">Pay</a>
                            @endif
                            <button class="btn btn-primary" id="print-invoice">Print</button>
                        </div>
                    </div>
                </div>

                <div class="card" id="invoice-content">
                    <div class="card-body">
                        <div class="row invoice-data">
                            <div class="col-6">
                                <p><small>From:</small></p>
                                <p><strong>{{ config('yourconfig.resort')->name }}</strong></p>
                                <p>{{ config('yourconfig.resort')->address }}</p>
                                <p><strong>Invoice date:</strong> {{ $transaction->created_at->toFormattedDateString() }}</p>
                            </div>
                            <div class="col-6 text-right">
                                <h4 class="h4-responsive"><small>Invoice No.</small><strong> <span class="blue-text">#{{ $transaction->id }}</span></strong></h4>
                                <p><small>To:</small></p>
                                <p><strong>{{ $transaction->guest->full_name }}</strong></p>
                                <p>{{ $transaction->guest->address }}</p>
                                
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <!-- Item list -->
                                    <table class="table table-bordered table-md">
                                        <thead>
                                            <tr>
                                                <th>Item list</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($transaction->cottage || $transaction->room->entrancefee != 'Inclusive')
                                                @if ($transaction->adults)
                                                <tr>
                                                    <td>Adults</td>
                                                    <td>{{ $transaction->adults }}</td>
                                                    @foreach ($entranceFees as $entrancefee)
                                                        @if ($entrancefee->title == 'Adults')
                                                            <td>P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }}</td>
                                                            <td>P<span class="totalprice">{{ number_format($transaction->adults * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span></td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @endif

                                                @if ($transaction->kids)
                                                <tr>
                                                    <td>Kids</td>
                                                    <td>{{ $transaction->kids }}</td>
                                                    @foreach ($entranceFees as $entrancefee)
                                                        @if ($entrancefee->title == 'Kids')
                                                            <td>P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }}</td>
                                                            <td>P<span class="totalprice">{{ number_format($transaction->kids * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span></td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @endif

                                                @if ($transaction->senior)
                                                <tr>
                                                    <td>Senior Citizen</td>
                                                    <td>{{ $transaction->senior }}</td>
                                                    @foreach ($entranceFees as $entrancefee)
                                                        @if ($entrancefee->title == 'Senior Citizen')
                                                            <td>P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }}</td>
                                                            <td>P<span class="totalprice">{{ number_format($transaction->senior * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span></td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @endif
                                            @endif

                                            @if ($transaction->cottage)
                                            <tr>
                                                <td>Cottage: {{ $transaction->cottage->name }}</td>
                                                <td>1</td>
                                                <td>P{{ number_format($transaction->type != 'day' ? $transaction->cottage->nightPrice : $transaction->cottage->price, 2) }}</td>
                                                <td>P<span class="totalprice">{{  number_format($transaction->type != 'day' ? $transaction->cottage->nightPrice : $transaction->cottage->price, 2) }}</span></td>
                                            </tr>
                                            @endif

                                            @if ($transaction->room)
                                                <tr>
                                                    <td>Room: {{ $transaction->room->name }}</td>
                                                    <td>1</td>
                                                    <td>P{{ number_format($transaction->room->price, 2) }}</td>
                                                    <td>P<span class="totalprice">{{  number_format($transaction->room->price, 2) }}</span></td>
                                                </tr>
                                            @endif

                                            @if ($transaction->extraPerson)
                                                <tr>
                                                    <td>Extra Person</td>
                                                    <td>{{ $transaction->extraPerson }}</td>
                                                    <td>P{{ number_format($transaction->room->extraPerson, 2) }}</td>
                                                    <td>P<span class="totalprice">{{  number_format($transaction->room->extraPerson*$transaction->extraPerson, 2) }}</span></td>
                                                </tr>
                                            @endif

                                            @if ($transaction->is_breakfast)
                                                <tr>
                                                    <td>Breakfast</td>
                                                    <td>1</td>
                                                    <td>P{{ number_format(config('yourconfig.resort')->breakfastPrice, 2) }}</td>
                                                    <td>P<span class="totalprice">{{ number_format(config('yourconfig.resort')->breakfastPrice, 2) }}</span></td>
                                                </tr>

                                                @foreach ($transaction->breakfasts as $breakfast)
                                                <tr>
                                                    <td>{{ $breakfast->title }}</td>
                                                    <td>1</td>
                                                    <td>P{{ number_format($breakfast->price, 2) }}</td>
                                                    <td>P<span class="totalprice">{{ number_format($breakfast->price, 2) }}</span></td>
                                                </tr>
                                                @endforeach
                                            @endif
    
                                        </tbody>
                                    </table>
                                    <!-- /.Item list -->
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-3 float-md-right ml-auto">
                                <ul class="striped list-unstyled">
                                    <li><strong>TOTAL:</strong><span class="float-right">P<span id="total_invoice">{{ number_format($transaction->totalBill, 2) }}</span></span></li>
                                </ul>
                            </div>
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
            // var sum = 0;
            // $('.totalprice').each(function()
            // {
            //     var val = parseFloat($(this).text().replace(/,/g, ''));
            //     sum += parseFloat(val);
            // });
            // var number_format_sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // $('#total_invoice').text(number_format_sum);

            $(document).on('click', '#print-invoice', function () {
                // var divContents = document.getElementById("invoice-content").innerHTML;  
                // divContents.print(); 

                var printContents = document.getElementById("invoice-content").innerHTML;

                var originalContents = $('body').html();
              
                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;

            });
        });
    </script>
@endsection