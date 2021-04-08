<?php

use Core\CSRFHelper;
use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($artistInfo ?? $_POST);
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form action="" method="post">
                <div class="form-floating my-3">
                    <input type="text" name="name" id="name" class="form-control <?= FormValidator::fieldIsValid('name') ?>"
                           placeholder="Nom de l'artiste" value="<?= FormHelper::getSanitizedFieldValue('name') ?>">
                    <label for="name">Nom de l'artiste</label>
                    <?= FormValidator::getOneErrorHTML('name') ?>
                </div>
                <?= CSRFHelper::generateCsrfHiddenInput() ?>
                <div class="form-group my-3">
                    <input type="submit" class="btn btn-primary" name="<?= $submitButtonName ?>" value="<?= $submitButtonValue ?? 'Enregistrer' ?>">
                </div>
            </form>
        </div>
    </div>
</div>