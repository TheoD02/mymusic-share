<?php


namespace Core\Form;


class FormValidator extends FormValidatorCore
{
    /** Mime Type pour les fichiers */
    public const FILE_PDF    = 'application/pdf';
    public const FILE_ZIP    = 'application/temp_music_zip';
    public const FILE_MSWORD = 'application/msword';
    public const AUDIO_MPEG  = 'audio/mpeg';
    public const AUDIO_WAV   = 'audio/wav';
    public const AUDIO_FLAC  = 'audio/wav';
    public const VIDEO_MP4   = 'video/mp4';
    public const VIDEO_MPEG  = 'video/mpeg';
    public const IMAGE_GIF   = 'image/gif';
    public const IMAGE_PNG   = 'image/png';
    public const IMAGE_JPG   = 'image/jpeg';


    /**
     * Nom du champ à vérifié, retourne une erreur si il n'est pas trouvé
     *
     * @param string $fieldName Nom du champ
     *
     * @return $this
     */
    public function verify(string $fieldName): self
    {
        $this->name = $fieldName;
        if (!isset(self::$data[$this->name]))
        {
            $this->setError('Champ non reçu.');
        }
        return $this;
    }

    /**
     * Vérifie que le champ n'est pas null et pas vide
     *
     * @return $this
     */
    public function isNotEmpty(): self
    {
        if (empty($this->getCurrentValue()))
        {
            $this->setError('Veuillez remplir le champ.');
        }
        return $this;
    }

    /**
     * Vérifie que la valeur du champ ne soit pas en dessous d'une longueur minimal
     *
     * @param int $minLength Longueur à ne pas dépasser
     *
     * @return $this
     */
    public function minLength(int $minLength): self
    {
        if (strlen($this->getCurrentValue()) < $minLength)
        {
            $this->setError('Le champ requis un minium de ' . $minLength . ' caractères');
        }
        return $this;
    }

    /**
     * Vérifie que la valeur du champ ne dépasse pas une longueur maximal
     *
     * @param int $maxLength Longueur à ne pas dépasser
     *
     * @return $this
     */
    public function maxLength(int $maxLength): self
    {
        if (strlen($this->getCurrentValue()) > $maxLength)
        {
            $this->setError('Le champ requis un maximum de ' . $maxLength . ' caractères');
        }
        return $this;
    }

    /**
     * Requis des caractères alphanumérique uniquement (Accent compris)
     * Les chiffre sont désactivé par défaut.
     *
     * @param array  $additionalChars Caractère supplémentaire a prendre en compte (attention à
     *                                l'échappement [REGEX])
     * @param string $caseConstraint  caseSensitive (both = lower & upper, upper = upperOnly, lower
     *                                = lowerOnly)
     * @param bool   $acceptNumber    permet d'accepter les chiffre numérique
     *
     * @return $this
     * @throws \Exception
     */
    public function isAlphaNumeric(array $additionalChars = [], string $caseConstraint = 'both', bool $acceptNumber = false): self
    {
        /** Extrait les caractères passer en paramètre à inclure dans la regex*/
        $chars = implode('', $additionalChars);
        /** Créer un message personnalisée selon les paramètres sélectionner */
        $messageDetail = match ($caseConstraint)
        {
            'both' => ' en majuscule et minuscule',
            'upper' => ' en majuscule',
            'lower' => ' en minuscule',
            default => '',
        };
        $messageDetail .= $acceptNumber ? ' (chiffres autorisé).' : '.';
        $messageDetail .= count($additionalChars) > 0 ? '<br>Les caractères spécifiques suivant sont autorisé : ' . implode(', ', $additionalChars) . '' : '';
        /** Choir la contrainte par rapport à la valeur passer en paramètre */
        $caseConstraint = match ($caseConstraint)
        {
            'both' => 'L',
            'upper' => 'Lu',
            'lower' => 'Ll',
            default => throw new \Exception('Veuillez choisir une contrainte valide [both, upper ou lower].'),
        };
        if (!preg_match('/^[\p{' . $caseConstraint . '}' . ($acceptNumber ? '0-9' : '') . $chars . ']+$/u', $this->getCurrentValue()))
        {
            $this->setError('Veuillez saisir des caractères alphanumérique uniquement' . $messageDetail);
        }
        return $this;
    }

    /**
     * Vérifie que la valeur du champ est de type int
     *
     * @param int      $min minValue
     * @param int|null $max maxValue
     *
     * @return $this
     */
    public function isInt(int $min = 0, ?int $max = null): self
    {
        if (!is_int((int)$this->getCurrentValue()))
        {
            $this->setError('Veuillez saisir un nombre entier.');
        }
        else
        {
            if ($this->getCurrentValue() < $min)
            {
                $this->setError('Veuillez saisir une valeur supérieur ou égal à ' . $min . '.');
            }
            else if ($max !== null && $this->getCurrentValue() > $max)
            {
                $this->setError('Veuillez saisir une valeur inférieur ou égal à ' . $max . '.');
            }
        }
        return $this;
    }

    /**
     * Vérifie qu'un select à une valeur différente de 'default'
     *
     * @return $this
     */
    public function isValidSelect(): self
    {
        if ($this->getCurrentValue() === 'default')
        {
            $this->setError('Veuillez sélectionner un élément.');
        }
        return $this;
    }

    /**
     * Vérifie que la valeur du champ est de type float
     *
     * @param float      $min             minValue
     * @param float|null $max             maxValue
     * @param int        $numberOfDecimal Nombre de décimal à afficher
     *
     * @return $this
     */
    public function isFloat(float $min = 0, ?float $max = null, int $numberOfDecimal = 2): self
    {
        if (!is_float((float)$this->getCurrentValue()))
        {
            $this->setError('Veuillez saisir un nombre à virgule.');
        }
        else
        {
            if ($this->getCurrentValue() < $min)
            {
                $this->setError('Veuillez saisir une valeur supérieur ou égal à ' . number_format($min, $numberOfDecimal) . '.');
            }
            else if ($max !== null && $this->getCurrentValue() > $max)
            {
                $this->setError('Veuillez saisir une valeur inférieur ou égal à ' . number_format($max, $numberOfDecimal) . '.');
            }
        }
        return $this;
    }

    /**
     * Vérifie si l'email est valide
     *
     * @return $this
     */
    public function isEmail(): self
    {
        if (!filter_var($this->getCurrentValue(), FILTER_VALIDATE_EMAIL))
        {
            $this->setError('Veuillez saisir une email valide.');
        }
        return $this;
    }

    /**
     * Vérifie si un champ est coché
     *
     * @return $this
     */
    public function needToBeChecked(): self
    {
        if (!isset(self::$data[$this->name]))
        {
            $this->setError('Veuillez cocher la case.');
        }
        return $this;
    }

    /**
     * Return true si le champ est cochée si non false
     *
     * @param string $fieldName Nom du champ qui doit être cocher
     *
     * @return bool
     */
    public function isChecked(string $fieldName): bool
    {
        return isset(self::$data[$fieldName]);
    }

    /**
     * Vérifie qu'un mot de passe respect une regex
     * Par défaut accepte (tous les caractères avec un minium de 12 caractères et 100 au maximum)
     *
     * @param string $regex pattern de la regex
     *
     * @return $this
     */
    public function passwordConstraintRegex(string $regex = '/[^\'"]{12,100}/'): FormValidator
    {
        if (!preg_match($regex, $this->getCurrentValue()))
        {
            $this->setError('Veuillez saisir un mot de passe valide.');
        }
        return $this;
    }

    /**
     * Vérifie qu'un mot de passe correspond à un deuxième champ
     *
     * @param string $confirmPasswordFieldName Nom du deuxième champ à vérifié
     *
     * @return $this
     */
    public function passwordCorrespondTo(string $confirmPasswordFieldName): self
    {
        if (isset(self::$data[$confirmPasswordFieldName]) && $this->getCurrentValue() !== self::$data[$confirmPasswordFieldName])
        {
            $this->setGeneralError('Les mots de passe ne correspondent pas.');
            $this->setError('Veuillez ressaisir votre mot de passe.', $this->name, true);
            $this->setError('Veuillez ressaisir votre mot de passe de confirmation.', $confirmPasswordFieldName, true);
        }
        else if (isset(self::$errors[$this->name]))
        {
            $this->setError('Veuillez ressaisir votre mot de passe de confirmation.', $confirmPasswordFieldName, true);
        }
        return $this;
    }

    /**
     * Demande à l'utilisateur de re-rentrer la valeur dans le champ
     *
     * @return $this
     */
    public function needReEntry(): self
    {
        self::$reEntryFields[$this->name] = 'Veuillez ressaisir ce champ.';
        $this->setError('Veuillez ressaisir ce champ.');
        return $this;
    }

    /**
     * @param string $searchValue
     *
     * @return $this
     */
    public function isContain(string $searchValue): self
    {
        if (!str_contains($this->getCurrentValue(), $searchValue))
        {
            $this->setError('Le champ ne contient pas le mot suivant : ' . $searchValue . '.');
        }
        return $this;
    }

    /**
     * Vérifie que le champ contient au minium nu nombre défini d'un caractère spécifique
     *
     * @param string $character            Le caractère à compter
     * @param int    $minNumberOfCharacter Le nombre de fois que le caractère doit être contenu
     *
     * @return $this
     */
    public function isContainAtLeast(string $character, int $minNumberOfCharacter = 1): self
    {
        if (substr_count($this->getCurrentValue(), $character) < $minNumberOfCharacter)
        {
            $this->setError('Veuillez vérifier le champ et utilisée l\'autocomplétion, utilisée le format suivant : 1 Rue de Paris, Paris, 75000');
        }
        return $this;
    }

    /** File Verification */
    /**
     * Nom de l'élément dans le tableau $_FILES à vérifier, retourne une erreur si il y une erreur
     * retourné par PHP
     *
     * @param string $fileFieldName Nom de element du tableau a verifier
     *
     * @return $this
     * @throws \Exception
     */
    public function verifyFile(string $fileFieldName): self
    {
        if (self::$file === null)
        {
            throw new \Exception('Pas de valeur dans la variable file. Veuillez envoyé $_FILES dans le constructeur de FormValidator.');
        }
        $this->name = $fileFieldName;
        /** Une erreur est survenue lors du téléversement */
        if (isset(self::$file[$this->name]['error']) && self::$file[$this->name]['error'] !== 0)
        {
            $this->setError(match ((int)self::$file[$this->name]['error'])
            {
                1, 2 => 'Le fichier téléversé dépasse la limite autorisé, si le problème persiste contactez l\'administrateur du site avec une copie de ce message. ERR:12',
                3 => 'Un problème est survenue lors du téléversement, veuillez recommencer.',
                4 => 'Aucun fichier téléversé, veuillez sélectionner un fichier à téléversé.',
                5, 6, 8 => 'Un problème est survenue lors du téléversement, si le problème persiste contactez l\'administrateur du site avec une copie de ce message. ERR:568'
            });
            return $this;
        }
        return $this;
    }

    /**
     * Défini une taille maximal à ne pas dépasser
     *
     * @param int $maxSize
     *
     * @return $this
     */
    public function fileMaxSize(int $maxSize = 64): self
    {
        if ($this->getFileSizeInMB() > $maxSize)
        {
            $this->setError('La taille du fichier est trop grande. Veuillez téléversé un fichier pesant moins de ' . $maxSize . ' Mo');
        }
        return $this;
    }

    /**
     * Vérifie qu'un fichier envoyé soit au format souhaité
     *
     * @param array $acceptedFormat Les format accepté (e.g ['image/jpg' => 'jpg'])
     *                              Des constantes sont disponible pour les plus connus
     *                              FormValidator::IMAGE_JPG
     *
     * @return $this
     * @see https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
     */
    public function fileIsFormat(array $acceptedFormat = []): self
    {
        $formatsInline = '[*.' . implode(', *.', $acceptedFormat) . ']';
        if (!array_key_exists($this->getFileMimeType(), $acceptedFormat))
        {
            $this->setError('Veuillez uploader un fichier au format ' . $formatsInline . '.');
        }
        return $this;
    }
}