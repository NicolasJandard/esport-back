<?php

namespace App\Tests\Controller;

use App\Controller\GoogleController;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;

class GoogleControllerTest extends TestCase {

  public function testRegisterSuccess() {
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $serializer = $this->createMock(SerializerInterface::class);
    $gController = new GoogleController(new \Google_Client(), $entityManager, $serializer);
    $user = new User();
    $user->setEmail('email')
         ->setFirstName('given_name')
         ->setLastName('family_name')
         ->setAvatar('picture')
         ->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
    $payload = array(
      'email' => 'email',
      'given_name' => 'given_name',
      'family_name' => 'family_name',
      'picture' => 'picture');

    $gUser = $gController->register($payload);
    $this->assertEquals($user, $gUser);
  }

  public function testRegisterFailed() {
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $serializer = $this->createMock(SerializerInterface::class);
    $gController = new GoogleController(new \Google_Client(), $entityManager, $serializer);
    $payload = array();
    $gResult = $gController->register($payload);
    $this->assertEquals(false, $gResult);
  }

  /*public function testLoginSuccess() {
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $serializer = $this->createMock(SerializerInterface::class);
    $gController = new GoogleController(new \Google_Client(), $entityManager, $serializer);
    $request = new Request();
    $response = $gController->login($request);
    $this->assertEquals(new Response($user, Response::HTTP_OK), $response);
  }*/

  public function testLoginUserNotFound() {
    $this->expectException(\Exception::class);
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $serializer = $this->createMock(SerializerInterface::class);
    $gController = new GoogleController(new \Google_Client(), $entityManager, $serializer);
    $request = new Request();
    $response = $gController->login($request);
    $this->assertEquals(new \Exception('User not found', 404), $response);
  }

  public function testLoginNoTokenID() {
    $this->expectException(\Exception::class);
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $serializer = $this->createMock(SerializerInterface::class);
    $gController = new GoogleController(new \Google_Client(), $entityManager, $serializer);
    $request = new Request(); //Simule un payload vide
    $response = $gController->login($request);
    $this->assertEquals(new \Exception('No token ID found', 404), $response);
  }
}
