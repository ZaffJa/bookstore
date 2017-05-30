@extends('layout.master')

@section('content')

    <div class="widget">
        <div class="widget-header"><i class="icon-bar-chart"></i>
            <h3>Transaction Graph</h3>
        </div>
        <div class="widget-content">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#weekly">Weekly</a></li>
                <li><a data-toggle="tab" href="#monthly">Monthly</a></li>
            </ul>
            <div class="tab-content">
                <div id="weekly" class="tab-pane fade in active">
                    <div class="widget-content">
                        <h3>Weekly Profits</h3>
                        <canvas id="area-chart" class="chart-holder" width="1000" height="400">
                        </canvas>
                    </div>
                </div>
                <div id="monthly" class="tab-pane fade">
                    <div class="widget-content">
                        <h3 class="text-center">Monthly Profits</h3>
                        <canvas id="yearly-chart" class="chart-holder" width="1000" height="400">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="widget widget-table action-table">
        <div class="widget-header"><i class="icon-th-list"></i>
            <h3>Transaction Informations</h3>
        </div>
        <div class="widget-content" style="padding: 10px 20px 10px 20px;">
            <table id="books" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Barcode</th>
                    <th>Title</th>
                    <th>Publisher</th>
                    <th>Quantity</th>
                    <th>Profit (RM)</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\Transaction::all() as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_type_id == 1 ? 'Buy/Sell' : 'Transfer' }}</td>
                        <td>{{ $transaction->book->barcode or null }}</td>
                        <td>{{ $transaction->book->title or null }}</td>
                        <td>{{ $transaction->book->publisher or null }}</td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->transaction_type_id == 1 ? $transaction->profit : 0 }}</td>
                        <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $.getJSON('/dashboard/charts', function (transactions) {
            console.log(transactions);

            var lineChartData = {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: [
                    {
                        labels: "Profits",
                        fillColor: "rgba(220,220,220,0.5)",
                        strokeColor: "rgba(220,220,220,1)",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        data: transactions.monthlyProfits
                    }
                ]
            };

            var monthlyChartData = {
                labels: transactions.weekNumber,
                datasets: [
                    {
                        labels: "Profits",
                        fillColor: "rgba(220,220,220,0.5)",
                        strokeColor: "rgba(220,220,220,1)",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        data: transactions.weeklyProfits
                    }
                ]
            };

            new Chart(document.getElementById("area-chart").getContext("2d")).Line(monthlyChartData);
            new Chart(document.getElementById("yearly-chart").getContext("2d")).Line(lineChartData);
        });


        $(document).ready(function () {
            $('#books').DataTable();
        });
    </script>

@endsection