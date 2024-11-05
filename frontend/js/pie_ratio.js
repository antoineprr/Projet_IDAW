$(document).ready(function(){
    let today = new Date();
    let date = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');


    let login = sessionStorage.getItem('login');
    if(login === null) {
        alert("Vous n'êtes pas connecté.");
        window.location.href = "connexion.php";
    }
    let prefix_api = window.prefix_api;
    $.ajax({
        // L'URL de la requête 
        url: prefix_api + "/utilisateurs.php/daily_ratios/" + login + "/" + date,

        // La méthode d'envoi (type de requête)
        method: "GET",

        // Le format de réponse attendu
        dataType : "json",
    })
    .done(function(response){
        // Define mapping for shorter names
        const labelMapping = {
            "Sucres (g/100 g)": "Sucres",
            "Fibres alimentaires (g/100 g)": "Fibres",
            "Glucides (g/100 g)": "Glucides",
            "Lipides (g/100 g)": "Lipides",
            "Protéines, N x 6.25 (g/100 g)": "Protéines"
            // Add more mappings as needed
        };

        // Transform data using the mapping
        let pieData = response.map(item => {
            return {
                nom_ratio: labelMapping[item.NOM_RATIO] || item.NOM_RATIO,
                pourcentage_ratio: item.QUANTITE
            };
        });

        // Create the chart with the transformed data
        createPie(pieData);
    })
    // Ce code sera exécuté en cas d'échec - L'erreur est passée à fail()
    .fail(function(error){
        console.error(error);
    })
    // Ce code sera exécuté que la requête soit un succès ou un échec
    .always(function(){
    });
});



function createPie(data) {
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
                valueField: "pourcentage_ratio",
                categoryField: "nom_ratio",
                endAngle: 270
            })
        );

        series.states.create("hidden", {
            endAngle: -90
        });

        series.data.setAll(data);

        // Animate chart on load
        series.appear(1000, 100);
    });
};