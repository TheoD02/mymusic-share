<?php $router = AltoRouter::getRouterInstance(); ?>
<ul class="nav justify-content-center shadow py-2 rounded">
    <li class="nav-item bg-secondary rounded shadow mx-1">
        <a class="nav-link" href="<?= $router->generate('profileInformations') ?>">Mes informations perso.</a>
    </li>
    <li class="nav-item bg-secondary rounded shadow mx-1">
        <a class="nav-link" href="<?= $router->generate('profileDownloadLists') ?>">Liste de téléchargement</a>
    </li>
    <li class="nav-item bg-secondary rounded shadow mx-1">
        <a class="nav-link" href="<?= $router->generate('profileSendMusic') ?>">Envoyer votre musique</a>
    </li>
</ul>