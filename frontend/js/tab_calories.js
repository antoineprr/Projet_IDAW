$(document).ready(function(){
    let today = new Date();
    let date = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');


    let login = sessionStorage.getItem('login');
    if(login === null) {
        alert("Vous n'êtes pas connecté.");
        window.location.href = "connexion.php";
    }
    let prefix_api = "http://localhost-projet/backend/API";
    $.ajax({
        // L'URL de la requête 
        url: prefix_api + "/utilisateurs.php/calories/" + login + "/" + date,

        // La méthode d'envoi (type de requête)
        method: "GET",

        // Le format de réponse attendu
        dataType : "json",
    })
    // Ce code sera exécuté en cas de succès - La réponse du serveur est passée à done()
    .done(function(response){
        // Formater les données pour le graphique
        let chartData = response.map(item => {
            return {
                date: new Date(item.DAY).getTime(),
                calories: item.CALORIES
            };
        });

        // Créer le graphique avec les données formatées
        createChart(chartData);
    })
    // Ce code sera exécuté en cas d'échec - L'erreur est passée à fail()
    .fail(function(error){
        console.error(error);
    })
    // Ce code sera exécuté que la requête soit un succès ou un échec
    .always(function(){
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