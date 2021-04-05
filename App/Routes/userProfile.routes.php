<?php
$router->map('GET', '/profile/personal-informations', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserInformationsController@showProfileInformations', 'profileInformations');
$router->map('POST', '/profile/edit-personal-informations', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserInformationsController@editProfileInformations', 'editProfilInformations');


$router->map('GET', '/profile/download-lists/[i:id]?', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserDownloadListsController@showDownloadList', 'profileDownloadLists');
$router->map('POST', '/profile/download-lists/delete', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserDownloadListsController@deleteDownloadList', 'profileDownloadListsDeleteAction');


$router->map('GET', '/profile/send-music', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserSendMusicController@showSendMusicForm', 'profileSendMusic');
$router->map('POST', '/profile/send-music', NAMESPACE_FRONTOFFICE . 'UserProfile\\UserSendMusicController@addMusicAction');