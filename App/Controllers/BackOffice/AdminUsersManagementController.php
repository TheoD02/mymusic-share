<?php

namespace App\Controllers\BackOffice;

use App\Models\Roles;
use App\Models\Users;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;
use Core\FlashMessageService;
use Core\Form\FormValidator;

class AdminUsersManagementController extends BaseAdminController
{
    /**
     * Liste les utilisateurs
     *
     * @throws \Exception
     */
    public function listUsers(): void
    {
        $usersList = (new Users())->getUsersList();
        $this->render('Users/MembersList', 'Liste des membres', [
            'usersList' => $usersList,
        ], BaseView::BACK_OFFICE_PATH);
    }

    public function editUser(int $id): void
    {
        $FV        = new FormValidator($_POST);
        $userModel = new Users();
        $userInfo  = $userModel->setId($id)->getUserById();
        $rolesList = (new Roles)->getRolesList();
        if ($FV->checkFormIsSend('editUserAction'))
        {

            $FV->verify('lastName')->isNotEmpty()->isAlphaNumeric(['-']);
            $FV->verify('firstName')->isNotEmpty()->isAlphaNumeric(['-']);
            $FV->verify('email')->isEmail();
            $FV->verify('id_userRole')->isValidSelect()->isInt();

            if ($FV->formIsValid())
            {
                $userModel->setLastName($FV->getFieldValue('lastName'))
                          ->setFirstName($FV->getFieldValue('lastName'))
                          ->setEmail($FV->getFieldValue('email'))
                          ->setIdUserRole($FV->getFieldValue('id_userRole'));

                if ($userModel->checkEmailExist() && $userInfo->getEmail() !== $userModel->getEmail())
                {
                    $FV->forceError('email', 'Cet email est déjà utilisée.');
                }
                if ($FV->formIsValid())
                {
                    if ($userModel->updateUserInfoById())
                    {
                        FlashMessageService::addSuccessMessage('Utilisateur mis à jour avec succès !');
                        $this->redirectWithAltoRouter('adminUsersList');
                    }
                    else
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors de la mise à jour de l\'utilisateur.');
                    }
                }
            }
        }
        else if (!$FV->checkFormIsSend('editUser'))
        {
            $this->redirectWithAltoRouter('home');
        }

        $this->render('Users/MemberForm', 'Editer user', [
            'submitButtonName'  => 'editUserAction',
            'submitButtonValue' => 'Enregistrer les modifications',
            'userInfo'          => $userInfo,
            'rolesList'         => $rolesList,
        ], BaseView::BACK_OFFICE_PATH);
    }


    /**
     * Supprime un utilisateur
     *
     * @param int $id
     */
    public function deleteUser(int $id): void
    {
        $FV = new FormValidator($_POST);
        if ($FV->checkFormIsSend('deleteUserAction'))
        {
            if ($id == $_SESSION['user']['id'])
            {
                FlashMessageService::addErrorMessage('Impossible de supprimer le compte sur lequel vous êtes actuellement connecté.');
                $this->redirectWithAltoRouter('adminUsersList');
            }
            $user = new Users();
            $user->setId($id);
            if ($user->deleteUserById())
            {
                FlashMessageService::addSuccessMessage('Utilisateur supprimé avec succès !');
            }
            else
            {
                FlashMessageService::addErrorMessage('Une erreur est survenue lors de la suppression de l\'utilisateur, veuillez re-essayer');
            }
            $this->redirectWithAltoRouter('adminUsersList');
        }
        $this->redirectWithAltoRouter('home');
    }
}