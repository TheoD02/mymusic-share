<?php
$router->map('GET', '/download-track/[*:hash]', 'DownloadController@downloadTrack', 'downloadTrack');