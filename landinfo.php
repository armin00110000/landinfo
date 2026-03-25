<?php
$land   = null;
$fehler = null;

// Name aus der URL holen
if (isset($_GET['name']) && $_GET['name'] != '') {

    $name     = $_GET['name'];
    $apiUrl   = 'https://restcountries.com/v3.1/name/' . rawurlencode($name) . '?fullText=true';
    $response = file_get_contents($apiUrl);

    if ($response == false) {
        $fehler = 'Land nicht gefunden.';
    } else {
        $daten = json_decode($response, true);

        if (isset($daten['status'])) {
            $fehler = 'Land "' . $name . '" wurde nicht gefunden.';
        } else {
            $land = $daten[0];
        }
    }

} else {
    $fehler = 'Kein Land angegeben.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Land Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Länder-Info</h1>
    <nav>
        <a href="index.php">Alle Länder</a>
        <a href="suche.php">Suche</a>
    </nav>
</header>

<div class="container">

    <a class="zurueck" href="index.php">← Zurück</a>

    <!-- Fehlermeldung -->
    <?php if ($fehler != null): ?>
        <div class="fehler"><?php echo $fehler; ?></div>

    <!-- Länderinfos anzeigen -->
    <?php elseif ($land != null): ?>

        <?php
        // Alle Werte vorbereiten
        $name         = $land['name']['common'];
        $hauptstadt   = $land['capital'][0];
        $region       = $land['region'];
        $bevoelkerung = number_format($land['population'], 0, ',', '.');
        $flaeche      = number_format($land['area'], 0, ',', '.');
        $flagge       = $land['flags']['png'];

        // Sprachen zusammenbauen
        $sprachen = implode(', ', $land['languages']);

        // Währungen zusammenbauen
        $waehrungen = '';
        foreach ($land['currencies'] as $code => $info) {
            $waehrungen .= $info['name'] . ' (' . $info['symbol'] . ') ';
        }
        ?>

        <div class="detail-box">

            <h2><?php echo $name; ?></h2>

            <img src="<?php echo $flagge; ?>" alt="Flagge von <?php echo $name; ?>">

            <p><span>Hauptstadt:</span> <?php echo $hauptstadt; ?></p>
            <p><span>Region:</span> <?php echo $region; ?></p>
            <p><span>Bevölkerung:</span> <?php echo $bevoelkerung; ?></p>
            <p><span>Fläche:</span> <?php echo $flaeche; ?> km²</p>
            <p><span>Sprachen:</span> <?php echo $sprachen; ?></p>
            <p><span>Währungen:</span> <?php echo $waehrungen; ?></p>

        </div>

    <?php endif; ?>

</div>

</body>
</html>
