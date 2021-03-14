<?php

use App\Models\Users;
use Core\Form\FormHelper;
use Core\Form\FormValidator;

/** @var Users|false $userInfo */
FormHelper::setFormData(count($_POST) > 2 ? $_POST : $userInfo);
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Edition de votre profile</h1>
            <form action="" method="POST">
                <fieldset>
                    <legend>Informations personnel</legend>
                    <div class="row">
                        <div class="form-floating col-md-6 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('lastName') ?>" name="lastName"
                                   id="lastName" placeholder="Nom"
                                   value="<?= FormHelper::getSanitizedFieldValue('lastName') ?>">
                            <label for="lastName">Nom</label>
                            <?= FormValidator::getOneErrorHTML('lastName') ?>
                        </div>
                        <div class="form-floating col-md-6 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('firstName') ?>" name="firstName" id="firstName" placeholder="Nom"
                                   value="<?= FormHelper::getSanitizedFieldValue('firstName') ?>">
                            <label for="firstName">Prénom</label>
                            <?= FormValidator::getOneErrorHTML('firstName') ?>
                        </div>
                        <div class="form-floating col-md-12 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('lastName') ?>" name="email" id="email" placeholder="Email"
                                   value="<?= FormHelper::getSanitizedFieldValue('email') ?>">
                            <label for="email">Email</label>
                            <?= FormValidator::getOneErrorHTML('email') ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Adresse</legend>
                    <div class="row">
                        <div class="form-floating col-2 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('houseNumber') ?>" name="houseNumber" id="houseNumber" placeholder="N° de rue"
                                   value="<?= FormHelper::getSanitizedFieldValue('houseNumber') ?>">
                            <label for="houseNumber">N° de rue</label>
                        </div>
                        <div class="form-floating col-10 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('address') ?>" name="address" id="address" placeholder="address"
                                   value="<?= FormHelper::getSanitizedFieldValue('address') ?>">
                            <label for="address">Adresse</label>
                            <?= FormValidator::getOneErrorHTML('address') ?>
                        </div>
                        <div class="form-floating col-md-6 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('city') ?>" name="city" id="city" placeholder="city"
                                   value="<?= FormHelper::getSanitizedFieldValue('city') ?>">
                            <label for="city">Ville</label>
                            <?= FormValidator::getOneErrorHTML('city') ?>
                        </div>
                        <div class="form-floating col-md-6 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('zipCode') ?>" name="zipCode" id="zipCode" placeholder="zipCode"
                                   value="<?= FormHelper::getSanitizedFieldValue('zipCode') ?>">
                            <label for="zipCode">Code postal</label>
                            <?= FormValidator::getOneErrorHTML('zipCode') ?>
                        </div>
                        <div class="form-floating my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('country') ?>" name="country" id="country" placeholder="country"
                                   value="<?= FormHelper::getSanitizedFieldValue('country') ?>">
                            <label for="country">Pays</label>
                            <?= FormValidator::getOneErrorHTML('country') ?>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group">
                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                    <input type="submit" name="editProfilInformationsAction" class="btn btn-primary" value="Enregistrer les modifications">
                </div>
            </form>
        </div>
    </div>
</div>
