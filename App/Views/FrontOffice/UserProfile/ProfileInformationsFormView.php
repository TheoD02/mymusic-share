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
                        <div class="form-floating col-md-12 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('username') ?>" name="username"
                                   id="username" placeholder="Nom d'utilisateur"
                                   value="<?= FormHelper::getSanitizedFieldValue('username') ?>">
                            <label for="username">Nom d'utilisateur</label>
                            <?= FormValidator::getOneErrorHTML('username') ?>
                        </div>
                        <div class="form-floating col-md-12 my-3">
                            <input type="text" class="form-control <?= FormValidator::fieldIsValid('email') ?>" name="email" id="email" placeholder="Email"
                                   value="<?= FormHelper::getSanitizedFieldValue('email') ?>">
                            <label for="email">Email</label>
                            <?= FormValidator::getOneErrorHTML('email') ?>
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
