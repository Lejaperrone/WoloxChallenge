<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    private $userRepository;
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    public function getUser($userId)
    {
        return $this->userRepository->find($userId);
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function addUser($name, $email, $image)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setImage($image);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser($userId, $name, $email, $image)
    {
        $user = $this->userRepository->find($userId);
        if($user) {
            $user->setName($name);
            $user->setEmail($email);
            $user->setImage($image);
            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }

    public function deleteUser($userId)
    {
        $user = $this->userRepository->find($userId);
        if($user) {
            $this->em->remove($user);
            $this->em->flush();
            return $userId;
        }
        return null;
    }
}