<div class="container my-5 text-center">
    <h1>Erreur : <?= $errorCode ?></h1>
    <p class="h4 mt-4"><?= $errorInfo ?></p>
    <a href="<?= AltoRouter::getRouterInstance()->generate('home') ?>" class="btn btn-secondary mt-5">Retourner à l'accueil</a>
</div>
