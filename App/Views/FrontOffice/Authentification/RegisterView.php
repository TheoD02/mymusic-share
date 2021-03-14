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
                    <div class="form-floating my-3 col-md-6">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('lastname') ?>" name="lastname" id="lastname"
                               placeholder="Nom" value="<?= FormHelper::getSanitizedFieldValue('lastname') ?>">
                        <label for="lastname" class="form-label ps-4">Nom</label>
                        <?= FormValidator::getOneErrorHTML('lastname') ?>
                    </div>
                    <div class="form-floating my-3 col-md-6">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('firstname') ?>" name="firstname" id="firstname"
                               placeholder="Prénom" value="<?= FormHelper::getSanitizedFieldValue('firstname') ?>">
                        <label for="firstname" class="form-label ps-4">Prénom</label>
                        <?= FormValidator::getOneErrorHTML('firstname') ?>
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
                <fieldset class="row border-bottom shadow-sm rounded mx-0 py-3">
                    <legend>Adresse</legend>
                    <div id="addressSearchContainer" class="form-floating my-3">
                        <input type="text" class="form-control" name="addressSearch" id="addressSearch" autocomplete="off"
                               placeholder="Rechercher une adresse" value="<?= FormHelper::getSanitizedFieldValue('addressSearch') ?>">
                        <label for="addressSearch" class="form-label ps-4">Rechercher une adresse</label>
                        <div id="addressSearchHelp" class="form-text">Veuillez saisir votre adresse postal.</div>
                    </div>
                    <div id="addressCheckContainer" class="py-3 row">
                        <p id="addressCheckTitle" class="h5">Veuillez remplir le champ ci-dessus :</p>
                        <div class="form-group my-3 col-2">
                            <label for="street_number">N°</label>
                            <input type="text" name="street_number" id="street_number" placeholder="N°"
                                   class="form-control <?= FormValidator::fieldIsValid('street_number') ?>"
                                   value="<?= FormHelper::getSanitizedFieldValue('street_number') ?>"<?= isset($_POST['street_number']) ?: 'disabled' ?>>
                        </div>
                        <div class="form-group my-3 col-10">
                            <label for="route">Nom de rue</label>
                            <input type="text" name="route" id="route" placeholder="Nom de rue"
                                   class="form-control <?= FormValidator::fieldIsValid('route') ?>"
                                   value="<?= FormHelper::getSanitizedFieldValue('route') ?>"<?= isset($_POST['route']) ?: 'disabled' ?>>
                        </div>
                        <div class="form-group my-3 col-md-12">
                            <label for="locality">Ville</label>
                            <input type="text" name="locality" id="locality" placeholder="Ville"
                                   class="form-control <?= FormValidator::fieldIsValid('locality') ?>"
                                   value="<?= FormHelper::getSanitizedFieldValue('locality') ?>"<?= isset($_POST['locality']) ?: 'disabled' ?>>
                        </div>
                        <div class="form-group my-3 col-md-6">
                            <label for="postal_code">Code postal</label>
                            <input type="text" name="postal_code" id="postal_code" placeholder="Code postal"
                                   class="form-control <?= FormValidator::fieldIsValid('postal_code') ?>"
                                   value="<?= FormHelper::getSanitizedFieldValue('postal_code') ?>"<?= isset($_POST['postal_code']) ?: 'disabled' ?>>
                        </div>
                        <div class="form-group my-3 col-md-6">
                            <label for="country">Pays</label>
                            <input type="text" name="country" id="country" placeholder="Pays"
                                   class="form-control <?= FormValidator::fieldIsValid('country') ?>"
                                   value="<?= FormHelper::getSanitizedFieldValue('country') ?>"<?= isset($_POST['country']) ?: 'disabled' ?>>
                        </div>
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
<script src="/assets/js/register.front.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzrQ26XJeKLYHS_5R102Lw549KiiSRroI&callback=initAutocomplete&libraries=places&v=weekly"></script>
