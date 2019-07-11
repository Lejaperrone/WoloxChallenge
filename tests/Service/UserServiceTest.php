<?php


namespace App\Tests\Service;

use App\Entity\User;
use App\Exception\InvalidUserException;
use App\Exception\UserAlreadyExistException;
use App\Exception\UserNotFoundException;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class UserServiceTest extends TestCase
{

    private $objectManager;
    private $validator;

    public function setUp()
    {
        $this->objectManager = $this->createMock(EntityManagerInterface::class);
        $this->validator =  Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    public function testGetUserOk()
    {
        $user = new User();
        $user->setId(1);
        $user->setName('A Name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);
        $this->assertEquals($userService->getUser(1), $user);
    }

    public function testGetUserUserNotFound()
    {
        $this->expectException(UserNotFoundException::class);

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->getUser(1);
    }

    public function testAddUserOk()
    {
        $user = new User();
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($this->objectManager, $this->validator);
        $this->assertEquals($userService->addUser('A name', 'email@test.com', 'http://image.com'), $user);
    }

    public function testAddUserIvalidUser()
    {
        $this->expectException(InvalidUserException::class);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->addUser('', 'email@test.com', 'http://image.com');
    }

    public function testAddUserAlreadyExist()
    {
        $this->expectException(UserAlreadyExistException::class);

        $user = new User();
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->addUser('Another name', 'email@test.com', '');
    }

    public function testUpdateUserOk()
    {
        $user = new User();
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);

        $this->assertEquals($userService->updateUser(1,'A name', 'email@test.com', 'http://image.com'), $user);
    }

    public function testUpdateUserNotFound()
    {
        $this->expectException(UserNotFoundException::class);

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn(null);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->updateUser(1,'A name', 'email@test.com', 'http://image.com');
    }

    public function testUpdateUserAlreadyExist()
    {
        $this->expectException(UserAlreadyExistException::class);

        $user = new User();
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $user2 = new User();
        $user2->setName('Another name');
        $user2->setEmail('anotherEmail@test.com');
        $user2->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($user2);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->updateUser(1,'A name', 'anotherEmail@test.com', 'http://image.com');
    }

    public function testUpdateUserIvalidUser()
    {
        $this->expectException(InvalidUserException::class);

        $user = new User();
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->updateUser(1,'A name', '', 'http://image.com');

    }

    public function testDeleteUserOk()
    {
        $user = new User();
        $user->setId(1);
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);
        $this->assertEquals($userService->deleteUser(1), null);
    }

    public function testDeleteUserUserNotFound()
    {
        $this->expectException(UserNotFoundException::class);

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->deleteUser(1);
    }

    public function testValidateAttributesUserOk()
    {
        $user = new User();
        $user->setId(1);
        $user->setName('A name');
        $user->setEmail('email@test.com');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);
        $this->assertEquals($userService->validateUserAttributes($user), null);
    }

    public function testValidateAttributesInvalidUser()
    {
        $this->expectException(InvalidUserException::class);

        $user = new User();
        $user->setId(1);
        $user->setName('A name');
        $user->setEmail('');
        $user->setImage('http://image.com');

        $userRepository = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $userService = new UserService($this->objectManager, $this->validator);
        $userService->validateUserAttributes($user);
    }
}