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

    public function showProfileInformations(): void
    {
        $userInfo = (new Users())->setId(UserHelper::getUserID())->getUserById();
        $this->render('UserProfile/ProfileInformations', 'Vos informations personnel', [
            'userInfo' => $userInfo,
        ]);
    }

    public function editProfileInformations(): void
    {
        $FV       = new FormValidator($_POST);
        $userInfo = (new Users())->setId(UserHelper::getUserID())->getUserById();

        if ($FV->checkFormIsSend('editProfilInformationsAction'))
        {
            $FV->verify('lastName')->isNotEmpty()->isAlphaNumeric(['-']);
            $FV->verify('firstName')->isNotEmpty()->isAlphaNumeric(['-']);;
            $FV->verify('email')->isNotEmpty()->isEmail();
            $FV->verify('address')->isNotEmpty()->minLength(4);
            $FV->verify('houseNumber')->isInt();
            $FV->verify('zipCode')->isInt();
            $FV->verify('country')->isNotEmpty()->isAlphaNumeric([], 'both', false)->minLength(3);

            if ($FV->formIsValid())
            {
                $userInfo->setLastName($FV->getFieldValue('lastName'))->setFirstName($FV->getFieldValue('firstName'))
                         ->setEmail($FV->getFieldValue('email'))->setHouseNumber($FV->getFieldValue('houseNumber'))
                         ->setAddress($FV->getFieldValue('address'))->setCity($FV->getFieldValue('city'))
                         ->setZipCode($FV->getFieldValue('zipCode'))->setCountry($FV->getFieldValue('country'));

                if ($userInfo->updateUserInfoById())
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
        else if (!$FV->checkFormIsSend('editProfilInformations'))
        {
            $this->redirectWithAltoRouter('home');
        }
        $this->render('UserProfile/ProfileInformationsForm', 'Edition du profile', [
            'userInfo' => $userInfo,
        ]);
    }
}