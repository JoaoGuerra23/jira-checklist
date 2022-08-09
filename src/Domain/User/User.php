<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

/**
 * @OA\Schema(
 *     title="User",
 *     description="A simple user model."
 * )
 */
class User implements JsonSerializable
{
    /**
     * @OA\Property(type="integer", format="int64", readOnly=true, example=1)
     */
    private $id;

    /**
     * @OA\Property(type="string", example="johndoe")
     */
    private $username;

    /**
     * @OA\Property(type="string", example="John")
     */
    private $firstName;

    /**
     * @OA\Property(type="string", example="Doe")
     */
    private $lastName;

    public function __construct(?int $id, string $username, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->username = strtolower($username);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
