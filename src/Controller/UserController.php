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

        $this->logger->info(get_class($this) . 'createUserAction con name: ' . $name . ' email: ' . $email . ' image: ' . $image);

        try {
            $user = $this->userService->addUser($name, $email, $image);
        } catch (WoloxChallengeException $e) {
            $this->logger->error(get_class($this) . 'Error al crear usuario - ' . get_class($e) . ': ' . $e->getMessage());
            return new View($e->getMessage(), Response::HTTP_CONFLICT);
        }

        $this->logger->info(get_class($this) . 'Usuario creado correctamente - id: ' . $user->getId());
        return new View($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        $this->logger->info(get_class($this) . 'getUsersAction');
        $users = $this->userService->getAllUsers();
        $this->logger->info(get_class($this) . 'Cantidad de usuarios: ' . count($users));
        return new View($users, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id)
    {
        $this->logger->info(get_class($this) . 'getUserAction con id ' . $id);
        try {
            $user = $this->userService->getUser($id);
        } catch (WoloxChallengeException $e) {
            $this->logger->error(get_class($this) . 'Error al buscar usuario - ' . get_class($e) . ': ' . $e->getMessage());
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

        $this->logger->info(get_class($this) . 'editUserAction con id ' . $id . ' name: ' . $name . ' email: ' . $email . ' image: ' . $image);

        try {
            $user = $this->userService->updateUser($id, $name, $email, $image);
        } catch (WoloxChallengeException $e) {
            $this->logger->error(get_class($this) . 'Error al actualizar usuario - ' . get_class($e) . ': ' . $e->getMessage());
            return new View($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        $this->logger->info(get_class($this) . 'Usuario actualizado correctamente - id: ' . $user->getId());
        return new View($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUserAction($id)
    {
        $this->logger->info(get_class($this) . 'deleteUserAction con id ' . $id);
        try {
            $this->userService->deleteUser($id);
        } catch (WoloxChallengeException $e) {
            $this->logger->error(get_class($this) . 'Error al borrar usuario - ' . get_class($e) . ': ' . $e->getMessage());
            return new View($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        $this->logger->info(get_class($this) . 'Usuario borrado correctamente');
        return new View(null, Response::HTTP_NO_CONTENT);
    }
}