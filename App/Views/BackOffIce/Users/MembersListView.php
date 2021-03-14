<?php

use App\Models\Users;

/** @var Users[]|false $usersList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Gestion des membres</h1>
            <table class="table table-striped table-dark text-center">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Statut du compte</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersList as $user) : ?>
                        <tr class="align-middle">
                            <td><?= $user->getLastName() ?></td>
                            <td><?= $user->getFirstName() ?></td>
                            <td><?= $user->getEmail() ?></td>
                            <td><?= $user->isConfirmed ? '<i class="mdi mdi-account-check text-success"></i>' : '<i class="mdi mdi-clock-alert text-warning"></i>' ?></td>
                            <td>
                                <form action="<?= AltoRouter::getRouterInstance()->generate('adminEditUser', ['id' => $user->getId()]) ?>"
                                      method="POST">
                                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                    <button type="submit" name="editUser">
                                        <i class="mdi mdi-account-edit admin-icon"></i>
                                    </button>
                                </form>
                                <form action="<?= AltoRouter::getRouterInstance()
                                                            ->generate('adminDeleteUser', ['id' => $user->getId()]) ?>"
                                      method="POST">
                                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                    <button type="submit" name="deleteUserAction">
                                        <i class="mdi mdi-delete admin-icon"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>