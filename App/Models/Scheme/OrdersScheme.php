<?php


namespace App\Models\Scheme;


use App\Models\Subscriptions;
use App\Models\Users;
use Core\Base\BaseModel;
use PDO;

class OrdersScheme extends BaseModel
{

    protected int $id;
    protected int $number;
    protected string $orderDate;
    protected string $deliveryDate;
    protected bool $isActive;

    protected int $id_subscriptions;
    protected int $id_users;

    protected static ?Subscriptions $subscription = null;
    protected static ?Users $user = null;

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
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     *
     * @return self
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFormattedOrderDate(): string
    {
        return (new \DateTime($this->orderDate))->format('d/m/y à H:i:s');
    }

    /**
     * @return string
     */
    public function getOrderDate(): string
    {
        return $this->orderDate;
    }

    /**
     * @param string $orderDate
     *
     * @return self
     */
    public function setOrderDate(string $orderDate): self
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFormattedDeliveryDate(): string
    {
        return (new \DateTime($this->deliveryDate))->format('d/m/y à H:i:s');
    }

    /**
     * Retourne la date d'expiration de l'abonnement
     *
     * @return string
     * @throws \Exception
     */
    public function getSubscriptionExpirationDate(): string
    {
        return (new \DateTime($this->deliveryDate))->add(new \DateInterval('P' . $this->getSubscription()->getDuration() . 'M'))
                                                   ->format('d/m/Y à H:i:s');
    }

    /**
     * @return string
     */
    public function getDeliveryDate(): string
    {
        return $this->deliveryDate;
    }

    /**
     * @param string $deliveryDate
     *
     * @return self
     */
    public function setDeliveryDate(string $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive ? 'Oui' : 'Non';
    }

    /**
     * @param bool $isActive
     *
     * @return self
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSubscriptions(): int
    {
        return $this->id_subscriptions;
    }

    /**
     * @param int $id_subscriptions
     *
     * @return self
     */
    public function setIdSubscriptions(int $id_subscriptions): self
    {
        $this->id_subscriptions = $id_subscriptions;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsers(): int
    {
        return $this->id_users;
    }

    /**
     * @param int $id_users
     *
     * @return self
     */
    public function setIdUsers(int $id_users): self
    {
        $this->id_users = $id_users;
        return $this;
    }

    /**
     * Retourne l'utilisateur associé à la commande
     *
     * @return Users|false
     */
    public function getUser(): Users|false
    {
        if (self::$user === null)
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_users` WHERE `id` = :idUser');
            $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
            $stmt->execute();
            self::$user = $stmt->fetchObject(Users::class);
        }
        return self::$user;
    }

    /**
     * Retourne l'abonnement associé à la commande
     *
     * @return Subscriptions|false
     */
    public function getSubscription(): Subscriptions|false
    {
        if (self::$subscription === null  || self::$subscription->getId() !== $this->getIdSubscriptions())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_subscriptions` WHERE `id` = :idSubscription');
            $stmt->bindValue(':idSubscription', $this->getIdSubscriptions(), PDO::PARAM_INT);
            $stmt->execute();
            self::$subscription = $stmt->fetchObject(Subscriptions::class);
        }
        return self::$subscription;
    }
}