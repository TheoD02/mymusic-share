<?php


namespace App\Controllers\FrontOffice\Authentification;


use App\Models\Users;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\Mailer;
use Core\Security;
use Core\UserHelper;

class PasswordResetController extends BaseController
{
    /**
     * PasswordResetController constructor.
     *  Vérifie que l'utilisateur ne soit pas connecté.
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
     * Affiche le formulaire de demande de reinitialisation
     *
     * @param string $email
     *
     * @throws \Exception
     */
    public function showSendResetTokenForm(string $email = ''): void
    {
        $this->render('Authentification/RequestResetPassword', 'Demande de reinitialisation de mot de passe', ['email' => $email]);
    }

    /**
     * Génère le token et envoi l'email si il existe en base de données
     */
    public function sendResetTokenAction(): void
    {
        $FV = new FormValidator($_POST);
        /** Vérifie que le formulaire est envoyé et que le token CSRF est valide. */
        if ($FV->checkFormIsSend('resetPasswordRequestForm'))
        {
            /** Vérification du champ */
            $FV->verify('email')->isEmail();

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                /** Création d'une instance d'Users, on défini son email et on récupère un utilisateur avec cet email si non false. */
                $userInfo = (new Users())->setEmail($FV->getFieldValue('email'))->getUserByEmail();
                /** Si un utilisateur existe avec l'email */
                if ($userInfo !== false)
                {
                    /**
                     * Stocker le token pour cet utilisateur et définir le nombre d'heure de validité
                     * du lien de reinitialisation du mot de passe à partir de la date actuel
                     */
                    $token = Security::generateToken(100);
                    $userInfo->setPasswordResetToken($token)->setPasswordResetExpire(1);
                    /** Si une erreur survient lors de la mise à jour du token et la date d'expiration en base de données */
                    if (!$userInfo->updatePasswordResetById())
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors de l\'envoi du lien de reinitialisation.');
                        $this->redirectWithAltoRouter('requestPasswordReset');
                    }

                    /** Envoyer l'email avec le lien de reinitialisation du mot de passe */
                    $mailer = new Mailer();
                    $mailer->setSubject('Demande de reinitialisation de mot de passe')
                           ->setTo($userInfo->getEmail());
                    /** Si une erreur survient lors de l'envoi de l'email */
                    if (!$mailer->sendMail('ResetPassword', $userInfo))
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors de l\'envoi de l\'email. Veuillez ressayer.');
                        $this->redirectWithAltoRouter('requestPasswordReset');
                    }
                }
                /** Tout c'est bien passer, on redirige sur la page de connexion */
                FlashMessageService::addSuccessMessage('Un email vous à été envoyé avec un lien pour modifier votre mot de passe.');
                $this->redirectWithAltoRouter('login');
            }
        }
        $this->render('Authentification/RequestResetPassword', 'Demande de nouveau mot de passe');
    }

    /**
     * Affiche le formulaire de reinitialisation de mot de passe
     *
     * @param string $token
     *
     * @throws \Exception
     */
    public function showPasswordResetForm(string $token): void
    {
        /** Si le token passer en paramètre n'est pas de la longueur attendu */
        if (strlen($token) !== 100)
        {
            FlashMessageService::addErrorMessage('Le lien de reinitialisation n\'est pas valide.');
            $this->redirectWithAltoRouter('home');
        }
        else
        {
            /** Récupérer l'utilisateur correspondant au token */
            $userInfo = (new Users())->setPasswordResetToken($token)->getUserByPasswordResetToken();
            /** Si on ne trouve pas d'utilisateur correspondant */
            if ($userInfo === false)
            {
                FlashMessageService::addErrorMessage('Le lien demander n\'est pas reconnu.');
                $this->redirectWithAltoRouter('login');
            }
        }
        $this->render('Authentification/ResetPasswordForm', 'Reinitialisation du mot de passe.');
    }

    /**
     * Affiche le formulaire de reinitialisation de mot de passe
     */
    public function resetPasswordForm(string $token): void
    {
        /** Vérifie que le token contient bien le nombre de caractères attendu */
        if (strlen($token) !== 100)
        {
            FlashMessageService::addErrorMessage('Le lien de reinitialisation n\'est pas valide.');
        }
        else
        {
            /** Créer une instance de Users défini le token passer en paramètre et rechercher un utilisateur avec ce token */
            $userInfo = (new Users())->setPasswordResetToken($token)->getUserByPasswordResetToken();

            /** L'utilisateur existe en base de données */
            if ($userInfo !== false)
            {
                /** Si la date d'expiration est dépasser */
                if ($userInfo->getPasswordResetExpire() < (new \DateTime())->format('Y-m-d H:i:s'))
                {
                    FlashMessageService::addErrorMessage('Le lien de reinitialisation est expiré.');
                }
                /** Si le token donnée en paramètre et celui en base de données. */
                else if ($token === $userInfo->getPasswordResetToken())
                {
                    $FV = new FormValidator($_POST);
                    /** Vérifie que le formulaire est envoyé et que le token CSRF est valide. */
                    if ($FV->checkFormIsSend('resetPasswordForm'))
                    {
                        /** Vérification des champs */
                        $FV->verify('password')->isNotEmpty()->passwordConstraintRegex()
                           ->passwordCorrespondTo('confirmPassword')->needReEntry();
                        $FV->verify('confirmPassword')->needReEntry();

                        /** Si le formulaire est valide */
                        if ($FV->formIsValid())
                        {
                            /** On stock le mot de passe entrer par l'utilisateur dans l'objet */
                            $userInfo->setPassword(password_hash($FV->getFieldValue('password'), PASSWORD_BCRYPT));
                            /** Mettre à jour le mot de passe, si un problème survient on indique une erreur. */
                            if ($userInfo->updateUserPasswordById())
                            {
                                FlashMessageService::addSuccessMessage('Mot de passe mis à jour avec succès, vous pouvez désormais vous connecté.');
                                $this->redirect(\AltoRouter::getRouterInstance()
                                                           ->generate('login'));
                            }
                            else
                            {
                                FlashMessageService::addErrorMessage('Une erreur est survenue lors de la mise à jour de votre mot de passe, veuillez ressayer.');
                            }
                        }
                    }
                }
            }
            else
            {
                FlashMessageService::addErrorMessage('Le lien de reinitialisation n\'est pas valide.');
            }
        }
        $this->render('Authentification/ResetPasswordForm', 'Reinitialisation du mot de passe.');
    }
}