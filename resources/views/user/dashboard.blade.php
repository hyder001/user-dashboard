<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

        <style>
            #profileImage {
                font-family: Arial, Helvetica, sans-serif;
                width: 2.5rem;
                height: 2.5rem;
                border-radius: 50%;
                background: #004D3C;
                font-size: 1.5rem;
                color: #fff;
                text-align: center;
                line-height: 2.5rem;
                margin: 0rem 0;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="pos-f-t">
                <nav class="navbar navbar-dark bg-dark">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <span style="color: white;margin-right: 60%;">User Dashboard</span>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search">
                    </form>
                </nav>
            </div>

            <div class="row p-3">
                @foreach($users as $user)
                    <div class="col-sm-4">
                        <div class="card" style="width: 23.5rem; margin-top: 10px;">
                            <div class="card-body">
                                @if($user['avatar'])
                                    <img class="rounded-circle" style="height: 40px;" src="{{ asset("avatar/avatar.png") }}">
                                @else
                                    <div id="profileImage">{{$user['name'][0]}}</div>
                                @endif
                                <h5 class="card-title">{{ $user['name'] }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $user['occupation'] }}</h6>
                                <div class="row">
                                    <div class="ml-3">
                                        <canvas class="chart" id="{{uniqid();}}" time="{{json_encode($user['timelogs'])}}"
                                            revenue="{{json_encode($user['revenuelogs'])}}"></canvas>
                                    </div>
                                </div>
                                <div class="widget-content-left ml-3">

                                    <div class="widget-heading" style="font-size: 12px;">{{$user['impression']}}</div>
                                    <div class="widget-subheading" style="font-size: 10px;">Impression</div>
                                    <div class="widget-heading" style="font-size: 12px;">{{$user['conversion']}}</div>
                                    <div class="widget-subheading" style="font-size: 10px;">Conversions</div>
                                    <div class="widget-heading" style="font-size: 12px;">${{$user['revenue']}}</div>
                                    <div class="widget-subheading" style="font-size: 10px;">Revenu</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="widget-heading ml-3">Conversions {{$user['duration']}} </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <script type="text/javascript" src=" {{ asset('js/jquery-3.2.1.slim.min.js') }} "></script>
        <script type="text/javascript" src=" {{ asset('js/popper.min.js') }} "></script>
        <script type="text/javascript" src=" {{ asset('js/bootstrap.min.js') }} "></script>
        <script type="text/javascript" src=" {{ asset('js/echarts.min.js') }} "></script>

    </body>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            document.querySelectorAll('.chart').forEach(function(chartDom) {
                renderChart(chartDom)
            });
        });
        function renderChart(chartDom) {
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            animationDuration: 750,
            color: "#5470c6",
            gradientColor: "green",
            grid: {
                show: false,
                z: 0,
                left: 0,
                right: 10,
                top: 15,
                bottom: 5,
                containLabel: false,
                backgroundColor: "rgba(0,0,0,0)",
                borderWith: 1,
                borderColor: "ccc"
            },

            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: JSON.parse(chartDom.getAttribute('time'))
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                data: JSON.parse(chartDom.getAttribute('revenue')),
                type: 'line',
                areaStyle: {}
            }]
        };

        option && myChart.setOption(option);
        }
    </script>
</html>