<?php
require_once('header_template.php');
require_once('config.php');
?>

<script>
    sessionStorage.setItem('login', '<?php echo $_SESSION['login'] ?>');
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="js/tab_calories.js"></script>
<script src="js/pie_ratio.js"></script>


<div class="container mt-5 flex-grow-1">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Bienvenue sur notre site</h1>
            <p class="lead">Découvrez nos produits et services exceptionnels.</p>
        </div>
    </div>


    <div class="container mt-5">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="card border-primary mb-3" style="width: 100%; min-height: 300px;">
                    <div class="card-header">Calories consommées par jour</div>
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <div id="caloriesChart" style="width: 100%; height: 100%; min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="card border-primary mb-3" style="width: 100%; min-height: 300px;">
                    <div class="card-header">Ratio consommées aujourd'hui</div>
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <div id="ratioPie" style="width: 100%; height: 100%; min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php
require_once('footer_template.php');
?>