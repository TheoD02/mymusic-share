<?php


namespace Core\Form;


use Core\CSRFHelper;
use Core\FlashMessageService;

class FormValidatorCore
{
    /** @var array contient les données du formulaire ($_GET|$_POST) */
    protected static array $data;
    /** @var array|null contient les fichier du formulaire ($_FILES), si il en a */
    protected static ?array $file = null;
    /** @var string nom du champ */
    protected string $name;
    /** @var array contient les erreurs du formulaire */
    protected static array $errors = [];
    /** @var array contient les champ qui nécessite d'être re-saisie */
    protected static array $reEntryFields = [];

    /**
     * FormValidatorCore constructor.
     *
     * @param array      $data tableau de $_GET|$_POST
     * @param array|null $file tableau de $_FILES
     */
    public function __construct(array $data, array $file = null)
    {
        self::$data = $data;
        self::$file = $file;
    }

    /**
     * Vérifie que le formulaire a bien était envoyer,
     *
     * @param string $formName Nom du button submit du formulaire
     *
     * @return bool retourne true si il a été envoyé ou false si il n'est pas envoyé
     */
    public function checkFormIsSend(string $formName): bool
    {
        if (isset(self::$errors['generalErrors']))
        {
            unset(self::$errors['generalErrors']);
        }
        if (isset(self::$data[$formName]) && $this->checkCsrfTokenIsValid())
        {
            return true;
        }
        $this->setGeneralError('Le formulaire n\'a pas été envoyer.', 'formNotSend');
        return false;
    }

    /**
     * Retourne la validité du formulaire
     *
     * @return bool
     */
    public function formIsValid(): bool
    {
        return empty(self::$errors) || self::$errors === self::$reEntryFields;
    }

    /**
     * Vérifie qu'un champ existe dans $_GET, $_POST (Dépend des valeurs passer en paramètres du
     * constructeur)
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function checkFieldExist(string $fieldName): bool
    {
        return isset(self::$data[$fieldName]);
    }

    /**
     * Vérifie que le champ exist dans $_FILES
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function checkFileFieldExist(string $fieldName): bool
    {
        return isset(self::$file[$fieldName]);
    }

    /**
     * Retourne le message d'erreur en HTML quand le formulaire n'est pas envoyé.
     *
     * @return string|null
     */
    public static function getMessageFormNotSendHTML(): ?string
    {
        if (self::getGeneralError('formNotSend') !== null)
        {
            return '<div class="alert alert-danger">' . self::getGeneralError('formNotSend') . '</div>';
        }
        return null;
    }

    /**
     * Retourne la valeur saisie dans le champ ou null
     *
     * @param string|null $fieldName Nom du champ à récupérer si non préciser il prend le champ en
     *                               cours
     *
     * @return string|null
     */
    public function getFieldValue(?string $fieldName = null): ?string
    {
        return self::$data[$fieldName ?? $this->name] ?? null;
    }

    /**
     * Retourne la class valid ou invalid, si le champ est valide ou non.
     *
     * @param string $fieldName Nom du champ
     *
     * @return string|null
     */
    public static function fieldIsValid(string $fieldName): ?string
    {
        if (!isset(self::$errors[$fieldName]) && !isset(self::$data[$fieldName]))
        {
            return null;
        }
        return self::getError($fieldName) === null ? 'is-valid' : 'is-invalid';
    }

    /**
     * Remplace le message défini par un message prédéfini par défaut par un message personnalisé
     *
     * @param string $message Message du invalid feedback
     *
     * @return void
     */
    public function setCustomInvalidFeedback(string $message): void
    {
        if (isset(self::$errors[$this->name]))
        {
            $this->setError($message, $this->name, true);
        }
    }

    /**
     * @param string $errName Nom du message d'erreur
     *
     * @return string|null Le message d'erreur ou null
     */
    public static function getError(string $errName): ?string
    {
        return self::$errors[$errName] ?? null;
    }

    /**
     * Défini un message d'erreur pour un champ du formulaire
     *
     * @param string      $message          Contenu du message d'erreur
     * @param string|null $errName          Nom du message d'erreur (par défaut le nom du champ)
     * @param bool        $overwriteIfExist Si un message existe déjà le remplacer (par défaut
     *                                      désactiver)
     */
    protected function setError(string $message, ?string $errName = null, bool $overwriteIfExist = false): void
    {
        if ((isset(self::$errors[$errName ?? $this->name]) && $overwriteIfExist) || !isset(self::$errors[$errName ?? $this->name]))
        {
            self::$errors[$errName ?? $this->name] = $message;
        }
    }

    /**
     * Défini un message obligatoire (par exemple dans le cas d'une vérification en base de données)
     *
     * @param string $errName Nom du champ
     * @param string $message Contenu du message
     */
    public function forceError(string $errName, string $message): void
    {
        self::$errors[$errName] = $message;
    }

    /**
     * @param string $errName Nom du message d'erreur
     *
     * @return string|null
     */
    public static function getGeneralError(string $errName): ?string
    {
        return self::$errors['generalErrors'][$errName] ?? null;
    }

    /**
     * Défini un message d'erreur général du formulaire
     *
     * @param string      $message          Contenu du message d'erreur
     * @param string|null $errName          Nom du message d'erreur (par défaut le nom du champ)
     * @param bool        $overwriteIfExist Si un message existe déjà le remplacer (par défaut
     *                                      désactiver)
     */
    protected function setGeneralError(string $message, ?string $errName = null, bool $overwriteIfExist = false): void
    {
        if ((isset(self::$errors['generalErrors'][$errName ?? $this->name]) && $overwriteIfExist) || !isset(self::$errors['generalErrors'][$errName ?? $this->name]))
        {
            self::$errors['generalErrors'][$errName ?? $this->name] = $message;
        }
    }

    /**
     * Retourne une erreur au format HTML (Bootstrap)
     * Si elle n'existe pas null est renvoyé
     *
     * @param string $fieldName Nom de l'erreur (Nom du champ)
     *
     * @return string|null
     */
    public static function getOneErrorHTML(string $fieldName): ?string
    {
        if (self::getError($fieldName) !== null)
        {
            return '<div class="invalid-feedback text-center">' . self::getError($fieldName) . '</div>';
        }
        return null;
    }

    /**
     * Retourne une erreur au format HTML (Bootstrap)
     * Si elle n'existe pas null est renvoyé
     *
     * @param string $fieldName Nom de l'erreur (Nom du champ)
     *
     * @return string|null
     */
    public static function getOneGeneralErrorHTML(string $fieldName): ?string
    {
        if (self::getGeneralError($fieldName) !== null)
        {
            return '<div class="alert alert-danger">' . self::getGeneralError($fieldName) . '</div>';
        }
        return null;
    }

    /**
     * Retourne les erreurs au format HTML (Bootstrap)
     * Si elle n'existe pas null est renvoyé
     *
     *
     * @return string|null
     */
    public static function getAllGeneralError(): ?string
    {
        if (isset(self::$errors['generalErrors']) && count(self::$errors['generalErrors']) > 0)
        {
            $html = '<div class="alert alert-danger"><ul class="m-0">';
            foreach (self::$errors['generalErrors'] as $generalErrorValue)
            {
                $html .= '<li>' . $generalErrorValue . '</li>';
            }
            $html .= '</ul></div>';
            return $html;
        }
        return null;
    }
    /**
     * $_FILES Function
     */

    /**
     * Retourne les données d'un fichier dans la variable $_FILES
     *
     * @return array|null
     */
    protected function getFileInfo(): ?array
    {
        return self::$file[$this->name] ?? null;
    }

    /**
     * Retourne la taille du fichier
     *
     * @return string|null
     */
    protected function getFileSizeInMB(): ?string
    {
        if (isset(self::$file[$this->name]['size']))
        {
            return round(self::$file[$this->name]['size'] / 1024 / 1024, 1);
        }
        return null;
    }

    /**
     * Retourne la taille du fichier
     *
     * @return array|null
     */
    protected function getFileName(): ?array
    {
        return self::$file[$this->name]['name'] ?? null;
    }

    /**
     * Retourne le mime type du fichier
     *
     * @return string|null
     */
    protected function getFileMimeType(): ?string
    {
        return self::$file[$this->name]['type'] ?? null;
    }

    public function checkCsrfTokenIsValid(): bool
    {
        if (isset(self::$data['csrf-token']) && self::$data['csrf-token'] === CSRFHelper::getCsrfToken())
        {
            return true;
        }
        FlashMessageService::addErrorMessage('Accès interdit.');
        return false;
    }
}