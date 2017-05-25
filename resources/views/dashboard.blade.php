@extends('layout.master')

@section('content')

    <div class="widget">
        <div class="widget-header">
            <i class="icon-bar-chart"></i>
            <h3>
                Line Chart</h3>
        </div>
        <!-- /widget-header -->
        <div class="widget-content">
            <canvas id="area-chart" class="chart-holder" width="538" height="250">
            </canvas>
            <!-- /line-chart -->
        </div>
        <!-- /widget-content -->
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

        new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);
    </script>

@endsection