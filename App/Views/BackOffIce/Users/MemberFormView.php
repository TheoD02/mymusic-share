<?php

use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($userInfo ?? $_POST); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="post" class="border shadow rounded my-4" id="registerForm">
                <fieldset class="row border-bottom rounded shadow-sm mx-0 px-2 py-3">
                    <legend>Identité</legend>
                    <div class="form-floating my-3 col-md-6">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('lastName') ?>" name="lastName" id="lastName"
                               placeholder="Nom" value="<?= FormHelper::getSanitizedFieldValue('lastName') ?>">
                        <label for="lastName" class="form-label ps-4">Nom</label>
                        <?= FormValidator::getOneErrorHTML('lastName') ?>
                    </div>
                    <div class="form-floating my-3 col-md-6">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('firstName') ?>" name="firstName" id="firstName"
                               placeholder="Prénom" value="<?= FormHelper::getSanitizedFieldValue('firstName') ?>">
                        <label for="firstName" class="form-label ps-4">Prénom</label>
                        <?= FormValidator::getOneErrorHTML('firstName') ?>
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
                </fieldset>
                <fieldset class="row border-bottom rounded mx-0 shadow-sm py-3">
                    <legend>Rôle</legend>
                    <div class="form-floating my-3">
                        <select name="id_userRole" id="id_userRole" class="form-select">
                            <option value="default">Choisissez un rôle</option>
                            <?php foreach ($rolesList as $role): ?>
                                <option value="<?= $role->getId() ?>" <?= $role->getId() === (int)FormHelper::getSanitizedFieldValue('id_userRole') ? 'selected' : '' ?>>
                                    <?= $role->getName() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="id_userRole" class="form-label ps-4">Rôle</label>
                        <?= FormValidator::getOneErrorHTML('id_userRole') ?>
                    </div>
                </fieldset>
                <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                <div class="form-group my-3 d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary" name="<?= $submitButtonName ?>" value="<?= $submitButtonValue ?? 'Enregistrer' ?>">
                </div>
            </form>
        </div>
    </div>
</div>