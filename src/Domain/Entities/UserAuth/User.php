<?php

namespace App\Domain\Entities\UserAuth;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="User")
 *
 * @OA\Schema(
 *     description="User Auth Model",
 *     title="User Auth Model"
 * )
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="name")
     *
     * @OA\Property(type="string", description="User name", title="User name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="email")
     *
     * @OA\Property(type="string", description="User email", title="User email")
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="password")
     *
     * @OA\Property(type="string", description="User password", title="User password")
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
     *
     *
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="updated_at")
     *
     *
     */
    private $updated_at;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
