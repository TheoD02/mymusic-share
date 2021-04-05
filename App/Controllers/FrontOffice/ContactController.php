<?php


namespace App\Controllers\FrontOffice;


use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\Mailer;

class ContactController extends BaseController
{
    public function showContactForm(): void
    {
        $this->render('Contact', 'Nous contactez');
    }

    public function contactAction(): void
    {
        $FV = new FormValidator($_POST);

        /** Vérifier que le formulaire soit bien envoyer et que le token CSRF soit valide */
        if ($FV->checkFormIsSend('contactForm'))
        {
            /** Vérifier les champs du formulaire */
            $FV->verify('email')->isEmail();
            $FV->verify('subject')->isNotEmpty()->minLength(5);
            $FV->verify('message')->isNotEmpty()->minLength(30);

            /** Si le formualire est valide */
            if ($FV->formIsValid())
            {
                $mailer = new Mailer();
                /** Définir les données nécessaire pour l'envoi de l'email (Qui envoi ?, Envoyer à ?, Sujet de l'email) */
                $mailer->setFrom($FV->getFieldValue('email'))
                       ->setTo(Mailer::ADMIN_EMAIL)
                       ->setSubject($FV->getFieldValue('subject'));

                /** Envoyer l'email */
                if ($mailer->sendMail('contact', (object)$_POST))
                {
                    FlashMessageService::addSuccessMessage('Votre message à été envoyé avec succès ! <br>Nous vous recontacterons dans les prochain jours');
                    $this->redirectWithAltoRouter('home');
                }
                else
                {
                    FlashMessageService::addErrorMessage('Une erreur est survenue lors de l\'envoi de votre email, veuillez ressayer !');
                }
            }
        }
        $this->render('Contact', 'Nous contactez');
    }
}