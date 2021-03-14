<?php


namespace App\Controllers\FrontOffice\Authentification;


use App\Models\Users;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\Mailer;
use Core\Security;
use Core\UserAuthHelper;
use Core\UserHelper;

class EmailConfirmationController extends BaseController
{
    /**
     * EmailConfirmationController constructor.
     *
     *  Vérifie si l'utilisateur est déjà connecté
     *
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

    public function accountVerificationAction(string $token, string $email): void
    {

        /** Vérifie que le token contient bien le nombre de caractères */
        if (strlen($token) !== 100)
        {
            FlashMessageService::addErrorMessage('La confirmation de votre compte n\'est pas possible');
        }
        else
        {
            /** Créer une instance de Users défini l'email de l'utilisateur et rechercher un utilisateur avec cet email */
            $userInfo = (new Users())->setEmail($email)->getUserByEmail();

            /** Vérifier que l'utilisateur existe */
            if ($userInfo !== false)
            {
                /** Si le compte est déjà confirmé */
                if ($userInfo->isConfirmed())
                {
                    FlashMessageService::addWarningMessage('Ce compte est déjà confirmé.');
                }
                /** Si le la date d'expiration du token est expiré */
                else if ($userInfo->getConfirmationTokenExpire() < (new \DateTime())->format('Y-m-d H:i:s'))
                {
                    FlashMessageService::addWarningMessage('Le lien est expiré. <br><a href="' . \AltoRouter::getRouterInstance()
                                                                                                            ->generate('resendConfirmationToken', ['email' => $userInfo->getEmail(), 'id' => $userInfo->getId()]) . '">
                                        Cliquez ici pour recevoir un nouveau lien de confirmation<a/>');
                }
                /** Si le token de l'url et le token en base de données est le même */
                else if ($token === $userInfo->getConfirmationToken())
                {
                    /** On confirme le compte */
                    if ($userInfo->setAccountToConfirmedStatut())
                    {
                        FlashMessageService::addSuccessMessage('Compte confirmé avec succès');
                    }
                    else
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors de l\'activation de votre compte.');
                    }

                }
                /** L'utilisateur n'est pas vérifié, ou son token n'est plus valide. on lui propose d'en recevoir un nouveau */
                else
                {
                    FlashMessageService::addErrorMessage('Ce lien d\'activation n\'est pas valide.<br><a href="' . \AltoRouter::getRouterInstance()
                                                                                                                              ->generate('resendConfirmationToken', ['email' => $userInfo->getEmail(), 'id' => $userInfo->getId()]) . '">
                                        Cliquez ici pour recevoir un nouveau lien de confirmation<a/>');
                }
            }
            /** Si l'utilisateur n'existe pas */
            else
            {
                FlashMessageService::addErrorMessage('Ce lien d\'activation n\'existe pas.');
            }
        }
        $this->redirectWithAltoRouter('home');

    }

    public function resendConfirmationToken(string $email, int $id): void
    {
        /** Récupérer un utilisateur via son ID */
        $userInfo = (new Users())->setId($id)->getUserById();

        /** Si un utilisateur à été trouver */
        if ($userInfo !== false)
        {
            /** Si l'email en paramètre est le même que celui récupérer en base de données */
            if ($userInfo->getEmail() === $email)
            {
                /** Régénération d'un token */
                $token = Security::generateToken(100);
                $userInfo->setConfirmationToken($token)->setConfirmationTokenExpire(1);
                /** Mise à jour du token pour l'utilisateur via son ID */
                $userInfo->updateConfirmationTokenById();

                /** On renvoie un email avec le nouveau token */
                $mailer = new Mailer();
                $mailer->setTo($userInfo->getEmail())
                       ->setSubject('Veuillez confirmer votre compte');
                if ($mailer->sendMail('ConfirmAccount', $userInfo))
                {
                    FlashMessageService::addSuccessMessage('Un email avec un nouveau lien de confirmation vous à été envoyé.');
                    $this->redirectWithAltoRouter('login');
                }
                /** Si une erreur survient */
                else
                {
                    FlashMessageService::addSuccessMessage('Une erreur est survenue lors de l\'envoi du email. Si le problème persiste veuillez contactez l\'administrateur du site.');
                }
            }
        }
        /** Si pas d'utilisateur trouver ou l'email ne correspond a aucun utilisateur */
        FlashMessageService::addWarningMessage('Impossible d\'envoyer l\'email. Si le problème persiste veuillez contactez un administrateur.');
        $this->redirectWithAltoRouter('home');
    }
}