<?php

use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($_POST);
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <form action="" method="POST">
                <fieldset class="border shadow-lg rounded-3 p-5">
                    <legend>Connexion</legend>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('email') ?>" name="email" id="email"
                               placeholder="Email" value="<?= FormHelper::getSanitizedFieldValue('email') ?>">
                        <label for="email" class="form-label">Email</label>
                        <?= FormValidator::getOneErrorHTML('email') ?>
                    </div>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('password') ?>" name="password" id="password"
                               placeholder="Mot de passe" value="">
                        <label for="password" class="form-label">Mot de passe</label>
                        <?= FormValidator::getOneErrorHTML('password') ?>
                    </div>
                    <div class="form-group my-2 d-flex justify-content-center align-items-center">
                        <input type="checkbox" name="rememberMe" id="rememberMe" class="me-2">
                        <label for="rememberMe">Se souvenir de moi</label>
                    </div>
                    <div class="form-group my-2 d-flex justify-content-center align-items-center">
                        <a href="<?= AltoRouter::getRouterInstance()
                                               ->generate('requestPasswordReset', ['email' => FormHelper::getSanitizedFieldValue('email')]) ?>">
                            Mot de passe oublié ?
                        </a>
                    </div>
                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                    <div class="form-group my-3 d-flex justify-content-center">
                        <input type="submit" class="btn btn-primary" name="loginForm" value="Se connecter">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>