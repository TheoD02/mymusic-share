<?php

use Core\CSRFHelper;
use Core\Form\FormHelper;
use Core\Form\FormValidator;
FormHelper::setFormData($_POST);
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5">
            <form action="" method="POST">
                <fieldset class="border shadow rounded-3 p-5">
                    <legend>Nous contacter</legend>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('email') ?>" name="email" id="email" placeholder="Email" value="<?= FormHelper::getSanitizedFieldValue('email') ?>">
                        <label for="email" class="form-label">Email</label>
                        <?= FormValidator::getOneErrorHTML('email'); ?>
                    </div>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('subject') ?>" name="subject" id="subject" placeholder="Sujet" value="<?= FormHelper::getSanitizedFieldValue('subject') ?>">
                        <label for="subject" class="form-label">Sujet</label>
                        <?= FormValidator::getOneErrorHTML('subject'); ?>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Saisissez votre message</label>
                        <textarea class="form-control <?= FormValidator::fieldIsValid('message') ?>" placeholder="Saisissez votre message" id="message" name="message"
                                  style="height: 125px;"><?= FormHelper::getSanitizedFieldValue('message') ?></textarea>
                        <?= FormValidator::getOneErrorHTML('message'); ?>
                    </div>
                    <?= CSRFHelper::generateCsrfHiddenInput() ?>
                    <div class="form-group my-3 d-flex justify-content-center">
                        <input type="submit" class="btn btn-primary" name="contactForm" value="Envoyer le message">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>