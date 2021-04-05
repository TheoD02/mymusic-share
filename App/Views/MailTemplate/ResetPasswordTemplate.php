<?php

use App\Models\Users;


assert($obj instanceof Users);
?>
<p class="h2">Bonjour <?= $obj->getUsername() ?>,</p>

<p>Cet email à été envoyé suite à une demande de reinitialisation de mot de passe sur votre
   compte.</p>
<p>Veuillez cliquez sur le lien pour réinitialiser votre mot de passe.</p>
<a href="<?= AltoRouter::getRouterInstance()
                       ->generate('passwordResetForm', ['token' => $obj->getPasswordResetToken()], true) ?>">
    Cliquez sur ce lien pour réinitialiser votre mot de passe.</a>
<span>ce lien est valide jusque le <?= $obj->getFormattedPasswordResetExpire() ?></span>