<?php


namespace App\Controllers\FrontOffice\Authentification;


use App\Models\Users;
use Core\Base\BaseController;
use Core\CSRFHelper;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\Security;
use Core\UserHelper;

class LoginController extends BaseController
{
    /**
     * Exécute le constructeur parent et vérifie que l'utilisateur ne soit pas connecté.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        if (UserHelper::isAuthAsAnyRole())
        {
            FlashMessageService::addWarningMessage('Vous êtes déjà connecté !');
            $this->redirectWithAltoRouter('home');
        }
    }

    /**
     * Affiche le formulaire de connexion
     *
     * @throws \Exception
     */
    public function showLoginForm(): void
    {
        $this->render('Authentification/Login', 'Connexion');
    }

    /**
     * Vérifie les entrée du formulaire de connexion et connecte l'utilisateur si les informations sont valide
     */
    public function loginAction(): void
    {
        $FV = new FormValidator($_POST);
        /** Vérifie que le formulaire est envoyé et que le token CSRF est valide. */
        if ($FV->checkFormIsSend('loginForm'))
        {
            /** Vérifie que le champ 'email' est bien un email */
            $FV->verify('email')->isEmail();
            /** Vérifie que le champ password n'est pas vide et qu'il soit redemander en cas d'erreur */
            $FV->verify('password')->isNotEmpty()->needReEntry();


            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                /** on créer une instance d'Users */
                $userMdl = new Users();
                /** On défini l'email et le mot de passe entrée par l'utilisateur */
                $userMdl->setEmail($FV->getFieldValue('email'))
                        ->setPassword($FV->getFieldValue('password'));
                /** On stock le résultat de la requête dans $userInfo, si l'utilisateur existe on à un objet [Users] si non false  */
                $userInfo = $userMdl->getUserByEmail();
                if ($userInfo !== false)
                {
                    /** Mot de passe entrer dans le formulaire de connexion par l'utilisateur */
                    $userPassword = $FV->getFieldValue('password');
                    /** Vérification que le mot de passe entré correspond à celui enregistrée en base de données */
                    if (password_verify($userPassword, $userInfo->getPassword()))
                    {
                        /** Si le compte de l'utilisateur n'est pas vérifié on lui indique
                         * qu'il doit le vérifié pour accéder a son compte */
                        if (!$userInfo->isConfirmed())
                        {
                            FlashMessageService::addWarningMessage('Veuillez confirmer votre compte pour pouvoir vous connecter, un email de vérification vous à été envoyé à votre inscription.<br><a href="' . \AltoRouter::getRouterInstance()
                                                                                                                                                                                                                            ->generate('resendConfirmationToken', ['email' => $userInfo->getEmail(), 'id' => $userInfo->getId()]) . '">Cliquez ici pour recevoir un nouveau lien</a>');
                            $this->redirectWithAltoRouter('login');
                        }
                        /** Si tout est bon on peu le connecter */
                        else
                        {
                            /** Si la checkbox "Se souvenir de moi" est cochée */
                            if ($FV->isChecked('rememberMe'))
                            {
                                /** Création d'un token de 255 caractères */
                                $rememberMeToken = substr(Security::generateToken(125) . (new \DateTime())->getTimestamp() . Security::generateToken(125), 0, 255);
                                $userInfo->setRememberMeToken($rememberMeToken);
                                /** Mise à jour du token en base de données */
                                if (!$userInfo->updateRememberMeTokenById())
                                {
                                    FlashMessageService::addWarningMessage('Une erreur est survenue lors de l\'envoi du nouveau token');
                                    $this->redirectWithAltoRouter('login');
                                }
                                /** Stockage du cookie avec le token */
                                setcookie('user_persist', $rememberMeToken, time() + 60 * 60 * 24 * 7, null, null, false, true);
                            }
                            /** Stocker des informations utilisateur en session (isAuth, role, nom, prénom) */
                            $_SESSION['user']['id']          = $userInfo->getId();
                            $_SESSION['user']['role']        = $userInfo->getIdUserRole();
                            $_SESSION['user']['username']    = $userInfo->getUsername();
                            $_SESSION['user']['canDownload'] = $userInfo->getRemainingDownload() === null ? false : true;

                            /**
                             * Régénérer le CSRF  (Cross-site request forgery) et l'id de session
                             */
                            (new CSRFHelper())->makeCsrfToken()->saveTokenInSession();
                            /** Régénérer l'id de session, pour éviter les attaque sur la session de l'utilisateur */
                            session_regenerate_id();

                            FlashMessageService::addSuccessMessage('Connecté avec succès');
                            $this->redirectWithAltoRouter('home');
                        }
                    }
                }
                /** Si l'email et/ou mot de passe n'est pas valide on affiche un message */
                $FV->forceError('email', 'Veuillez vérifier ce champ.');
                FlashMessageService::addErrorMessage('Veuillez saisir un email ou un mot de passe valide.');
            }
            /** Si l'email et/ou mot de passe n'est pas valide on affiche un message */
            else
            {
                $FV->forceError('email', 'Veuillez vérifier ce champ.');
                FlashMessageService::addErrorMessage('Veuillez saisir un email ou un mot de passe valide.');
            }
        }
        $this->render('Authentification/Login', 'Connexion');
    }
}