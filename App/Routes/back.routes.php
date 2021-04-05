<?php

/**
 * Page d'accueil admin
 */
$router->map('GET', '/admin-panel/', NAMESPACE_BACKOFFICE . 'AdminHomeController@index', 'adminHome');

/**
 * Admin gestion des catégories
 */
/** Listes des catégories */
$router->map('GET', '/admin-panel/categories-list/[i:pageNumber]?', NAMESPACE_BACKOFFICE . 'AdminCategoriesManagementController@showCategoriesList', 'adminCategoriesList');
/** Ajouter une catégorie */
$router->map('POST', '/admin-panel/add-category', NAMESPACE_BACKOFFICE . 'AdminCategoriesManagementController@addCategory', 'adminAddCategory');

/** Editer une catégorie */
$router->map('POST', '/admin-panel/edit-category/[i:id]', NAMESPACE_BACKOFFICE . 'AdminCategoriesManagementController@editCategory', 'adminEditCategory');

/** Suppression d'une catégorie */
$router->map('POST', '/admin-panel/delete-category/[i:id]', NAMESPACE_BACKOFFICE . 'AdminCategoriesManagementController@deleteCategory', 'adminDeleteCategory');


/**
 * Gestion des artistes
 */
$router->map('GET', '/admin-panel/artists-list/[i:pageNumber]?', NAMESPACE_BACKOFFICE . 'AdminArtistsManagementController@showArtistsList', 'adminArtistsList');
$router->map('POST', '/admin-panel/edit-artist/[i:id]', NAMESPACE_BACKOFFICE . 'AdminArtistsManagementController@editArtist', 'adminEditArtist');
$router->map('POST', '/admin-panel/delete-artist/[i:id]', NAMESPACE_BACKOFFICE . 'AdminArtistsManagementController@deleteArtist', 'adminDeleteArtist');
$router->map('POST', '/admin-panel/add-artist', NAMESPACE_BACKOFFICE . 'AdminArtistsManagementController@addArtist', 'adminAddArtist');

/**
 * Gestion des utilisateurs
 */
$router->map('GET', '/admin-panel/users-list/[i:pageNumber]?', NAMESPACE_BACKOFFICE . 'AdminUsersManagementController@listUsers', 'adminUsersList');
$router->map('POST', '/admin-panel/add-user', NAMESPACE_BACKOFFICE . 'AdminUsersManagementController@addUser', 'adminAddUser');
$router->map('POST', '/admin-panel/edit-user/[i:id]', NAMESPACE_BACKOFFICE . 'AdminUsersManagementController@editUser', 'adminEditUser');
$router->map('POST', '/admin-panel/delete-user/[i:id]', NAMESPACE_BACKOFFICE . 'AdminUsersManagementController@deleteUser', 'adminDeleteUser');

/**
 * Gestion des musiques
 */
$router->map('GET', '/admin-panel/musics-list/[i:pageNumber]?', NAMESPACE_BACKOFFICE . '\AdminMusicsManagementController@showMusicList', 'adminMusicsList');
$router->map('GET', '/admin-panel/pending-musics-list/[i:pageNumber]?', NAMESPACE_BACKOFFICE . '\AdminMusicsManagementController@showPendingMusicsList', 'adminPendingMusicsList');
/** Ajouter une musique */
$router->map('POST', '/admin-panel/upload-music', NAMESPACE_BACKOFFICE . '\AdminMusicsManagementController@addMusic', 'adminUploadMusic');
/** Editer une musique */
$router->map('POST', '/admin-panel/edit-music/[i:id]', NAMESPACE_BACKOFFICE . '\AdminMusicsManagementController@editMusic', 'adminEditMusic');
/** Supprimer une musique */
$router->map('POST', '/admin-panel/delete-music/[i:id]', NAMESPACE_BACKOFFICE . '\AdminMusicsManagementController@deleteMusicAction', 'adminMusicDelete');