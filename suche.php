<?php
$ergebnis = null;
$fehler   = null;

// Formular wurde abgeschickt
if (isset($_GET['land']) && $_GET['land'] != '') {

    $suchbegriff = $_GET['land'];
    $apiUrl      = 'https://restcountries.com/v3.1/name/' . rawurlencode($suchbegriff) . '?fullText=true';
    $response    = file_get_contents($apiUrl);

    if ($response == false) {
        $fehler = 'Land nicht gefunden.';
    } else {
        $daten = json_decode($response, true);

        if (isset($daten['status'])) {
            // API hat einen Fehler zurückgegeben
            $fehler = 'Kein Land mit dem Namen "' . $suchbegriff . '" gefunden.';
        } else {
            // Erstes Ergebnis nehmen
            $ergebnis = $daten[0];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Ländersuche</title>
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

    <h2>Land suchen</h2>

    <!-- Suchformular -->
    <div class="suchformular">
        <form method="GET" action="suche.php">
            <input type="text" name="land" placeholder="z.B. Deutschland" value="<?php echo isset($_GET['land']) ? $_GET['land'] : ''; ?>">
            <button type="submit">Suchen</button>
        </form>

        <!-- Fehlermeldung anzeigen -->
        <?php if ($fehler != null): ?>
            <div class="fehler"><?php echo $fehler; ?></div>
        <?php endif; ?>
    </div>

    <!-- Ergebnis anzeigen -->
    <?php if ($ergebnis != null): ?>

        <?php
        $name       = $ergebnis['name']['common'];
        $hauptstadt = $ergebnis['capital'][0];
        $region     = $ergebnis['region'];
        $bevoelkerung = number_format($ergebnis['population'], 0, ',', '.');
        $flagge     = $ergebnis['flags']['png'];
        ?>

        <div class="ergebnis">
            <h2><?php echo $name; ?></h2>

            <img src="<?php echo $flagge; ?>" alt="Flagge von <?php echo $name; ?>">

            <p><strong>Hauptstadt:</strong> <?php echo $hauptstadt; ?></p>
            <p><strong>Region:</strong> <?php echo $region; ?></p>
            <p><strong>Bevölkerung:</strong> <?php echo $bevoelkerung; ?></p>

            <a href="landinfo.php?name=<?php echo urlencode($name); ?>">Mehr Details →</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
