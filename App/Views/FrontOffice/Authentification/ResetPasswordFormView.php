<?php

use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($_POST);
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <h3>Reinitialisation de votre mot de passe</h3>
            <form action="" method="POST">
                <?= FormValidator::getAllGeneralError() ?>
                <div class="form-floating my-3">
                    <input type="password" class="form-control <?= FormValidator::fieldIsValid('password') ?>" name="password" id="password"
                           placeholder="Nouveau mot de passe">
                    <label for="password">Nouveau mot de passe</label>
                    <?= FormValidator::getOneErrorHTML('password') ?>
                </div>
                <div class="form-floating my-3">
                    <input type="text" class="form-control <?= FormValidator::fieldIsValid('confirmPassword') ?>" name="confirmPassword"
                           id="confirmPassword" placeholder="Confirmation du mot de passe">
                    <label for="confirmPassword">Confirmation du mot de passe</label>
                    <?= FormValidator::getOneErrorHTML('confirmPassword') ?>
                </div>
                <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                <div class="form-group my-3">
                    <input type="submit" class="btn btn-secondary" name="resetPasswordForm" value="Confirmer la demande">
                </div>
            </form>
        </div>
    </div>
</div>