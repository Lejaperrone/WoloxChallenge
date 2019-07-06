<?php


namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractFOSRestController
{

    /**
     * @Rest\Get("/users")
     */
    public function getUserAction()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        $view = $this->view($users, Response::HTTP_OK);
        return $this->handleView($this->view($view));
    }

    public function editUserAction()
    {
    }

    public function deleteUserAction(Request $request)
    {
    }

    public function createUserAction()
    {
    }
}