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
    public function getUsersAction()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        $view = $this->view($users, Response::HTTP_OK);
        return $this->handleView($this->view($view));
    }

    /**
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        $view = $this->view($user, Response::HTTP_OK);
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

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        $user->setName($name);
        $user->setEmail($email);
        $user->setImage($image);

        $em->persist($user);
        $em->flush();

        $view = $this->view($user, Response::HTTP_OK);
        return $this->handleView($this->view($view));
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUserAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        //TODO Que devuelvo?
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

        $em = $this->getDoctrine()->getManager();

        $user = New User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setImage($image);

        $em->persist($user);
        $em->flush();

        $view = $this->view($user, Response::HTTP_OK);
        return $this->handleView($this->view($view));
    }
}