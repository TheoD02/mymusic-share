<?php use Core\CSRFHelper;
use Core\FlashMessageService;
use Core\UserHelper;

$router     = AltoRouter::getRouterInstance();
$showPlayer = str_contains($_SERVER['REQUEST_URI'], 'category') || str_contains($_SERVER['REQUEST_URI'], 'new-release');
?>
<!doctype html>
<html lang="fr" dir="ltr">
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <!-- Personal CSS -->
    <link rel="stylesheet" href="/assets/css/player.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Tagify CSS -->
    <link rel="stylesheet" href="/assets/css/tagify.min.css">


    <!-- Scripts -->

    <title><?= $title ?? DEFAULT_PAGE_NAME ?></title>
</head>
<body class="<?= $showPlayer ? 'hide-overflow' : '' ?>">
    <div id="alerts" class="d-none">
        <?= FlashMessageService::showAllMessages() ?>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $router->generate('home') ?>">MyMusic Share</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= $router->generate('home') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('categoriesList') ?>">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('newRelease') ?>">Nouveautés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="<?= $router->generate('contact') ?>">Contact</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
                    <?= CSRFHelper::generateCsrfHiddenInput() ?>
                    <button class="btn btn-outline-success" type="submit">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </form>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (UserHelper::isAuthAsAnyRole()) : ?>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                Connecté en tant que <?= $_SESSION['user']['lastname'] . ' ' . $_SESSION['user']['firstname'] ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                <li>
                                    <a class="dropdown-item" href="<?= $router->generate('profileCurrentSubscription') ?>">
                                        Mon compte
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <?php if (UserHelper::isAuthAsAdmin()): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= $router->generate('adminHome') ?>">
                                            Panel admin
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item" href="<?= $router->generate('logout') ?>">
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-secondary mx-2" aria-current="page" href="<?= $router->generate('register') ?>">
                                S'inscrire
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-secondary mx-2" aria-current="page" href="<?= $router->generate('login') ?>">
                                Se connecter
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div id="<?= $showPlayer ? 'content-container' : 'content-container-no-player' ?>"><?= $content ?></div>
    <?php if ($showPlayer) : ?>
        <div id="player-container" class="container-fluid">
            <div class="row mx-auto" id="AP">
                <div class="col-2 d-flex justify-content-center align-self-center">
                    <i id="play-btn" class="mdi mdi-play-circle-outline" style="font-size: 3rem;"></i>
                </div>
                <div class="col d-flex flex-column justify-content-center" id="container-informations" style="padding-bottom: 2rem;">
                    <div class="d-flex flex-column" id="trackinfo">
                        <span id="song-data-title" class="text-center">Text</span>
                        <span id="song-data-artists" class="text-center">Text</span>
                    </div>
                    <div id="progress-container">
                        <div id="progress">
                            <span id="progress-time" class="d-flex w-100 justify-content-center">--:-- / --:-- - 0 %</span>
                            <div id="progress-bars-container">
                                <div class="progress flex-fill" id="progress-bar">
                                    <div class="progress-bar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="progress flex-fill" id="progress-buffer">
                                    <div class="progress-bar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 col-md-2 d-flex flex-column justify-content-center align-items-center">
                    <i id="volumeIcon" class="mdi mdi-volume-medium"></i>
                    <input type="range" id="playerVolume" class="form-range bg-transparent "/>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Scripts -->
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha1/0.6.0/sha1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- SweetAlertManager -->
    <script src="/assets/js/SweetAlertManager.js"></script>
    <!-- Tagify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/3.22.2/tagify.min.js"></script>
    <!-- JS Media Tags-->
    <script src="/assets/js/jsmediatags.min.js"></script>
    <!-- JS -->
    <script src="/assets/js/main.both.js"></script>
    <script src="/assets/js/main.front.js"></script>
    <!-- Player JS -->
    <script src="/assets/js/player.js"></script>
</body>
</html>