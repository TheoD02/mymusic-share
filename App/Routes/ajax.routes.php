<?php

$router->map('POST', '/ajax/track/add-listen/[a:hash]', NAMESPACE_AJAX . 'AjaxPlayerController@addListenOnTrack', 'ajaxAddListenOnTrack');
$router->map('GET', '/ajax/get/category/[a:slug]/page-[i:currentPage]?', NAMESPACE_AJAX . 'AjaxPlayerController@getCategory', 'ajaxGetCategory');