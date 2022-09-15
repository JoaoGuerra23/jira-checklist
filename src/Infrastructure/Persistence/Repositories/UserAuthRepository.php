<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\UserAuth\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

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
     * @return User[]
     *
     * @throws NoResultException|NonUniqueResultException
     */
    public function getUserByEmailAndPassword(string $email, string $password): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u.email, u.password')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter(':email', $email)
            ->andWhere('u.password = :password')
            ->setParameter(':password', $password)
            ->getQuery()
            ->getSingleResult();
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
}
