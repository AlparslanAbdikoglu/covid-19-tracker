<?php
  ini_set("allow_url_fopen", 1);
  header("Access-Control-Allow-Origin: *");

  /* fetch historical API data */
  $json = file_get_contents('https://disease.sh/v3/covid-19/all');
  $obj = json_decode($json);
  $jsonHistorial = file_get_contents('https://corona.lmao.ninja/v2/historical/all?lastdays=all');
  $objHistorial = json_decode($jsonHistorial);
  $arrayHistorial = json_decode(json_encode($objHistorial), true);

  /* cases - chart[1] */
  $datesCases = array_keys($arrayHistorial['cases']);

$items = array();
  foreach ($datesCases as $key => $value) {
    $items[] = date("j M", strtotime($value))."";

  };

$datesFormattedShort = '"'.implode('","',$items).'"' ;


//print_r($datesFormattedShort);

  $datesFormatted = "".implode(",",$datesCases);

  $casesByDay = array_values($arrayHistorial['cases']);
  $casesByDayFormatted = "'".implode("','",$casesByDay)."'";

  /* deaths - chart[2] */
  $datesDeaths = array_keys($arrayHistorial['deaths']);

$itemsD = array();
$itemsDeaths = array();
  foreach ($datesDeaths as $key => $value) {
    $itemsD[] = date("j M", strtotime($value))."";

  };

$datesDeathsFormattedShort = '"'.implode('","',$itemsD).'"' ;

  $datesFormattedDeaths ="'".implode("','",$datesDeaths)."'";
  $deathsByDay = array_values($arrayHistorial['deaths']);
  $deathsByDayFormatted = "'".implode("','",$deathsByDay)."'";



  /* top card calculations */
  $yesterdayCases = end($arrayHistorial['cases']);
  $totalCases = ($obj-> cases);

  $yesterdayDeaths = end($arrayHistorial['deaths']);
  $totalDeaths = ($obj-> deaths);

  $yesterdayRecoveries = end($arrayHistorial['recovered']);
  $totalRecoveries= ($obj-> recovered);

  $activeCases = ($obj-> active);
  $activeYesterday = ($yesterdayCases - $yesterdayDeaths - $yesterdayRecoveries);

  /* calculate percentage change */
  function getPercentageChange($oldNumber, $newNumber){
    $decreaseValue = $newNumber - $oldNumber;
    $percentage = round(($decreaseValue / $oldNumber) * 100);
    $output = "";

    if ($percentage > 0) {
      $output = $percentage . "% Növekedés";
    } elseif ($percentage < 0) {
      $output = $percentage . "% CSökkenés";
    } else {
      $output = $percentage . "% Növekedés";
    }

    return $output;
  }

  function getBadgeClass($percentage){
    $output = "";

    if ($percentage > 0) {
      $output = "badge-danger";
    } elseif ($percentage < 0) {
      $output = "badge-success";
    } else {
      $output = "badge-info";
    }

    return $output;
  }

  function thousandsCurrencyFormat($num) {
    if ($num > 1000) {
      $x = round($num);
      $x_number_format = number_format($x);
      $x_array = explode(',', $x_number_format);
      $x_parts = array('k', 'm', 'b', 't');
      $x_count_parts = count($x_array) - 1;
      $x_display = $x;
      $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
      $x_display .= $x_parts[$x_count_parts - 1];

      return $x_display;
    } else {
      return $num;
    }
  }
?>

<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <title>KoronaVírus Statisztika</title>
  <meta name="description" content="Köves a covid 19 járványhelyzetet hazai, világ statisztikák, hírek intézkedések.">

  <link rel="stylesheet" href="assets/css/tachyons.min.css">
  <link rel="stylesheet" href="assets/css/site.css?v=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!-- Global site tag (gtag.js) - Google Analytics
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162093056-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-162093056-1');
  </script>-->
  <style>
h1 {text-align: center;}</style>

  <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon//apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon//apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon//apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon//apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon//apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon//apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon//apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon//apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="assets/favicon//android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon//favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon//favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon//favicon-16x16.png">
  <link rel="manifest" href="assets/favicon//manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="assets/favicon//ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:title" content="🦠COVID-19 Tracker">
  <meta property="og:description" content="Track the spread of the Coronavirus Covid-19 outbreak in Hungary too">
</head>
<body class="">
  <div class="h-100 midnight-blue pa3 ph0-l pv6-l">
      <div class="center mw7">
          <nav class="db dt-l w-100 border-box pa3 ">
          <div class="switch-wrapper">
              <img class="theme-icon" src="assets/img/moon.svg">
              <div class="theme-switch">
                      <div class="switch"></div>
                  </div>
              </div>
          </nav>
      <article class="cf">
        <header class="header mw5 mw7-ns tl pa3">
          <div class="fl w-50-ns pa2">
          <h1 class="mt0">👑Koronavírus<img class="theme-icon" src="assets/img/coronavirus.svg">Statisztika</h1>
          <p class="lh-copy measure black-60">
             Covid-19 Világ és Hazai statisztikák, hírek intézkedések.
          </p>
           </div>
            <div class="fl w-50-ns pa2 link">
        <!-- FB SHARE HERE-->
            <a href="https://www.buymeacoffee.com/kylerphillips" target="_blank" class="navlinkblock w-inline-block" style=";">
                <div class="navbuttoniconwrapper coffee"></div>
                <div class="phbuttontextcontainer">
                    <div class="navlinktext phtitle" style="">Hívj meg egy Kávéra</div>
                    <div class="navlinktext phcopy" style="">Segísd az oldal fennmaradását.</div>
                </div>
            </a>
            </div>

        </header>
        <h1 class="mt0">🦠Világ Statisztika</h1></span>
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon">
              <img src="assets/img/cases.svg">
            </span>
            <h6 class="black-40 ttu tl">Összes fertőzött száma</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> cases) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($yesterdayCases, $totalCases));?> mr-1"><?php echo getPercentageChange($yesterdayCases, $totalCases) ?></span>
              <span class="text-muted black-40">Tegnaphoz képest (<?php echo thousandsCurrencyFormat($yesterdayCases) ?>)</span>
            </div>
          </div>
        </div>
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon">
              <img src="assets/img/deaths.svg">
            </span>
            <h6 class="black-40 ttu tl">Összes Elhunyt száma</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> deaths) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($yesterdayDeaths, $totalDeaths));?> mr-1"><?php echo getPercentageChange($yesterdayDeaths, $totalDeaths) ?></span>
              <span class="text-muted black-40">Tegnaphoz képest (<?php echo thousandsCurrencyFormat($yesterdayDeaths) ?>)</span>
            </div>
          </div>
        </div>
      </article>
      <article class="cf">
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon"><img src="assets/img/recoveries.svg"></span>
            <h6 class="black-40 ttu tl">Összesen felépültek</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> recovered) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($totalRecoveries, $yesterdayRecoveries));?> mr-1"><?php echo getPercentageChange($yesterdayRecoveries, $totalRecoveries) ?></span>
              <span class="text-muted black-40">Tegnaphoz képest (<?php echo thousandsCurrencyFormat($yesterdayRecoveries) ?>)</span>
            </div>
          </div>
        </div>
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon">
              <img src="assets/img/active_cases.svg">
            </span>
            <h6 class="black-40 ttu tl">Aktív fertőzöttek száma</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> active) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($activeYesterday, $activeCases));?> mr-1"><?php echo getPercentageChange($activeYesterday, $activeCases) ?></span>
              <span class="text-muted black-40">Tegnaphozképest (<?php echo thousandsCurrencyFormat($activeYesterday) ?>)</span>
            </div>
          </div>
        </div>
      </article>
      <br> 
      <br>
</br>
      <!-- Magyar cards-->
      <br><h1 class="mt0">🦠🇭🇺 Statisztika</h1></span></br>
      <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon">
              <img src="assets/img/cases.svg">
            </span>
            <h6 class="black-40 ttu tl">Összes fertőzött száma</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> cases) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($yesterdayCases, $totalCases));?> mr-1"><?php echo getPercentageChange($yesterdayCases, $totalCases) ?></span>
              <span class="text-muted black-40">Tegnaphoz képest (<?php echo thousandsCurrencyFormat($yesterdayCases) ?>)</span>
            </div>
          </div>
        </div>
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon">
              <img src="assets/img/deaths.svg">
            </span>
            <h6 class="black-40 ttu tl">Összes Elhunyt száma</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> deaths) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($yesterdayDeaths, $totalDeaths));?> mr-1"><?php echo getPercentageChange($yesterdayDeaths, $totalDeaths) ?></span>
              <span class="text-muted black-40">Tegnaphoz képest (<?php echo thousandsCurrencyFormat($yesterdayDeaths) ?>)</span>
            </div>
          </div>
        </div>
      </article>
      <article class="cf">
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon"><img src="assets/img/recoveries.svg"></span>
            <h6 class="black-40 ttu tl">Összesen felépültek</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> recovered) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($totalRecoveries, $yesterdayRecoveries));?> mr-1"><?php echo getPercentageChange($yesterdayRecoveries, $totalRecoveries) ?></span>
              <span class="text-muted black-40">Tegnaphoz képest (<?php echo thousandsCurrencyFormat($yesterdayRecoveries) ?>)</span>
            </div>
          </div>
        </div>
        <div class="fl w-50 tc stat-card">
          <div class="card-box tilebox-one">
            <span class="icon">
              <img src="assets/img/active_cases.svg">
            </span>
            <h6 class="black-40 ttu tl">Aktív fertőzöttek száma</h6>
            <h3 class="black tl" data-plugin="counterup"><?php echo number_format($obj-> active) ?></h3>
            <div class="sub-info pt3 pb4">
              <span class="badge <?php echo getBadgeClass(getPercentageChange($activeYesterday, $activeCases));?> mr-1"><?php echo getPercentageChange($activeYesterday, $activeCases) ?></span>
              <span class="text-muted black-40">Tegnaphozképest (<?php echo thousandsCurrencyFormat($activeYesterday) ?>)</span>
            </div>
          </div>
        </div>
      </article>

      <footer class="">

        <div class="mt1">
          <a href="https://portfolio-olive-one.vercel.app/" target="blank" title="Alparslan Abdikoglu" class="f4 dib pr2 mid-gray dim">Made by Alparslan Abdikoglu</a>
          <a href="https://disease.sh/" target="blank" title="Data Source" class="f4 dib pr2 mid-gray dim">DATA Backed by Disease API</a>
        </div>
      </footer>
  <script>

      function isDay() {
  const hours = (new Date()).getHours();
  return (hours >= 6 && hours < 18);
}
/* Dynamically change theme */
      // if (isDay() == false) {
      //     $("body").toggleClass("light-theme");
      // } else {
      //     $("body").toggleClass("");
      // }

      $(".theme-switch").on("click", () => {
          $("body").toggleClass("light-theme");
      });
  </script>

  <script>// Add basic styles for active tabs
$('.tabs__menu-item').on('click', function() {
  $(this).addClass('bg-white').addClass('red');
  $(this).siblings().removeClass('red');
});</script>

</body>
</html>