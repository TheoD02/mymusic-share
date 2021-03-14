<?php
$router->map('GET', '/profile/current-subscription', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserCurrentSubscriptionController@showCurrentSubscription', 'profileCurrentSubscription');


$router->map('GET', '/profile/orders-history', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserOrdersHistoryController@showOrdersHistory', 'profileOrdersHistory');


$router->map('GET', '/profile/personal-informations', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserInformationsController@showProfileInformations', 'profileInformations');
$router->map('POST', '/profile/edit-personal-informations', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserInformationsController@editProfileInformations', 'editProfilInformations');


$router->map('GET', '/profile/download-lists', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserDownloadListsController@showDownloadLists', 'profileDownloadLists');


$router->map('GET', '/profile/send-music', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserSendMusicController@showSendMusicForm', 'profileSendMusic');
$router->map('POST', '/profile/send-music', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserSendMusicController@addMusicAction');