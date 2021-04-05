<?php

$router->map('POST', '/ajax/track/add-listen/[a:hash]', NAMESPACE_AJAX . 'AjaxPlayerController@addListenOnTrack', 'ajaxAddListenOnTrack');
$router->map('GET', '/ajax/get/category/[*:slug]/page-[i:currentPage]', NAMESPACE_AJAX . 'AjaxPlayerController@getCategoryTrackList', 'ajaxGetCategory');

$router->map('POST', '/ajax/add-download-list', NAMESPACE_AJAX . 'AjaxDownloadList@addDownloadList', 'ajaxAddDownloadList');
$router->map('GET', '/ajax/get-download-list/[i:id]', NAMESPACE_AJAX . 'AjaxDownloadList@getDownloadListById', 'ajaxGetDownloadList');
$router->map('GET', '/ajax/select-download-list/[i:id]', NAMESPACE_AJAX . 'AjaxDownloadList@setDownloadListIdInSession');
$router->map('GET', '/ajax/add-track-to-download-list/[i:playlistId]/[*:trackHash]', NAMESPACE_AJAX . 'AjaxDownloadList@addTrackToDownloadList');