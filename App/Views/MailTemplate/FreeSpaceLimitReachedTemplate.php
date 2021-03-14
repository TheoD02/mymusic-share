<?php

use Core\SpaceDiskHelper;

?>

La limite de stockage du serveur est quasiment atteinte.

L'espace disque disponible est de : <?= SpaceDiskHelper::getFreeSpace() ?> Go sur <?= SpaceDiskHelper::getTotalSpace() ?> Go. (<?= SpaceDiskHelper::getUsedSpace() ?> Go utilisée)

