<?php

use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($_POST);
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <?= FormHelper::getSanitizedFieldValue('email') ?>
            <h3>Demande de remise à zéro du mot de passe</h3>
            <form action="" method="POST">
                <div class="form-floating my-3">
                    <input type="text" class="form-control <?= FormValidator::fieldIsValid('email') ?>" name="email" id="email"
                           placeholder="Email du compte" value="<?= FormHelper::getSanitizedFieldValue('email') ?? htmlentities($email) ?>">
                    <label for="email">Email du compte</label>
                    <?= FormValidator::getOneErrorHTML('email') ?>
                </div>
                <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                <div class="form-group my-3">
                    <input type="submit" class="btn btn-secondary" name="resetPasswordRequestForm" value="Confirmer la demande">
                </div>
            </form>
        </div>
    </div>
</div>