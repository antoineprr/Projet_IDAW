document.addEventListener('DOMContentLoaded', function() {
    am5.ready(function() {
        // Create root element
        var root = am5.Root.new("ratioPie");

        // Set themes
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        // Create chart
        var chart = root.container.children.push(
            am5percent.PieChart.new(root, {
                endAngle: 270
            })
        );

        // Create series
        var series = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                endAngle: 270
            })
        );

        series.states.create("hidden", {
            endAngle: -90
        });

        // Set data
        var data = [
            { category: "Protéines", value: 40 },
            { category: "Glucides", value: 30 },
            { category: "Lipides", value: 20 },
            { category: "Fibres", value: 10 }
        ];

        series.data.setAll(data);

        // Animate chart on load
        series.appear(1000, 100);
    });
});