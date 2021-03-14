<?php
/**
 * Accueil
 */
$router->map('GET', '/', NAMESPACE_FRONTOFFICE . 'HomeController@index', 'home');

/**
 * Liste des catégories
 */
$router->map('GET', '/categories', NAMESPACE_FRONTOFFICE . 'Categories\\CategoriesListController@showCategoriesList', 'categoriesList');

/**
 * Détails d'une catégorie
 */
$router->map('GET', '/category/[slug:slug]/page-[i:currentPage]', NAMESPACE_FRONTOFFICE . 'Categories\\CategoryController@showCategory', 'category');

/**
 * Nouveautés
 */
$router->map('GET', '/new-release', NAMESPACE_FRONTOFFICE . 'NewReleaseController@showNewRelease', 'newRelease');


/**
 * Contact
 */
$router->map('GET', '/contact', NAMESPACE_FRONTOFFICE . 'ContactController@showContactForm', 'contact');
$router->map('GET', '/contact', NAMESPACE_FRONTOFFICE . 'ContactController@contactAction');