<?php use Core\CSRFHelper;

$router = AltoRouter::getRouterInstance(); ?>
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
    <!-- Personnal CSS -->
    <link rel="stylesheet" href="/assets/css/style.admin.css">
    <!-- Tagify CSS -->
    <link rel="stylesheet" href="/assets/css/tagify.min.css">

    <!-- Scripts -->

    <title><?= $title ?? DEFAULT_PAGE_NAME ?></title>
</head>
<body>
    <div id="alerts" class="d-none">
        <?= \Core\FlashMessageService::showAllMessages() ?>
    </div>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 px-3 my-2 bg-transparent" href="<?= AltoRouter::getRouterInstance()->generate('adminHome') ?>">
            MyMusic Share - Admin
        </a>
        <button class="navbar-toggler float-end me-2 my-2 d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu"
                 class="col-md-3 col-lg-2 d-md-block text-center bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= $router->generate('adminHome'); ?>">
                                Accueil
                            </a>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-center align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Gestion des catégories</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('adminCategoriesList'); ?>">
                                Voir la listes des catégories
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="<?= AltoRouter::getRouterInstance()->generate('adminAddCategory') ?>" method="post">
                                <?= CSRFHelper::generateCsrfHiddenInput() ?>
                                <button type="submit" name="addCategory" class="btn btn-primary">
                                    Ajoute une catégorie
                                </button>
                            </form>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-center align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Gestion des musiques</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('adminMusicsList'); ?>">
                                Voir la listes des musiques
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('adminPendingMusicsList'); ?>">
                                Voir la listes des musiques en attentes
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="<?= AltoRouter::getRouterInstance()->generate('adminUploadMusic') ?>" method="post">
                                <?= CSRFHelper::generateCsrfHiddenInput() ?>
                                <button type="submit" name="uploadMusic">
                                    Ajouter une musique
                                </button>
                            </form>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-center align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Gestion des artistes</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('adminArtistsList'); ?>">
                                Voir la liste des artistes
                            </a>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-center align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Gestion des membres</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('adminUsersList'); ?>">
                                Voir la liste des membres
                            </a>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-center align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Actions</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('home'); ?>">
                                Retourner sur le site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('logout'); ?>">
                                Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="pt-3 pb-2 mb-3">
                    <h1 class="h1 text-center mt-5"><?= $title ?? 'Panneau de gestion' ?></h1>
                    <div class="col-12"><?= $content ?></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <!-- Tagify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/3.22.2/tagify.min.js"></script>
    <!-- JS Media Tags-->
    <script src="/assets/js/jsmediatags.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- SweetAlertManager -->
    <script src="/assets/js/SweetAlertManager.js"></script>
    <!-- JS -->
    <script src="/assets/js/main.both.js"></script>
    <script src="/assets/js/main.admin.js"></script>
</body>
</html>