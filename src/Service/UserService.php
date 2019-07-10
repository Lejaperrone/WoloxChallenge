<?php


namespace App\Service;


use App\Entity\User;
use App\Exception\InvalidUserException;
use App\Exception\UserAlreadyExistException;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{

    private $userRepository;
    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->validator = $validator;
    }

    public function getUser($userId)
    {
        $user = $this->userRepository->find($userId);
        if(!$user){
            throw new UserNotFoundException($userId);
        }
        return $user;
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

        $this->validateUser($user);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser($userId, $name, $email, $image)
    {
        $user = $this->userRepository->find($userId);
        if(!$user) {
            throw new UserNotFoundException($userId);
        }

        if($user->getEmail() != $email){
            $existantUser =  $this->userRepository->findByEmail($email);
            if($existantUser){
                throw new UserAlreadyExistException($user->getEmail());
            }
        }

        $user->setName($name);
        $user->setEmail($email);
        $user->setImage($image);

        $this->validateUser($user);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function deleteUser($userId)
    {
        $user = $this->userRepository->find($userId);
        if(!$user){
            throw new UserNotFoundException($userId);
        }
        $this->em->remove($user);
        $this->em->flush();
    }

    public function validateUser($user)
    {
        $errors = $this->validator->validate($user);
        if(count($errors) > 0){
            $errorListMessage = array();
            foreach ($errors as $error){
                array_push($errorListMessage, $error->getMessage());
            }
            throw new InvalidUserException($errorListMessage);
        }
    }
}