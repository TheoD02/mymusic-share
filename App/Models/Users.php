<?php


namespace App\Models;


use App\Models\Scheme\UsersScheme;
use PDO;

class Users extends UsersScheme
{
    /**
     * Créer un utilisateur en base de données
     *
     * @return bool false en cas d'échec.
     */
    public function addUser(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_users` 
                                      (`lastName`, `firstName`, `email`, `password`, `houseNumber`, `country`, `zipCode`, `city`, `address`, `confirmationToken`, `confirmationTokenExpire`) VALUES
                                      (:lastname, :firstname, :email, :password, :houseNumber, :country, :zipcode, :city, :address, :confirmationToken, :confirmationTokenExpire);');
        $stmt->bindValue(':lastname', $this->getLastname(), PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $this->getFirstname(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':country', $this->getCountry(), PDO::PARAM_STR);
        $stmt->bindValue(':houseNumber', $this->getHouseNumber(), PDO::PARAM_INT);
        $stmt->bindValue(':zipcode', $this->getZipCode(), PDO::PARAM_INT);
        $stmt->bindValue(':city', $this->getCity(), PDO::PARAM_STR);
        $stmt->bindValue(':address', $this->getAddress(), PDO::PARAM_STR);
        $stmt->bindValue(':confirmationToken', $this->getConfirmationToken(), PDO::PARAM_STR);
        $stmt->bindValue(':confirmationTokenExpire', $this->getConfirmationTokenExpire(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Add user without any information concerning address
     *
     * @return bool
     */
    public function addUserEssential(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_users` 
                                      (`lastName`, `firstName`, `email`, `password`, `houseNumber`, `country`, `zipCode`, `city`, `address`, `confirmationToken`, `confirmationTokenExpire`) VALUES
                                      (:lastname, :firstname, :email, :password, :country, :zipcode, :city, :address, :confirmationToken, :confirmationTokenExpire);');
        $stmt->bindValue(':lastname', $this->getLastname(), PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $this->getFirstname(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':country', '', PDO::PARAM_STR);
        $stmt->bindValue(':houseNumber', $this->getHouseNumber(), PDO::PARAM_INT);
        $stmt->bindValue(':zipcode', '', PDO::PARAM_STR);
        $stmt->bindValue(':city', '', PDO::PARAM_STR);
        $stmt->bindValue(':address', '', PDO::PARAM_STR);
        $stmt->bindValue(':confirmationToken', '', PDO::PARAM_STR);
        $stmt->bindValue(':confirmationTokenExpire', '', PDO::PARAM_STR);
        return $stmt->execute();
    }


    /**
     * Vérifie si une email est déjà en base de données
     *
     * @return bool
     */
    public function checkEmailExist(): bool
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `isExist` FROM `myokndefht_users` WHERE `email` = :email;');
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->execute();
        return (bool)$stmt->fetch()->isExist;
    }

    /**
     * Recherche un utilisateur via son email
     *
     * @return Users|false
     */
    public function getUserByEmail(): Users|false
    {
        $stmt = $this->prepare('SELECT `id`, `firstName`, `lastName`, `email`, `password`, `confirmationDate`, `confirmationToken`, `confirmationTokenExpire`, `passwordResetToken`, `passwordResetExpire`, `id_userRole`, `remainingDownload`, `rememberMeToken`, `houseNumber` FROM `myokndefht_users` WHERE `email` = :email;');
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Recherche un utilisateur par son ID
     *
     * @return Users|false
     */
    public function getUserById(): Users|false
    {
        $stmt = $this->prepare('SELECT `id`, `firstName`, `lastName`, `email`, `password`, `confirmationDate`, `confirmationToken`, `confirmationTokenExpire`, `passwordResetToken`, `passwordResetExpire`, `id_userRole`, `remainingDownload`, `rememberMeToken`, `address`, `houseNumber`, `country`, `city`, `zipCode` FROM `myokndefht_users` WHERE `id` = :id;');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Recherche un utilisateur par son Token de reinitialisation de mot de passe
     *
     * @return Users|false
     */
    public function getUserByPasswordResetToken(): Users|false
    {
        $stmt = $this->prepare('SELECT `id`, `firstName`, `lastName`, `email`, `password`, `confirmationDate`, `confirmationToken`, `confirmationTokenExpire`, `passwordResetToken`, `passwordResetExpire`, `id_userRole`, `remainingDownload`, `rememberMeToken` FROM `myokndefht_users` WHERE `passwordResetToken` = :passwordResetToken;');
        $stmt->bindValue(':passwordResetToken', $this->getPasswordResetToken(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Recherche un utilisateur par son Token RememberMe en cookie
     *
     * @return Users|false
     */
    public function getUserByRememberMeToken(): Users|falsereme
    {
        $stmt = $this->prepare('SELECT `id`, `firstName`, `lastName`, `email`, `password`, `confirmationDate`, `confirmationToken`, `confirmationTokenExpire`, `passwordResetToken`, `passwordResetExpire`, `id_userRole`, `remainingDownload`, `rememberMeToken` FROM `myokndefht_users` WHERE `rememberMeToken` = :rememberMeToken;');
        $stmt->bindValue(':rememberMeToken', $this->getRememberMeToken(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Met à jour le rememberMe Token via l'ID de l'utilisateur
     *
     * @return bool
     */
    public function updateRememberMeTokenById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET `rememberMeToken` = :rememberMeToken WHERE `id` = :id;');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':rememberMeToken', $this->getRememberMeToken(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Met à jour le confirmationToken Token et sa date d'expiration via l'ID de l'utilisateur
     *
     * @return bool
     */
    public function updateConfirmationTokenById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET `confirmationToken` = :confirmationToken, `confirmationTokenExpire` = :confirmationTokenExpire WHERE `id` = :id');
        $stmt->bindValue('id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':confirmationToken', $this->getConfirmationToken(), PDO::PARAM_STR);
        $stmt->bindValue(':confirmationTokenExpire', $this->getConfirmationTokenExpire(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Met à jour le token de reinitialisation du mot de passe et la date d'expiration du token
     *
     * @return bool
     */
    public function updatePasswordResetById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET `passwordResetToken` = :passwordResetToken, `passwordResetExpire` = :passwordResetExpire WHERE `id` = :id;');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':passwordResetToken', $this->getPasswordResetToken(), PDO::PARAM_STR);
        $stmt->bindValue(':passwordResetExpire', $this->getPasswordResetExpire(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Met à jour le mot de passe via l'ID de l'utilisateur
     *
     * @return bool
     */
    public function updateUserPasswordById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET  `passwordResetExpire` = NULL, `passwordResetToken` = NULL, `password` = :password WHERE `id` = :id;');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':password', $this->getPassword(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Défini le compte de l'utilisateur comme confirmer
     *
     * @return bool
     */
    public function setAccountToConfirmedStatut(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET `confirmationToken` = NULL, `confirmationTokenExpire` = NULL, `confirmationDate` = NOW() WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourne la liste des utilisateur
     *
     * @return Users[]|false
     */
    public function getUsersList(): array|false
    {
        $stmt = $this->prepare('SELECT `id`, `lastName`, `firstName`, `email`, `confirmationDate` AS `isConfirmed` FROM `myokndefht_users`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Met à jour les informations de l'utilisateur
     *
     * @return bool
     */
    public function updateUserInfoById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET `lastName` = :lastname, `firstName` = :firstname, `email` = :email, `zipCode` = :zipCode, `country` = :country, `city` = :city, `address` = :address, `houseNumber` = :houseNumber, `id_userRole` = :idUserRole WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':lastname', $this->getLastName(), PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $this->getFirstName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':zipCode', $this->getZipCode(), PDO::PARAM_STR);
        $stmt->bindValue(':country', $this->getCountry(), PDO::PARAM_STR);
        $stmt->bindValue(':city', $this->getCity(), PDO::PARAM_STR);
        $stmt->bindValue(':address', $this->getAddress(), PDO::PARAM_STR);
        $stmt->bindValue(':houseNumber', $this->getHouseNumber(), PDO::PARAM_INT);
        $stmt->bindValue(':idUserRole', $this->getIdUserRole(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprime un utilisateur via son ID
     *
     * @return bool
     */
    public function deleteUserById(): bool
    {
        $stmt = $this->prepare('DELETE FROM `myokndefht_users` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourne le nombre de téléchargement restant ou null
     *
     * @return int|null
     */
    public function getRemainingDownloadById(): int|null
    {
        $stmt = $this->prepare('SELECT `remainingDownload` FROM `myokndefht_users` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()->remainingDownload;
    }

    /**
     * Décrémente le nombre de téléchargement de l'utilisateur
     *
     * @return bool
     */
    public function decrementRemainingDownload(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_users` SET `remainingDownload` = `remainingDownload` - 1 WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}