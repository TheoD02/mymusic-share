<?php


namespace Core\Form;


class FormHelper
{
    public static array $data;

    /**
     * Défini les données du formulaire
     *
     * @param array|object $data
     */
    public static function setFormData(array|object $data): void
    {
        // Si un objet est passer on transforme les attribut (non static) de l'objet en tableau
        if (is_object($data))
        {
            $data = $data->getModelVars();
        }
        self::$data = $data;
    }

    /**
     * Retourne la valeur du champ à afficher si il existe ou null
     *
     * @param string $fieldName Nom du champ
     *
     * @return string|null  Retourne la valeur du champ avec les caractères HTML échappée
     */
    public static function getSanitizedFieldValue(string $fieldName): ?string
    {
        return isset(self::$data[$fieldName]) ? htmlspecialchars(self::$data[$fieldName], ENT_QUOTES | ENT_HTML5) : null;
    }
}