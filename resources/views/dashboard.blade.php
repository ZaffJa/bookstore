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
                        <canvas id="area-chart" class="chart-holder" width="1000" height="400">
                        </canvas>
                    </div>
                </div>
                <div id="monthly" class="tab-pane fade">
                    <div class="widget-content">
                        <canvas id="yearly-chart" class="chart-holder" width="1000" height="400">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="widget widget-table action-table">
        <div class="widget-header"><i class="icon-th-list"></i>
            <h3>Book Informations</h3>
        </div>
        <div class="widget-content" style="padding: 10px 20px 10px 20px;">
            <table id="books" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Title</th>
                    <th>Publisher</th>
                    <th>Quantity</th>
                    <th>Retail Price (RM)</th>
                    <th>Selling Price (RM)</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\Transaction::all() as $transaction)
                    <tr>
                        <td>{{ $transaction->book->barcode or null }}</td>
                        <td>{{ $transaction->book->title or null }}</td>
                        <td>{{ $transaction->book->publisher or null }}</td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->profit }}</td>
                        <td>{{ $transaction->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        var lineChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    fillColor: "rgba(220,220,220,0.5)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    data: [65, 59, 90, 81, 56, 55, 40]
                },
                {
                    fillColor: "rgba(151,187,205,0.5)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    data: [28, 48, 40, 19, 96, 27, 100]
                }
            ]
        };

        var monthlyChartData = {
            labels: ["Week 1", "Week 2", "Week 3", "Week 4", "Week 5"],
            datasets: [
                {
                    fillColor: "rgba(220,220,220,0.5)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    data: [65, 59, 90, 81, 56]
                },
                {
                    fillColor: "rgba(151,187,205,0.5)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    data: [28, 48, 40, 19, 96]
                }
            ]
        };

        new Chart(document.getElementById("area-chart").getContext("2d")).Line(monthlyChartData);
        new Chart(document.getElementById("yearly-chart").getContext("2d")).Line(lineChartData);

        $(document).ready(function () {
            $('#books').DataTable();
        });
    </script>

@endsection