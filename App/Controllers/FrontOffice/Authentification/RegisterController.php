<?php


namespace App\Controllers\FrontOffice\Authentification;


use App\Models\Users;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\Mailer;
use Core\Security;
use Core\UserAuthHelper;
use Core\UserHelper;

class RegisterController extends BaseController
{
    /**
     * RegisterController constructor.
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
     * Affiche le formulaire d'inscription
     *
     * @throws \Exception
     */
    public function showRegisterForm(): void
    {
        $this->render('Authentification/Register', 'S\'enregistrer');
    }

    /**
     * Vérifie le formulaire d'inscription et ajoute l'utilisateur.
     *
     * En cas d'erreurs re-affiche le formulaire avec les erreurs commises.
     *
     * @throws \Exception
     */
    public function registerAction(): void
    {
        $FV = new FormValidator($_POST);
        /** On vérifie que le formulaire est bien envoyé et que le token CSRF est valide. */
        if ($FV->checkFormIsSend('registerForm'))
        {
            /** Vérification des champs */
            $FV->verify('username')->isNotEmpty()->maxLength(50);
            $FV->verify('email')->isNotEmpty()->isEmail();
            $FV->verify('password')->isNotEmpty()->passwordConstraintRegex()
               ->passwordCorrespondTo('confirmPassword')->needReEntry();
            $FV->verify('confirmPassword')->needReEntry();
            $FV->verify('rememberMe')->needToBeChecked()
               ->setCustomInvalidFeedback('Veuillez accepter les conditions.');

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                /** Création d'une instance d'Users */
                $userMdl = new Users();
                /** On stock les données entrée par l'utilisateur dans l'objet */
                $userMdl->setUsername($FV->getFieldValue('username'))
                        ->setEmail($FV->getFieldValue('email'))
                        ->setPassword(password_hash($FV->getFieldValue('password'), PASSWORD_BCRYPT));

                /** On vérifie si l'email n'est pas déjà enregistrée en base de données */
                if ($userMdl->checkEmailExist())
                {
                    $FV->forceError('email', 'Cet email est déjà utilisée.');
                }
                /** On vérifie si le nom d'utilisateur n'est pas déjà enregistrée en base de données */
                if ($userMdl->checkUsernameExists())
                {
                    $FV->forceError('username', 'Ce nom d\'utilisateur est déjà utilisée.');
                }
                /** Si toujours pas d'erreur après la vérification de l'email */
                if ($FV->formIsValid())
                {
                    /** Création du Token de confirmation */
                    $confirmationToken = Security::generateToken(100);
                    /** Stocker le token pour cet utilisateur et définir le nombre d'heure de validité du lien de confirmation à partir de la date actuel */
                    $userMdl->setConfirmationToken($confirmationToken)
                            ->setConfirmationTokenExpire(1);

                    /** Ajouter l'utilisateur en base de données */
                    if ($userMdl->addUser())
                    {
                        /** Envoyer un email de confirmation */
                        $mailer = new Mailer();
                        $mailer->setTo($userMdl->getEmail())
                               ->setSubject('Veuillez confirmer votre compte');

                        /** Si l'email c'est bien envoyé */
                        if ($mailer->sendMail('ConfirmAccount', $userMdl))
                        {
                            FlashMessageService::addSuccessMessage('Inscrit avec succès, veuillez vérifier vos emails pour confirmer votre compte.');
                            $this->redirectWithAltoRouter('login');
                        }
                        else
                        {
                            FlashMessageService::addErrorMessage('Une erreur est survenue lors de l\'envoi du email. Si le problème persiste veuillez contactez l\'administrateur du site.');
                        }
                    }
                    /** En cas de problème lors de l'ajout on signale que une erreur est survenue */
                    else
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors de votre enregistrement, veuillez recommencer.');
                    }
                }
            }
        }
        $this->render('Authentification/Register', 'Inscription');
    }
}