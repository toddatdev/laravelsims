@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('after-styles')
    <style>
        .highlight {
            background-color: #eeeeee !important;
        }
    </style>
@stop

@section('page-header')
    <h4><a href="{{ url()->previous() }}" class="text-decoration-none">{{ trans('navs.frontend.event.dashboard')}}</a> > {{ trans('labels.qse.chart_report', ['qse' => $qse->courseContents->menu_title]) }}</h4>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ $event->DisplayEventNameShort }}
        </div>
        <div class="card-body">
            <div class="principal wrapper">
                <div class="card text-center">

                    <div class="card-header">
                        <h5>
                            Series de tiempo
                        </h5>

                        <!-- <a href="#" class="icon_home"> <i class="fa fa-home"> </i></a> -->

                    </div>


                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <!-- Chart.js Object -->
                        <canvas id="lineChart" width="300" height="150"> </canvas>
                    </div>




                    <div class="card-footer text-muted">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <button type="button" class="btn btn-outline-info" disabled="">Zoom</button>

                            <label class="btn btn-info" id="zoom_1">
                                <input type="radio" name="options"  id="option1" autocomplete="off"> 3M
                            </label>
                            <label class="btn btn-info" id="zoom_2">
                                <input type="radio" name="options" id="option2" autocomplete="off"> 6M
                            </label>
                            <label class="btn btn-info" id="zoom_3">
                                <input type="radio" name="options" id="option3" autocomplete="off"> 1Y
                            </label>
                            <label class="btn btn-info" id="zoom_4">
                                <input type="radio" name="options" id="option4" autocomplete="off"> 5Y
                            </label>
                            <label class="btn btn-info" id="zoom_5">
                                <input type="radio" name="options" id="option5" autocomplete="off" > 10Y
                            </label>
                            <label class="btn btn-info active" id="zoom_max">
                                <input type="radio" name="options" id="maximo" autocomplete="off" > MAX
                            </label>

                            <!-- <label class="btn btn-info active">
                              <input type="radio" name="options" id="copia" autocomplete="off"> copia
                            </label>
                            <label class="btn btn-info active">
                              <input type="radio" name="options" id="back" autocomplete="off"> back
                            </label> -->


                        </div>
                        <!-- Download Button -->
                        <!-- Download Attribute is very important! -->
                        <a id="download"
                           download="BcrpChart.jpeg"
                           href=""
                           class="btn btn-primary float-right bg-flat-color-1"
                           title="Descargar Gráfico">
                            <!-- Download Icon -->
                            <i class="fa fa-download"></i>
                        </a>
                    </div>

                </div>
            </div>
            <div class="card collapsed-card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('labels.qse.chart_options') }}</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                id="collapse-advance-options"
                        >
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: none;">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="chart-style">Style:</label>
                            <select id="chart-style" class="form-control">
                                <option value="bar">Bar</option>
                                <option value="line">Line</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="chart-width">Width:</label>
                            <input type="number" class="form-control" id="chart-width" value="300">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="chart-height">Height:</label>
                            <input type="number" class="form-control" id="chart-height" value="150">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="chart-style">N Color:</label>
                            <select id="chart-style" class="form-control">
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="chart-style">N2 Color:</label>
                            <select id="chart-style" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="benchmark_n2" value="1" name="benchmark_n2">
                        <label class="form-check-label" for="benchmark_n2">Benchmark (N2)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="display_values" value="1" name="display_values">
                        <label class="form-check-label" for="display_values">Display Values</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="rotate_labels" value="1" name="rotate_labels">
                        <label class="form-check-label" for="rotate_labels">Rotate Labels</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="hide_comments" value="1" name="hide_comments">
                        <label class="form-check-label" for="hide_comments">Hide Comments</label>
                    </div>
                </div>
            </div>
            <div class="card collapsed-card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('labels.qse.questions') }}</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                id="collapse-advance-options"
                        >
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <form class="card-body" style="display: none;" action="" method="GET">
                    <div class="d-flex justify-content-between mb-3">
                        <button class="btn btn-primary" onclick="event.preventDefault(); $('.checkable-questions').attr('checked',function(_, attr){ return !attr})">Select All</button>
                        <button type="submit" class="btn btn-primary">View Charts</button>
                    </div>
                    <table class="table table-striped table-sm">
                        <tbody>
                        @foreach($questions as $q)
                            <tr>
                                <td style="width: 100px">
                                    <div class="form-check">
                                        <input class="form-check-input checkable-questions" type="checkbox" value="{{$q->id}}" id="q-{{$q->id}}" name="questions[]">

                                    </div>
                                </td>
                                <td>
                                    <label class="form-check-label" for="q-{{$q->id}}">
                                        {{ $q->text }}
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.0/chart.min.js" integrity="sha512-yadYcDSJyQExcKhjKSQOkBKy2BLDoW6WnnGXCAkCoRlpHGpYuVuBqGObf3g/TdB86sSbss1AOP4YlGSb6EKQPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var randomScalingFactor = function() {
            return Math.round(Math.random() * 100);
        };

        // ADD FUNCTIONS
        function addData(chart, label, data) {
            chart.data.labels.push(label);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(data);
            });
            chart.update();
            //updateCopy();
        }
        function addLabels(chart, label_x){
            for (var i = 0; i < label_x.length; i++) {
                chart.data.labels.push(label_x[i]);
            }
            chart.update();
            //updateCopy();
        }
        function addDataSet(chart, config, data, serieName){ //label, data
            var colorName = colorNames[config.data.datasets.length % colorNames.length];
            var newColor = window.chartColors[colorName];
            console.log(colorName);

            var newDataset = {
                label: serieName,  // 'Dataset ' + config.data.datasets.length,
                backgroundColor: newColor,
                borderColor: newColor.replace("0.2","1"),
                data: []
            };

            for (var index = 0; index < config.data.labels.length; ++index) {//data
                newDataset.data.push(data[index]);
            }

            config.data.datasets.push(newDataset);
            chart.update();
            //updateCopy();
        }
        //REMOVE FUNCTIONS
        function removeDataLabel(chart) {
            chart.data.labels.pop();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.pop();
            });
            chart.update();
        }
        function removeAllDataSet(chart, config){
            config.data.datasets.splice(0, config.data.labels.length+1);
            chart.update();
        }

        function removeLabels(chart){
            i = chart.data.labels.length;
            for (let index = 0; index < i; index++) {
                chart.data.labels.pop();
            }
            chart.update();
        }
        //generate dataset
        function geneData(labels){
            var data=[];

            for (let index = 0; index < labels.length; index++) {
                data.push(randomScalingFactor());
            }

            return data;
        }
        //Colors Config
        window.chartColors = {
            sea_green: 'rgb(46,139,87,0.2)',
            chocolate:'rgb(210,105,30,0.2)',
            sandy_brown:'rgb(244,164,96,0.2)',
            dark_slate_blue:'rgb(72,61,139,0.2)',
            blue_violet:'rgb(138,43,226,0.2)',
            navy:'rgb(0,0,128,0.2)',
            teal:'rgb(0,128,128,0.2)',
            red: 'rgb(255, 99, 132, 0.2)',
            orange: 'rgb(255, 159, 64, 0.2)',
            yellow: 'rgb(255, 205, 86, 0.2)',
            green: 'rgb(75, 192, 192, 0.2)',
            blue: 'rgb(54, 162, 235, 0.2)',
            purple: 'rgb(153, 102, 255, 0.2)',
            // grey: 'rgb(231,233,237, 0.2)',
            indian_red:'rgb(205,92,92,0.2)'
        };
        var colorNames = Object.keys(window.chartColors);

        //object config
        var config = {
            type: 'bar',
            data:{
                labels:[],
                datasets:[
                    //   {
                    //   label:"Amazon",
                    //   backgroundColor: 'rgba(54, 162, 235, 0.2)',//'rgb(17, 122, 101,.5)',
                    //   borderColor:'rgba(54, 162, 235, 1)',
                    //   data:[
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor(),
                    //     randomScalingFactor()
                    //   ]
                    // }
                ]
            },

            options: {
                title: {
                    display: true,
                    text: 'Ventas Mensuales',
                    fontStyle:'bold',
                    fontSize: 15,
                },
                maintainAspectRatio: true,
                legend: {
                    display: true
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        gridLines: {
                            display:true,
                            drawOnChartArea: true,
                            drawBorder:true,
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Months',
                            // fontSize:15,
                            fontFamily:'sans-serif',
                            fontStyle:'blond'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            // beginAtZero: true,
                            // maxTicksLimit: 5,
                            // stepSize: Math.ceil(250 / 5),
                            // max: 250
                        },
                        gridLines: {
                            display: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Values',
                            // fontSize:15,
                            fontFamily:'sans-serif',
                            fontStyle:'blond'
                        }
                    }]
                },
                elements: {
                    point: {
                        radius: 0,
                        hitRadius: 10,
                        hoverRadius: 4,
                        hoverBorderWidth: 3
                    }
                }
            }
        }

        //copia de data
        var copydata = [];
        var copylabels = [];

        function updateCopy() {
            copydata = [];
            copylabels = [];
            for (let index = 0; index < myLineChart.data.datasets.length; index++) {
                const element = myLineChart.data.datasets[index];

                var serie = {
                    data:[],
                    serieName:""
                };

                for (let i = 0; i < element.data.length; i++) {
                    const el = element.data[i];
                    serie.data.push(el);
                }
                serie.serieName = element.label;

                copydata.push(serie);
            }

            for (let index = 0; index < myLineChart.data.labels.length; index++) {
                const element = myLineChart.data.labels[index];
                copylabels.push(element);
            }
            console.log(copydata);
            console.log(copylabels);
        }

        //ZOOM
        function zoomSerie(chart, config, copydata, labels,n){
            var fin = labels.length;
            var ini = fin - n;

            var newLabels = labels.slice(ini,fin);
            console.log(newLabels);
            removeAllDataSet(chart,config);
            removeLabels(chart);

            addLabels(chart, newLabels);

            for (let index = 0; index < copydata.length; index++) {
                const element = copydata[index];
                addDataSet(chart, config, element.data.slice(ini,fin+1), element.serieName);
            }
        }
        //RESET
        function resetSerie(chart, config){
            removeAllDataSet(chart,config);
            removeLabels(chart);

            addLabels(chart,copylabels);
            for (let index = 0; index < copydata.length; index++) {
                const element = copydata[index];
                addDataSet(chart, config, element.data, element.serieName);
            }
        }




        var ctx = document.getElementById('lineChart').getContext("2d");

        //CREATE THE OBJECT
        var myLineChart = new Chart(ctx, config);
        //console.log(myLineChart);

        var LABELS_X = ['Enero',"Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto", "Septiembre", "Octubre","Noviembre", "Diciembre"]

        // document.getElementById('option6').addEventListener('click', function() {
        //   addData(myLineChart,('A'+randomScalingFactor()),randomScalingFactor());
        // });
        // document.getElementById('option5').addEventListener('click', function() {
        //   removeDataLabel(myLineChart);
        // });
        // document.getElementById('option4').addEventListener('click', function() {
        //   addDataSet(myLineChart, config, geneData(LABELS_X), ('Data'+randomScalingFactor()));
        // });
        // document.getElementById('option3').addEventListener('click', function() {
        //   addLabels(myLineChart, LABELS_X);
        // });

        // document.getElementById("download").addEventListener('click', function(){
        //   var url_base64jp = document.getElementById("lineChart").toDataURL("image/jpg");
        //   // document.getElementById("img1").src = url_base64jp;
        //   var a =  document.getElementById("download");
        //   a.href = url_base64jp;
        // });

        function limpiarButtons(){
            document.getElementById("zoom_max").className = "btn btn-info";
            document.getElementById("zoom_1").className = "btn btn-info";
            document.getElementById("zoom_2").className = "btn btn-info";
            document.getElementById("zoom_3").className = "btn btn-info";
            document.getElementById("zoom_4").className = "btn btn-info";
            document.getElementById("zoom_5").className = "btn btn-info";
        }


        document.getElementById("maximo").addEventListener('click', function(){
            resetSerie(myLineChart,config);
            //validate
            limpiarButtons();
            document.getElementById("zoom_max").className = "btn btn-info active";
        });
        document.getElementById("option5").addEventListener('click', function(){
            zoomSerie(myLineChart,config,copydata,copylabels,10*12);
            //validate
            limpiarButtons();
            document.getElementById("zoom_5").className = "btn btn-info active";
        });
        document.getElementById("option4").addEventListener('click', function(){
            zoomSerie(myLineChart,config,copydata,copylabels,8*12);
            //validate
            limpiarButtons();
            document.getElementById("zoom_4").className = "btn btn-info active";
        });
        document.getElementById("option3").addEventListener('click', function(){
            zoomSerie(myLineChart,config,copydata,copylabels,12);
            //validate
            limpiarButtons();
            document.getElementById("zoom_3").className = "btn btn-info active";
        });
        document.getElementById("option2").addEventListener('click', function(){
            zoomSerie(myLineChart,config,copydata,copylabels,6);
            //validate
            limpiarButtons();
            document.getElementById("zoom_2").className = "btn btn-info active";
        });
        document.getElementById("option1").addEventListener('click', function(){
            zoomSerie(myLineChart,config,copydata,copylabels,3);
            //validate
            limpiarButtons();
            document.getElementById("zoom_1").className = "btn btn-info active";
        });

        //Download Chart Image
        document.getElementById("download").addEventListener('click', function(){
            var url_base64jp = document.getElementById("lineChart").toDataURL("image/jpeg");
            var a =  document.getElementById("download");
            a.href = url_base64jp;
        });


        //====data===
        var label_array = [
            'Ene92',
            'Feb92',
            'Mar92',
            'Abr92',
            'May92',
        ];

        var data_1 = [
            412.06716502,
            381.412660224,
            401.416077696,
            353.331869205,
            383.275365183,

        ];

        var data_2 = [
            70.43397087,
            68.781796115,
            73.694611654,
            79.739439249,
            86.455173916,
        ];

        var data_3 = [
            14.862504855,
            15.91715534,
            14.729776699,
            13.876261681,
            17.597713043,
        ];


        var data_4 = [
            22.131029126,
            21.895572816,
            22.13454369,
            21.224682243,
            21.743965217,
        ]


        var serie_NAMES = [
            'N = Select Classes (3 responses)',
            "N2 = All Classes (235 responses)",
        ];
        var data_test = [data_1,data_2,data_3, data_4];

        //====================
        //main init          =
        //====================

        addLabels(myLineChart, label_array);


        for (let index = 0; index < serie_NAMES.length; index++) {
            addDataSet(myLineChart, config, data_test[index], serie_NAMES[index]);
        }



        // addLabels(myLineChart, LABELS_X);
        // addDataSet(myLineChart, config, geneData(LABELS_X), ('Data'+randomScalingFactor()));

        updateCopy();

        $('#chart-style').on('change', function (e) {
            let type = $(this).find(':selected').val();
            config.type = type;
            resetSerie(myLineChart,config);
        });
    </script>
    <script>
        $(function (){
            $('#chart-width').on('change', function (e) {
                $('#lineChart').css({width: $(this).val()});
            });

            $('#chart-height').on('change', function (e) {
                $('#lineChart').attr({height: $(this).val()});
            });
        })
    </script>
@endsection