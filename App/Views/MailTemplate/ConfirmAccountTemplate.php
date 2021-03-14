<?php

use App\Models\Users;

assert($obj instanceof Users);
?>
<h1>Bonjour, <?= $obj->getLastname() . ' ' . $obj->getFirstname() . '.' ?></h1>

<p>Veuillez confirmer votre compte avec ce lien :
    <a href="<?=
    AltoRouter::getRouterInstance()->generate('accountConfirmation', [
        'token' => $obj->getConfirmationToken(),
        'email' => $obj->getEmail(),
    ], true) ?>">Cliquez pour ouvrir le lien de confirmation</a>
</p>