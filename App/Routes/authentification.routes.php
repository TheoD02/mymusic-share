<?php

/**
 * Inscription
 */
$router->map('GET', '/register', NAMESPACE_FRONTOFFICE . 'Authentification\\RegisterController@showRegisterForm', 'register');
$router->map('POST', '/register', NAMESPACE_FRONTOFFICE . 'Authentification\\RegisterController@registerAction');

/**
 * Connexion
 */
$router->map('GET', '/login', NAMESPACE_FRONTOFFICE . 'Authentification\\LoginController@showLoginForm', 'login');
$router->map('POST', '/login', NAMESPACE_FRONTOFFICE . 'Authentification\\LoginController@loginAction');

/**
 * Vérification d'un compte
 */
$router->map('GET', '/account/verification/[a:token]/[*:email]', NAMESPACE_FRONTOFFICE . 'Authentification\\EmailConfirmationController@accountVerificationAction', 'accountConfirmation');
$router->map('GET', '/account/verification/resend-token/[*:email]-[i:id]', NAMESPACE_FRONTOFFICE . 'Authentification\\EmailConfirmationController@resendConfirmationToken', 'resendConfirmationToken');


/**
 * Reinitialisation du mot de passe
 */
/** Envoi */
$router->map('GET', '/password-reset/[*:email]?', NAMESPACE_FRONTOFFICE . 'Authentification\\PasswordResetController@showSendResetTokenForm', 'requestPasswordReset');
$router->map('POST', '/password-reset/[*:email]?', NAMESPACE_FRONTOFFICE . 'Authentification\\PasswordResetController@sendResetTokenAction');
/** Formulaire de modification */
$router->map('GET', '/reset-password/[a:token]', NAMESPACE_FRONTOFFICE . 'Authentification\\PasswordResetController@showPasswordResetForm', 'passwordResetForm');
$router->map('GET|POST', '/reset-password/[a:token]', NAMESPACE_FRONTOFFICE . 'Authentification\\PasswordResetController@resetPasswordForm');


/**
 * Déconnexion
 */
$router->map('GET', '/logout', NAMESPACE_FRONTOFFICE . 'Authentification\\LogoutController@logoutAction', 'logout');