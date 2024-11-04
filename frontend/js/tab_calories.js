document.addEventListener('DOMContentLoaded', function() {
    am5.ready(function() {
        // Create root element
        var root = am5.Root.new("caloriesChart");

        // Set themes
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        // Create chart
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true,
            paddingLeft: 0,
            paddingRight: 1
        }));

        // Add cursor
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);

        // Create axes
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30,
            minorGridEnabled: true
        });

        xRenderer.labels.template.setAll({
            rotation: -90,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });

        xRenderer.grid.template.setAll({
            location: 1
        });

        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
            maxDeviation: 0.3,
            baseInterval: {
                timeUnit: "day",
                count: 1
            },
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yRenderer = am5xy.AxisRendererY.new(root, {
            strokeOpacity: 0.1
        });

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: yRenderer
        }));

        // Create series
        var series = chart.series.push(am5xy.LineSeries.new(root, {
            name: "Calories",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "calories",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        // Add data
        var data = [
            { date: new Date(2023, 9, 1).getTime(), calories: 2000 },
            { date: new Date(2023, 9, 2).getTime(), calories: 1800 },
            { date: new Date(2023, 9, 3).getTime(), calories: 2200 },
            { date: new Date(2023, 9, 4).getTime(), calories: 2500 },
            { date: new Date(2023, 9, 5).getTime(), calories: 2100 },
            { date: new Date(2023, 9, 6).getTime(), calories: 2300 },
            { date: new Date(2023, 9, 7).getTime(), calories: 1900 }
        ];

        series.data.setAll(data);

        // Make stuff animate on load
        series.appear(1000);
        chart.appear(1000, 100);
    });
});