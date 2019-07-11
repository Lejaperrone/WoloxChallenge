<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{

    public function testGetUsersAction()
    {
        $client = static::createClient();

        $client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testGetUserActionOk()
    {
        $client = static::createClient();

        $client->request('GET', '/users/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testGetUserActionNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/users/100');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

}