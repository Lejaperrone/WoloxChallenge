<?php


namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
        $view = $this->view($users, Response::HTTP_OK);
        return $this->handleView($this->view($view));
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
        }
        $view = $this->view($user, $status);
        return $this->handleView($this->view($view));
    }

    /**
     * @Rest\Put("/users/{id}")
     */
    public function editUserAction(Request $request, $id)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $image = $request->get('image');

        $user = $this->userService->getUser($id);
        if(!$user){
            $view = $this->view(null, Response::HTTP_NOT_FOUND);
            return $this->handleView($this->view($view));
        }

        $updatedUser = $this->userService->updateUser($user, $name, $email, $image);
        $view = $this->view($updatedUser, Response::HTTP_OK);

        return $this->handleView($this->view($view));
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUserAction($id)
    {
        $user = $this->userService->getUser($id);
        if(!$user){
            $view = $this->view(null, Response::HTTP_NOT_FOUND);
            return $this->handleView($this->view($view));
        }

        $this->userService->deleteUser($user);

        $view = $this->view(null, Response::HTTP_OK);
        return $this->handleView($this->view($view));
    }


    //Headers Content-Type application/json
    //Body {"name": "Alejo", "email": "lejaperrone@gmail.com", "image":"imagen"}
    /**
     * @Rest\Post("/users")
     */
    public function createUserAction(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $image = $request->get('image');

        $user = $this->userService->addUser($name, $email, $image);

        $view = $this->view($user, Response::HTTP_CREATED);
        return $this->handleView($this->view($view));
    }
}