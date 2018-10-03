<?php
require_once('classes/HaukionKala.php');

function asset_version($src) {
    if(is_file($src)) return $src . '?' . filemtime($src);

    return $src;
}

$weeks = file_get_contents('./weeks.json');

$validPeople = new HaukionKala();
$validPeople = $validPeople->people;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Haukion Kala oy - Vuositapaaminen</title>
    <link rel="icon" sizes="16x16" href="favicon.ico">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= asset_version('./app.css') ?>">

    <script src="<?= asset_version('./app.js') ?>" async></script>
</head>

<body>

<div id="app">
    <the-app :people='<?= json_encode($validPeople) ?>' :weeks-imported='<?= $weeks ?>'></the-app>
</div>

</body>
</html>