<?php

use App\Models\UsersDownloadLists;

/** @var UsersDownloadLists[]|false $downloadsList */
?>
<?php if (!empty($userDownloadsList)) : ?>
    <div class="col-md-4">
        <label for="downloadListSelector">Liste de téléchargement actuellement sélectionner :</label>
        <select class="form-select" name="downloadListSelector" id="downloadListSelector">
            <option value="default">Sélectionner une liste de téléchargement</option>
            <?php foreach ($userDownloadsList as $downloadListInfo): ?>
                <option value="<?= $downloadListInfo->getId() ?>" <?= ($_SESSION['user']['selectedDownloadListId'] ?? 0) === $downloadListInfo->getId() ? 'selected' : '' ?>><?= $downloadListInfo->getName() ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>