<?php


use App\Models\Categories;
use Core\PaginationService;

/** @var Categories[]|false $categoriesList */

$router = AltoRouter::getRouterInstance();
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-row-reverse my-3">
                <form action="<?= AltoRouter::getRouterInstance()->generate('adminAddCategory') ?>" method="post">
                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                    <button type="submit" name="addCategory" class="btn btn-primary">
                        Ajoute une catégorie
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-dark text-center">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Emplacement de l'image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($categoriesList) : ?>
                            <?php foreach ($categoriesList as $category) : ?>
                                <tr class="align-middle">
                                    <td><?= $category->getName() ?></td>
                                    <td><?= $category->getSlug() ?></td>
                                    <td>
                                        <img src="<?= $category->getImgPath() ?>" alt="Image de la catégorie <?= $category->getName() ?>"
                                             title="Image de la catégorie <?= $category->getName() ?>" class="me-2" style="height: 40px;">
                                    </td>
                                    <td>
                                        <form action="<?= $router->generate('adminEditCategory', ['id' => $category->getId()]) ?>" method="post">
                                            <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                            <button type="submit" name="editCategory">
                                                <i class="mdi mdi-folder-edit admin-icon"></i>
                                            </button>
                                        </form>
                                        <form action="<?= $router->generate('adminDeleteCategory', ['id' => $category->getId()]) ?>" method="POST">
                                            <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                            <button type="submit" name="deleteCategory">
                                                <i class="mdi mdi-delete admin-icon"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Pas de catégorie existante</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example" class="my-5">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="<?= AltoRouter::getRouterInstance()
                                                                 ->generate(AltoRouter::getRouterInstance()
                                                                                      ->match()['name'], ['pageNumber' => 1]) ?>"><?= 1 ?></a>
                    </li>
                    <?php foreach (PaginationService::getPagination() as $pageNumber) : ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= AltoRouter::getRouterInstance()
                                                                     ->generate(AltoRouter::getRouterInstance()
                                                                                          ->match()['name'], ['pageNumber' => $pageNumber]) ?>"><?= $pageNumber ?></a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (PaginationService::getTotalPages() > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= AltoRouter::getRouterInstance()
                                                                     ->generate(AltoRouter::getRouterInstance()
                                                                                          ->match()['name'], ['pageNumber' => PaginationService::getTotalPages()]) ?>"><?= PaginationService::getTotalPages() ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>