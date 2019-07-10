<?php


namespace App\Controller;

use App\Exception\WoloxChallengeException;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class UserController extends AbstractFOSRestController
{

    private $logger;
    private $userService;

    public function __construct(LoggerInterface $logger, UserService $userService)
    {
        $this->logger = $logger;
        $this->userService = $userService;
    }

    /**
     * @Rest\Post("/users")
     */
    public function createUserAction(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $image = $request->get('image');

        try {
            $user = $this->userService->addUser($name, $email, $image);
        } catch (WoloxChallengeException $e) {
            return new View($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return new View($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        $users = $this->userService->getAllUsers();
        return new View($users, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id)
    {
        try {
            $user = $this->userService->getUser($id);
        } catch (WoloxChallengeException $e) {
            return new View($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new View($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/users/{id}")
     */
    public function editUserAction(Request $request, $id)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $image = $request->get('image');

        try {
            $user = $this->userService->updateUser($id, $name, $email, $image);
        } catch (WoloxChallengeException $e) {
            return new View($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new View($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUserAction($id)
    {
        try {
            $this->userService->deleteUser($id);
        } catch (WoloxChallengeException $e) {
            return new View($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new View(null, Response::HTTP_NO_CONTENT);
    }
}