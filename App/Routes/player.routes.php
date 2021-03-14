<?php
/**
 * Renvoi le fichier audio
 */
$router->map('GET', '/listen/[*:hash]/[i:date]', 'PlayerController#listenMusic');