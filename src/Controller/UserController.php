<?php


namespace App\Controller;

use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $user = $this->userService->getUser($id);
        $status = Response::HTTP_OK;
        if(!$user){
            $status = Response::HTTP_NOT_FOUND;
            $user = 'User with id '. $id .' not found';
        }
        return new View($user, $status);
    }

    /**
     * @Rest\Put("/users/{id}")
     */
    public function editUserAction(Request $request, $id)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $image = $request->get('image');

        $status = Response::HTTP_OK;

        $user = $this->userService->updateUser($id, $name, $email, $image);
        if(!$user){
            $status = Response::HTTP_NOT_FOUND;
            $user = 'User with id '. $id .' not found';
        }

        return new View($user, $status);
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUserAction($id)
    {
        $user = $this->userService->deleteUser($id);
        $status = Response::HTTP_NO_CONTENT;
        if(!$user){
            $status = Response::HTTP_NOT_FOUND;
            $user = 'User with id '. $id .' not found';
        }
        return new View($user, $status);
    }

    /**
     * @Rest\Post("/users")
     */
    public function createUserAction(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $image = $request->get('image');

        $user = $this->userService->addUser($name, $email, $image);
        $status = Response::HTTP_OK;
        if(!$user){
            $status = Response::HTTP_CONFLICT;
            $user = 'Name or email invalid';
        }

        return new View($user, $status);
    }
}