<?php

namespace App\Controllers\BackOffice;

use App\Models\Roles;
use App\Models\Users;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\PaginationService;

class AdminUsersManagementController extends BaseAdminController
{
    /**
     * Liste les utilisateurs
     *
     * @param int $pageNumber
     *
     * @throws \Exception
     */
    public function listUsers(int $pageNumber = 1): void
    {
        $userMdl = new Users();

        /** Défini les données de pagination (Nombre total éléments, Nombre d'éléments par page, Numéro de page courante) */
        PaginationService::setTotalNumberOfElements($userMdl->getTotalNumberOfUsers());
        PaginationService::setNumberOfElementsPerPage(10);
        PaginationService::setCurrentPage($pageNumber);
        PaginationService::calculate();

        /** Récupérer la liste des utilisateur */
        $usersList = $userMdl->getUsersList(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());
        $this->render('Users/MembersList', 'Liste des membres', [
            'usersList' => $usersList,
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Edition d'un utilisateur
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function editUser(int $id): void
    {
        $FV        = new FormValidator($_POST);
        $userModel = new Users();
        /** Récupération des information de l'utilisateur via son ID */
        $userInfo = $userModel->setId($id)->getUserById();
        /** Récupère la liste des roles */
        $rolesList = (new Roles)->getRolesList();

        /** Vérifie que le formulaire est bien envoyé et que le token CSRF est valide. */
        if ($FV->checkFormIsSend('editUserAction'))
        {
            /** Vérification des champs du formulaire */
            $FV->verify('username')->isNotEmpty()->maxLength(50);
            $FV->verify('email')->isEmail();
            $FV->verify('id_userRole')->isValidSelect()->isInt();

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                /** On stock les données entrée par l'utilisateur dans l'objet */
                $userModel->setUsername($FV->getFieldValue('username'))
                          ->setEmail($FV->getFieldValue('email'))
                          ->setIdUserRole($FV->getFieldValue('id_userRole'));

                /**
                 * On vérifie que l'email que l'utilisateur n'est pas déjà utilisée,
                 * Et uniquement dans le cas ou l'ancien email et le nouveau email est différent
                 */
                if ($userModel->checkEmailExist() && $userInfo->getEmail() !== $userModel->getEmail())
                {
                    $FV->forceError('email', 'Cet email est déjà utilisée.');
                }
                /**
                 * On vérifie que l'username de l'utilisateur n'est pas déjà utilisée
                 * Et uniquement dans le cas ou l'ancien nom d'utilisateur et le nouveau soit différent
                 */
                if ($userModel->checkUsernameExists() && $userInfo->getUsername() !== $userModel->getUsername())
                {
                    $FV->forceError('username', 'Ce nom d\'utilsateur est déjà utilisée.');
                }
                /** Si le formulaire est toujours valide */
                if ($FV->formIsValid())
                {
                    /** Mise à jour des informations de l'utilisateur */
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
        /** Vérifie que le formulaire est bien envoyé et que le token CSRF est valide. */
        if ($FV->checkFormIsSend('deleteUserAction'))
        {
            /** Si l'utilisateur essaye de supprimer son compte auquel il est connecté on lui averti que ce n'est pas possible */
            if ($id == $_SESSION['user']['id'])
            {
                FlashMessageService::addErrorMessage('Impossible de supprimer le compte sur lequel vous êtes actuellement connecté.');
                $this->redirectWithAltoRouter('adminUsersList');
            }
            $user = new Users();
            $user->setId($id);
            /** Supprimer l'utilisateur */
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