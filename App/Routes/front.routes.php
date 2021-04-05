<?php
/**
 * Accueil
 */
$router->map('GET', '/', NAMESPACE_FRONTOFFICE . 'HomeController@index', 'home');

/**
 * Liste des catégories
 */
$router->map('GET', '/categories/[i:pageNumber]?/[i:numberOfElementPerPage]?', NAMESPACE_FRONTOFFICE . 'Categories\\CategoriesListController@showCategoriesList', 'categoriesList');

/**
 * Détails d'une catégorie
 */
$router->map('GET', '/category/[slug:slug]/page-[i:currentPage]', NAMESPACE_FRONTOFFICE . 'Categories\\CategoryController@showCategory', 'category');

/**
 * Nouveautés
 */
$router->map('GET', '/new-release', NAMESPACE_FRONTOFFICE . 'NewReleaseController@showNewRelease', 'newRelease');

/**
 * TOP 50
 */
$router->map('GET', '/top-50/listened', NAMESPACE_FRONTOFFICE . 'TopFiftyController@showTopFiftyListened', 'top50Listened');
$router->map('GET', '/top-50/downloaded', NAMESPACE_FRONTOFFICE . 'TopFiftyController@showTopFiftyDownloaded', 'top50Downloaded');

/**
 * Contact
 */
$router->map('GET', '/contact', NAMESPACE_FRONTOFFICE . 'ContactController@showContactForm', 'contact');
$router->map('POST', '/contact', NAMESPACE_FRONTOFFICE . 'ContactController@contactAction');

/**
 * Contact
 */
$router->map('POST', '/search', NAMESPACE_FRONTOFFICE . 'TracksSearchController@searchTrack', 'searchTrack');