<?php
// Alle Länder von der API holen
$apiUrl = 'https://restcountries.com/v3.1/all?fields=name,flags';
$response = file_get_contents($apiUrl);
$laender = json_decode($response, true);

// Länder alphabetisch sortieren
usort($laender, function($a, $b) {
    return strcmp($a['name']['common'], $b['name']['common']);
});
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Alle Länder</title>
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

    <h2>Alle Länder</h2>
    <p>Es gibt <?php echo count($laender); ?> Länder.</p>

    <br>

    <div class="laender-grid">
        <?php foreach ($laender as $land): ?>

            <?php
            $name   = $land['name']['common'];
            $flagge = $land['flags']['png'];
            ?>

            <a class="land-karte" href="landinfo.php?name=<?php echo urlencode($name); ?>">
                <img src="<?php echo $flagge; ?>" alt="Flagge von <?php echo $name; ?>">
                <p><?php echo $name; ?></p>
            </a>

        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
