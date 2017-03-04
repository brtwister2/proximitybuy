app.controller('HomeController', function ($scope, Service, $http) {

    $scope.Service = Service;
    var self = this;

    $scope.colors = ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)","rgba(59, 80, 128,0.38)", "rgba(184, 220, 184,0.38)"];

    self.init = function () {
        Service.setView('home');
        NProgress.start();
        self.loadAnalytics();

    }

    self.loadAnalytics = function () {
        $http.get(ANALYTICS_URL)
            .then(function (response) {
                NProgress.done();
                if(response && response.data && response.data[0].dataset != null) {
                    $scope.analytics = response.data[0];
                    self.loadGraph();
                    self.loadMapGraph();
                }
            })
            .catch(function () {
                NProgress.done();
                if (confirm("An unexpected erro happened. Try Again?")) {
                    self.loadAnalytics();
                }
            });

    };

    self.loadMapGraph = function(){

        var values = {};
        for(n in $scope.analytics.top_countries){
            values[n] = $scope.analytics.top_countries[n].count;
        }

        $("#world-map-gdp").length && $("#world-map-gdp").vectorMap({
            map: "world_en",
            backgroundColor: null,
            color: "#ffffff",
            hoverOpacity: .7,
            selectedColor: "#666666",
            enableZoom: !0,
            showTooltip: !0,
            values: values,
            scaleColors: ["#E6F2F0", "#149B7E"],
            normalizeFunction: "polynomial"
        })
    };

    self.loadGraph = function(){

        var dataset = [];
        var keys = Object.keys($scope.analytics.dataset);

        var length = -1;
        // find the hightest key
        for(n in $scope.analytics.dataset){
            for(m in $scope.analytics.dataset) {
                if (length < Object.keys($scope.analytics.dataset[m]).length){
                    length = Object.keys($scope.analytics.dataset[m]).length;
                }
            }
        }

        for(var i = 0; i <= keys.length -1; i++){
            dataset[i] = [[0,0]];

            var ckeys = Object.keys($scope.analytics.dataset[keys[i]]);
            var v = 1;
            for(var j = 0; j <= length-1; j++){
                if(ckeys[j]) {
                    v++;
                    dataset[i].push([j + 1, v]);
                }else{
                    dataset[i].push([j + 1, 0]);
                }
            }



        }
        console.log(dataset);

        var a = [[0, 0], [1, 2], [2, 1], [3, 4]];

        var options = {
            series: {
                lines: {
                    show: !1,
                    fill: !0
                },
                splines: {
                    show: !0,
                    tension: .4,
                    lineWidth: 1,
                    fill: .4
                },
                points: {
                    radius: 0,
                    show: !0
                },
                shadowSize: 2
            },
            grid: {
                verticalLines: !0,
                hoverable: !0,
                clickable: !0,
                tickColor: "#d5d5d5",
                borderWidth: 1,
                color: "#fff"
            },
            colors: $scope.colors,
            xaxis: {
                tickColor: "rgba(51, 51, 51, 0.06)",
                mode: "time",
                tickSize: [1, "day"],
                axisLabel: "Date",
                axisLabelUseCanvas: !0,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: "Verdana, Arial",
                axisLabelPadding: 10
            },
            yaxis: {
                ticks: 8,
                tickColor: "rgba(51, 51, 51, 0.06)"
            },
            tooltip: !1
        }
            , h = {
            grid: {
                show: !0,
                aboveData: !0,
                color: "#3f3f3f",
                labelMargin: 10,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: !0,
                hoverable: !0,
                autoHighlight: !0,
                mouseActiveRadius: 100
            },
            series: {
                lines: {
                    show: !0,
                    fill: !0,
                    lineWidth: 2,
                    steps: !1
                },
                points: {
                    show: !0,
                    radius: 4.5,
                    symbol: "circle",
                    lineWidth: 3
                }
            },
            legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function(a, b) {
                    return a + "&nbsp;&nbsp;"
                },
                width: 40,
                height: 1
            },
            colors: ["#96CA59", "#3F97EB", "#72c380", "#6f7a8a", "#f7cb38", "#5a8022", "#2c7282"],
            shadowSize: 0,
            tooltip: !0,
            tooltipOpts: {
                content: "%s: %y.0",
                xDateFormat: "%d/%m",
                shifts: {
                    x: -30,
                    y: -50
                },
                defaultTheme: !1
            }
        }
            , i = {
            series: {
                curvedLines: {
                    apply: !0,
                    active: !0,
                    monotonicFit: !0
                }
            },
            colors: ["#26B99A"],
            grid: {
                borderWidth: {
                    top: 0,
                    right: 0,
                    bottom: 1,
                    left: 1
                },
                borderColor: {
                    bottom: "#7F8790",
                    left: "#7F8790"
                }
            }
        };

        $.plot("#chart_plot_01", dataset, options);

    }

});

function gd(a, b, c) {
    return new Date(a,b - 1,c).getTime()
}