<?php

use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($_POST);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2 mt-5">
            <h1>Inscription</h1>
            <?= FormValidator::getAllGeneralError() ?>
            <form method="post" class="border shadow rounded my-4" id="registerForm">
                <fieldset class="row border-bottom rounded shadow-sm mx-0 px-2 py-3">
                    <legend>Identité</legend>
                    <div class="form-floating my-3 col-md-12">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('username') ?>" name="username" id="username"
                               placeholder="Nom" value="<?= FormHelper::getSanitizedFieldValue('username') ?>">
                        <label for="username" class="form-label ps-4">Nom d'utilisateur</label>
                        <?= FormValidator::getOneErrorHTML('username') ?>
                    </div>
                </fieldset>
                <fieldset class="row border-bottom rounded mx-0 shadow-sm py-3">
                    <legend>Informations de connexion</legend>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('email') ?>" name="email" id="email"
                               placeholder="Email" value="<?= FormHelper::getSanitizedFieldValue('email') ?>">
                        <label for="email" class="form-label ps-4">Email</label>
                        <?= FormValidator::getOneErrorHTML('email') ?>
                    </div>
                    <div class="form-floating my-3 col-md-6">
                        <input type="password" class="form-control <?= FormValidator::fieldIsValid('password') ?>" name="password" id="password"
                               placeholder="Mot de passe" value="">
                        <label for="password" class="form-label ps-4">Mot de passe</label>
                        <?= FormValidator::getOneErrorHTML('password') ?>
                    </div>
                    <div class="form-floating my-3 col-md-6">
                        <input type="password" class="form-control <?= FormValidator::fieldIsValid('confirmPassword') ?>" name="confirmPassword"
                               id="confirmPassword" placeholder="Confirmation du mot de passe" value="">
                        <label for="confirmPassword" class="form-label ps-4">Confirmation du mot de passe</label>
                        <?= FormValidator::getOneErrorHTML('confirmPassword') ?>
                    </div>
                </fieldset>
                <div class="form-group my-2 ">
                    <div class="d-flex justify-content-center align-items-center <?= FormValidator::fieldIsValid('rememberMe') ?>">
                        <input type="checkbox" name="rememberMe" id="rememberMe" class="me-2">
                        <label for="rememberMe">
                            J'accepte les conditions.
                            <a href="">(Voir les conditions)</a>
                        </label>
                    </div>
                    <?= FormValidator::getOneErrorHTML('rememberMe') ?>
                </div>
                <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                <div class="form-group my-3 d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary" name="registerForm" value="Confirmer l'inscription">
                </div>
            </form>
        </div>
    </div>
</div>
