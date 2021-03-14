<?php

use App\Models\Categories;

/** @var Categories[]|false $categoriesList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1 class="text-center mb-4 align-self-center">Listes des catégories</h1>
            <div id="categories">
                <div class="row">
                    <?php if (!empty($categoriesList)) : ?>
                    <div class="col-md-12 d-flex flex-row-reverse">
                        <form method="get">
                            <div class="form-group">
                                <label for="numberOfElementPerPage">Nombre d'éléments par page</label>
                                <select onchange="this.form.submit()" name="numberOfElementPerPage" class="form-select" id="numberOfElementPerPage">
                                    <?php for ($numberOfElements = 8; $numberOfElements <= 48; $numberOfElements += 8): ?>
                                        <option value="<?= $numberOfElements ?>" <?= isset($_GET['numberOfElementPerPage']) && $_GET['numberOfElementPerPage'] == $numberOfElements ? 'selected' : '' ?>><?= $numberOfElements ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <?php $nb = $_GET['numberOfElementPerPage'] ?? 8 ?>
                    <?php foreach ($categoriesList as $category) : ?>
                        <div class="card-categorie col-6 col-sm-4 col-md-3 my-3">
                            <a href="<?= AltoRouter::getRouterInstance()
                                                   ->generate('category', ['slug' => $category->getSlug(), 'currentPage' => 1]) ?>"
                               class="d-block text-center my-auto" title="Voir la catégorie <?= $category->getName() ?>">
                                <div class="card px-2 py-2 rounded shadow h-100">
                                    <img src="<?= $category->getImgPath() ?>" alt="Image de la catégorie <?= $category->getName() ?>"
                                         title="Image de la catégorie <?= $category->getName() ?>" class="img-fluid">

                                    <a href="<?= AltoRouter::getRouterInstance()
                                                           ->generate('category', ['slug' => $category->getSlug(), 'currentPage' => 1]) ?>"
                                       class="btn btn-outline-warning mx-0 mx-lg-4 mt-3 mb-1 mt-auto">
                                        Voir la catégorie<br><?= $category->getName() ?>
                                    </a>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <nav aria-label="Page navigation example" class="my-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
                <?php else: ?>
                    <p class="h2">Aucune catégorie n'est disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>