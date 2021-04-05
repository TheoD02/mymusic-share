<?php


namespace App\Controllers\FrontOffice\UserProfile;

use App\Models\Users;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\UserHelper;

class UserInformationsController extends BaseController
{
    /**
     * Vérifie que l'utilisateur soit connectée
     */
    public function __construct()
    {
        parent::__construct();
        if (!UserHelper::isAuthAsAnyRole())
        {
            FlashMessageService::addErrorMessage('Vous devez être connectez pour accéder à votre profil.');
            $this->redirectWithAltoRouter('home');
        }
    }

    /**
     * Affiche les informations de l'utilisateur
     */
    public function showProfileInformations(): void
    {
        $userInfo = (new Users())->setId(UserHelper::getUserID())->getUserById();
        $this->render('UserProfile/ProfileInformations', 'Vos informations personnel', [
            'userInfo' => $userInfo,
        ]);
    }

    /**
     * Affiche/Gère le formulaire d'édition des informations utilisateur
     */
    public function editProfileInformations(): void
    {
        $FV        = new FormValidator($_POST);
        $userModel = new Users();
        $userInfo  = $userModel->setId(UserHelper::getUserID())->getUserById();

        /** Si le formulaire est bien envoyée et que le token CSRF est valide */
        if ($FV->checkFormIsSend('editProfilInformationsAction'))
        {
            /** Vérifier les champ du formulaire */
            $FV->verify('username')->isNotEmpty()->maxLength(50);
            $FV->verify('email')->isNotEmpty()->isEmail();

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                $userModel->setUsername($FV->getFieldValue('username'))
                          ->setEmail($FV->getFieldValue('email'))->setIdUserRole($userInfo->getIdUserRole());

                /** Vérifier que l'email saisie n'est pas déjà en base de données */
                if ($userModel->checkEmailExist() && $userInfo->getEmail() !== $userModel->getEmail())
                {
                    $FV->forceError('email', 'Cet email est déjà utilisée.');
                }
                /** Vérifier que le nom d'utilisateur saisie n'est pas déjà en base de données */
                if ($userModel->checkUsernameExists() && $userInfo->getUsername() !== $userModel->getUsername())
                {
                    $FV->forceError('username', 'Ce nom d\'utilsateur est déjà utilisée.');
                }
                /** Si le formulaire est toujours valide */
                if ($FV->formIsValid())
                {
                    /** Mettre à jour les informations de l'utilisateur */
                    if ($userModel->updateUserInfoById())
                    {
                        FlashMessageService::addSuccessMessage('Votre profil à été mis à jour avec succès !');
                        $this->redirectWithAltoRouter('profileInformations');
                    }
                    else
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors de la mise à jour de vos données, Veuillez re-essayer.');
                        $this->redirectWithAltoRouter('profileInformations');
                    }
                }
            }
        }
        else if (!$FV->checkFormIsSend('editProfilInformations'))
        {
            $this->redirectWithAltoRouter('home');
        }

        $this->render('UserProfile/ProfileInformationsForm', 'Edition du profile', [
            'userInfo' => $userInfo,
        ]);
    }
}