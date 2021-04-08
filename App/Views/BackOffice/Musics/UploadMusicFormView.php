<?php

use App\Models\Categories;
use App\Models\MusicKey;
use Core\Form\FormHelper;
use Core\Form\FormValidator;

FormHelper::setFormData($musicInfo ?? $_POST);
/** @var MusicKey[]|false $musicKeyList */
/** @var Categories[]|false $categoriesList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <form action="" method="post" id="musicForm" enctype="multipart/form-data">
                <div class="row">
                    <?php if (!isset($musicInfo)) : ?>
                        <?php if (!isset($_POST['uploadMusicAction']) && !isset($tempFileName)) : ?>
                            <div id="mp3-upload">
                                <span>Veuillez sélectionner le fichier MP3 à téléversé.</span>
                                <div class="form-group my-3">
                                    <input type="file" class="form-control <?= FormValidator::fieldIsValid('musicFile') ?>" name="musicFile"
                                           id="musicFile">
                                    <?= FormValidator::getOneErrorHTML('musicFile') ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div id="current-uploaded">
                                <input type="hidden" name="tempMusicFile" id="tempMusicFile" value="<?= $tempFileName ?>">
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div id="mp3-informations"
                         class="mp3-informations container-fluid mt-5 <?= isset($_POST['uploadMusicAction']) || isset($musicInfo) ? '' : 'd-none' ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <h1>Edition des informations du fichier audio</h1>
                                <div class="row">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control <?= FormValidator::fieldIsValid('title') ?>" name="title" id="title"
                                               placeholder="Titre" value="<?= FormHelper::getSanitizedFieldValue('title') ?>">
                                        <label for="title">Titre</label>
                                        <?= FormValidator::getOneErrorHTML('title') ?>
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="artistsName">Artistes</label>
                                        <input type="text" class="form-control input-tags <?= FormValidator::fieldIsValid('artistsName') ?>"
                                               name="artistsName" id="artistsName" value="<?= FormHelper::getSanitizedFieldValue('artistsName') ?>"
                                               placeholder="Artistes">
                                        <?= FormValidator::getOneErrorHTML('artistsName') ?>
                                    </div>
                                    <div class="form-floating my-3 col-md-3 col-sm-6">
                                        <input type="text" class="form-control  <?= FormValidator::fieldIsValid('bpm') ?>" name="bpm" id="bpm"
                                               placeholder="BPM" value="<?= FormHelper::getSanitizedFieldValue('bpm') ?>">
                                        <label for="bpm">BPM</label>
                                        <?= FormValidator::getOneErrorHTML('bpm') ?>
                                    </div>
                                    <div class="form-floating my-3 col-md-3 col-sm-6">
                                        <select name="bitrate" id="bitrate" class="form-control  <?= FormValidator::fieldIsValid('bitrate') ?>">
                                            <option value="default" selected disabled>
                                                Sélectionner un bitrate
                                            </option>
                                            <option value="128" <?= FormHelper::getSanitizedFieldValue('bitrate') === '128' ? 'selected' : '' ?>>
                                                128 Kbit/s
                                            </option>
                                            <option value="256" <?= FormHelper::getSanitizedFieldValue('bitrate') === '256' ? 'selected' : '' ?>>
                                                256 Kbit/s
                                            </option>
                                            <option value="320" <?= FormHelper::getSanitizedFieldValue('bitrate') === '320' ? 'selected' : '' ?>>
                                                320 Kbit/s
                                            </option>
                                        </select>
                                        <label for="bitrate">Bitrate</label>
                                        <?= FormValidator::getOneErrorHTML('bitrate') ?>
                                    </div>
                                    <div class="form-floating my-3 col-md-3 col-sm-6">
                                        <select name="id_musicKey" id="id_musicKey"
                                                class="form-select <?= FormValidator::fieldIsValid('id_musicKey') ?>">
                                            <option value="default" selected disabled>
                                                Sélectionner une clé harmonique
                                            </option>
                                            <?php foreach ($musicKeyList as $keyInfo) : ?>
                                                <option value="<?= $keyInfo->getId() ?>" <?= FormHelper::getSanitizedFieldValue('id_musicKey') == $keyInfo->getId() ? 'selected' : '' ?>>
                                                    <?= $keyInfo->getMusicKey() ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="id_musicKey">Clé Harmonique</label>
                                        <?= FormValidator::getOneErrorHTML('id_musicKey') ?>
                                    </div>
                                    <div class="form-floating my-3 col-md-3 col-sm-6">
                                        <select name="id_categories" id="id_categories"
                                                class="form-select <?= FormValidator::fieldIsValid('id_categories') ?>">
                                            <option value="default" selected disabled>
                                                Sélectionner une catégorie
                                            </option>
                                            <?php foreach ($categoriesList as $categoryInfo) : ?>
                                                <option value="<?= $categoryInfo->getId() ?>" <?= $categoryInfo->getId() == FormHelper::getSanitizedFieldValue('id_categories') ? 'selected' : '' ?>>
                                                    <?= $categoryInfo->getName() ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="id_categories">Catégorie</label>
                                        <?= FormValidator::getOneErrorHTML('id_categories') ?>
                                    </div>
                                    <div class="form-floating my-3">
                                        <select name="isPending" id="isPending"
                                                class="form-select <?= FormValidator::fieldIsValid('isPending') ?>">
                                            <option value="default" selected disabled>
                                                Sélectionner l'état de statut de la musique
                                            </option>
                                            <option value="1" <?= (bool)FormHelper::getSanitizedFieldValue('isPending') == true ? 'selected' : '' ?>>
                                                En attente de vérification
                                            </option>
                                            <option value="0" <?= (bool)FormHelper::getSanitizedFieldValue('isPending') == false ? 'selected' : '' ?>>
                                                En ligne
                                            </option>
                                        </select>
                                        <label for="isPending">Statut de la musique</label>
                                        <?= FormValidator::getOneErrorHTML('isPending') ?>
                                    </div>
                                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                    <div class="form-group my-3">
                                        <input type="submit" class="btn btn-secondary" value="<?= $formButtonValue ?? 'Enregistrer' ?>"
                                               name="<?= $formButtonName ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>