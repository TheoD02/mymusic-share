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
    protected string $lastName;
    protected string $firstName;
    protected string $email;
    protected string $password;
    protected string $country;
    protected string $zipCode;
    protected string $city;
    protected string $address;
    protected int $houseNumber;
    protected string $registerDate;
    protected ?int $remainingDownload;
    protected ?string $confirmationToken;
    protected ?string $confirmationTokenExpire;
    protected ?string $confirmationDate;
    protected ?string $passwordResetToken;
    protected ?string $passwordResetExpire;
    protected int $id_userRole;
    protected ?string $rememberMeToken;

    private static ?Roles $role = null;

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
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
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
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return self
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $address Nom de la rue
     *
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getHouseNumber(): int
    {
        return $this->houseNumber;
    }

    /**
     * @param int $houseNumber
     *
     * @return self
     */
    public function setHouseNumber(int $houseNumber): self
    {
        $this->houseNumber = $houseNumber;
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

    /**
     * Récupère le rôle associé à l'utilisateur
     *
     * @return Roles|false
     */
    public function getRole(): Roles|false
    {
        if (self::$role === null || self::$role->getId() !== $this->getIdUserRole())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_userrole` WHERE `id` = :idRole');
            $stmt->bindValue(':idRole', $this->getIdUserRole(), PDO::PARAM_INT);
            $stmt->execute();
            self::$role = $stmt->fetchObject(Roles::class);
        }
        return self::$role;
    }
}