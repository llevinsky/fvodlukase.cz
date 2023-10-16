<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="components/css/style.css" />
    <link rel="stylesheet" type="text/css" href="components/css/header.css" />
    <link rel="stylesheet" type="text/css" href="components/css/footer.css" />
    <script src="https://kit.fontawesome.com/bdd5d57c47.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navigation bar -->
    <header class="header">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <img src="https://www.bidli.cz/img/logo_blue.png" alt="logo">
        </a>
        <!-- Hamburger icon -->
        <input class="side-menu" type="checkbox" id="side-menu" />
        <label class="hamb" for="side-menu"><span class="hamb-line"></span></label>
        <!-- Menu -->
        <nav class="nav">
            <ul class="menu">
                <li><a href="index.php">Úvod</a></li>
                <li><a href="o_mne.php">O mně</a></li>
                <li><a href="rodinne_domy.php">Rodinné domy</a></li>
                <li><a href="bytove_domy.php">Bytové domy</a></li>
                <li><a href="technologie.php">Technologie</a></li>
                <li><a href="kontakt.php">Kontakt</a></li>
                <li id="login"><a href="login.php">Přihlásit se</a></li>
                </div>
            </ul>
        </nav>
    </header>