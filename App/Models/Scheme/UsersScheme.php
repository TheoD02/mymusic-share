<?php

namespace App\Models\Scheme;

use App\Models\Roles;
use Core\Base\BaseModel;
use DateTime;
use DateInterval;
use PDO;

class UsersScheme extends BaseModel
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $registerDate;
    protected ?int $remainingDownload;
    protected ?string $confirmationToken;
    protected ?string $confirmationTokenExpire;
    protected ?string $confirmationDate;
    protected ?string $passwordResetToken;
    protected ?string $passwordResetExpire;
    protected int $id_userRole;
    protected ?string $rememberMeToken;


    /**
     * Retourne les variables de l'objet
     *
     * @return array
     */
    public function getModelVars(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return UsersScheme
     */
    public function setUsername(string $username): UsersScheme
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterDate(): string
    {
        return $this->registerDate;
    }

    /**
     * @param string $registerDate
     *
     * @return self
     */
    public function setRegisterDate(string $registerDate): self
    {
        $this->registerDate = $registerDate;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRemainingDownload(): ?int
    {
        return $this->remainingDownload;
    }

    /**
     * @param int|null $remainingDownload
     *
     * @return self
     */
    public function setRemainingDownload(?int $remainingDownload): self
    {
        $this->remainingDownload = $remainingDownload;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $confirmationToken
     *
     * @return self
     */
    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmationTokenExpire(): ?string
    {
        return $this->confirmationTokenExpire;
    }

    /**
     * @param int $nbOfHours    Nombre d'heure de validité du token à partir de la date et heure
     *                          actuel
     *
     * @return self
     * @throws \Exception
     */
    public function setConfirmationTokenExpire(int $nbOfHours): self
    {
        $this->confirmationTokenExpire = (new DateTime())->add(new \DateInterval('PT' . $nbOfHours . 'H'))
                                                         ->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * Retourne le statut du compte (Confirmer ou non confirmer)
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmationDate !== null;
    }

    /**
     * @return string|null
     */
    public function getConfirmationDate(): ?string
    {
        return $this->confirmationDate;
    }

    /**
     * @param string|null $confirmationDate
     *
     * @return self
     */
    public function setConfirmationDate(?string $confirmationDate): self
    {
        $this->confirmationDate = $confirmationDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    /**
     * @param string|null $passwordResetToken
     *
     * @return self
     */
    public function setPasswordResetToken(?string $passwordResetToken): self
    {
        $this->passwordResetToken = $passwordResetToken;
        return $this;
    }

    /**
     * Retourne la date formater
     *
     * @return string|null
     */
    public function getFormattedPasswordResetExpire(): ?string
    {
        return (new DateTime($this->passwordResetExpire))->format('d/m/Y H:i:s');
    }

    /**
     * @return string|null
     */
    public function getPasswordResetExpire(): ?string
    {
        return $this->passwordResetExpire;
    }

    /**
     * @param int $nbOfHours Nombre d'heure avant que le token ne soit plus valide.
     *
     * @return self
     */
    public function setPasswordResetExpire(int $nbOfHours): self
    {
        $this->passwordResetExpire = (new DateTime())->add(new DateInterval('PT' . $nbOfHours . 'H'))
                                                     ->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUserRole(): int
    {
        return $this->id_userRole;
    }

    /**
     * @param int $id_userRole
     *
     * @return self
     */
    public function setIdUserRole(int $id_userRole): self
    {
        $this->id_userRole = $id_userRole;
        return $this;
    }

    /**
     * @return string
     */
    public function getRememberMeToken(): string
    {
        return $this->rememberMeToken;
    }

    /**
     * @param string $rememberMeToken
     *
     * @return self
     */
    public function setRememberMeToken(string $rememberMeToken): self
    {
        $this->rememberMeToken = $rememberMeToken;
        return $this;
    }
}