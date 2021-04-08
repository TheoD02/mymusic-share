<?php

use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($categoryInfo ?? $_POST);
?>


<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <form action="" method="post" enctype="multipart/form-data" id="categoryForm">
                <fieldset class="border shadow rounded-3 p-5">
                    <legend><?= $formTitle ?></legend>
                    <?= FormValidator::getMessageFormNotSendHTML() ?>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('name') ?>" name="name" id="name"
                               placeholder="Nom de la catégorie" value="<?= FormHelper::getSanitizedFieldValue('name') ?>">
                        <label for="name" class="form-label">Nom de la catégorie</label>
                        <?= FormValidator::getOneErrorHTML('name') ?>
                    </div>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('slug') ?>" name="slug" id="slug" placeholder="Slug"
                               value="<?= FormHelper::getSanitizedFieldValue('slug') ?>">
                        <label for="slug" class="form-label">Slug</label>
                        <?= FormValidator::getOneErrorHTML('slug') ?>
                    </div>
                    <div class="form-group">
                        <?php if (!empty(FormHelper::getSanitizedFieldValue('imgPath'))) : ?>
                            <p class="text-center">Image de catégorie actuel : </p>
                            <img src="<?= FormHelper::getSanitizedFieldValue('imgPath') ?>" class="img-fluid d-flex mx-auto" alt="">
                        <?php else: ?>
                            <label for="imgPath">Image de la catégorie (*.png ou *.jpg)</label>
                            <input type="file" class="form-control <?= FormValidator::fieldIsValid('imgPath') ?>" name="imgPath" id="imgPath" accept="image/jpeg, image/png">
                            <?= FormValidator::getOneErrorHTML('imgPath') ?>
                        <?php endif; ?>
                    </div>
                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                    <div class="form-group my-3 d-flex justify-content-center">
                        <input type="submit" class="btn btn-primary" value="<?= $formSubmitValue ?>" name="<?= $formSubmitName ?>">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>