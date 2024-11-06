$(document).ready(function(){
    let login = sessionStorage.getItem('login');
    if(login === null) {
        alert("Vous n'êtes pas connecté.");
        window.location.href = "connexion.php";
    }
    let prefix_api = window.prefix_api;
    $.ajax({
        url: prefix_api + "/utilisateurs.php/calories/" + login,
        method: "GET",
        dataType : "json",
    })
    .done(function(response){
        if(response.length === 0) {
            alert("Aucune donnée disponible.");
            return;
        }
        // Créer un objet pour stocker les calories par date
        let apiData = {};
        let dates = [];
        response.forEach(item => {
            let dateStr = item.DAY; // Supposons que item.DAY est au format 'YYYY-MM-DD'
            apiData[dateStr] = item.CALORIES;
            dates.push(dateStr);
        });

        // Trouver la date la plus ancienne
        dates.sort(); // Trie les dates dans l'ordre croissant
        let earliestDateStr = dates[0];
        let today = new Date();
        let todayStr = today.toISOString().split('T')[0]; // Obtenir la date d'aujourd'hui au format 'YYYY-MM-DD'

        // Générer une plage de dates de la date la plus ancienne à aujourd'hui
        function generateDateRange(startDateStr, endDateStr) {
            let startDate = new Date(startDateStr);
            let endDate = new Date(endDateStr);
            let dateArray = [];
            let currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                let dateStr = currentDate.toISOString().split('T')[0];
                dateArray.push(dateStr);
                currentDate.setDate(currentDate.getDate() + 1);
            }
            return dateArray;
        }

        let dateRange = generateDateRange(earliestDateStr, todayStr);

        // Préparer les données pour le graphique en remplissant les dates manquantes avec zéro
        let chartData = dateRange.map(dateStr => {
            return {
                date: new Date(dateStr).getTime(),
                calories: apiData[dateStr] || 0
            };
        });

        // Créer le graphique avec les données complètes
        createChart(chartData);
    })
    .fail(function(error){
        console.error(error);
    });
});


function createChart(data) {
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
            pinchZoomX:true
        }));

        // Add cursor
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);

        // Create axes
        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
            maxDeviation: 0.2,
            baseInterval: {
                timeUnit: "day",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root, {}),
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            renderer: am5xy.AxisRendererY.new(root, {})
        }));

        // Create series
        var series = chart.series.push(am5xy.LineSeries.new(root, {
            name: "Calories",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "calories",  // Make sure this matches the data field name exactly
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));
        

        // Set data
        series.data.setAll(data);

        // Make stuff animate on load
        series.appear(1000);
        chart.appear(1000, 100);
    });
}