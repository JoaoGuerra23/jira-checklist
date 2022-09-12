<?php

namespace App\Infrastructure\Persistence\Repositories;


use App\Domain\UserAuth\User;
use App\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


class UserAuthRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $user
     */
    public function __construct(EntityManagerInterface $entityManager, User $user)
    {
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function findAllUsers(): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u.email')
            ->from(User::class, 'u')
            ->getQuery()
            ->execute();
    }

    /**
     * @return array
     */
    public function findAllUsersPassword(): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u.password')
            ->from(User::class, 'u')
            ->getQuery()
            ->getSingleColumnResult();
    }


    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function createUser(string $name, string $email, string $password): User
    {
        $this->user = new User();
        $this->user->setName($name);
        $this->user->setEmail($email);
        $this->user->setPassword($password);
        $this->user->setCreatedAt(new DateTime());
        $this->user->setUpdatedAt(new DateTime());

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

        return $this->user;

    }


    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function verifyUser(string $email, string $password): bool
    {
        $allUsers = $this->findAllUsers();

        $userPass = $this->findAllUsersPassword();

        foreach ($userPass as $pass){
            $hashPassword = $pass;
        }

        $verify = password_verify($password,$hashPassword);

        $email = Validator::validateValue('email', $email, $allUsers);

        if ($email === null || $verify === false){
            return false;
        }

        return true;
    }

}