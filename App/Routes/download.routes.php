<?php
$router->map('GET', '/download-track/[*:hash]', 'DownloadController@downloadTrack', 'downloadTrack');
$router->map('POST', '/download-list', 'DownloadController@createAndDownloadZip', 'downloadListZip');