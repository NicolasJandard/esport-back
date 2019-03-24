<?php

namespace App\Controller;

use App\Entity\UserGoogle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /** @var \Google_Client */
    private $client;

    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param \Google_Client $client
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     */
    public function __construct(\Google_Client $client,
      EntityManagerInterface $em, SerializerInterface $serializer) {
        $this->client = $client;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/connect/google", name = "connect_google", methods = { "POST" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function login(Request $request) {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];

        if($token) {
            $payload = $this->client->verifyIdToken($token);

            if($payload !== false) {
                $email = $payload['email'];
                $user = $this->em->getRepository(UserGoogle::class)->findOneBy(['email' => $email]);

                if(!$user) {
                    $user = $this->register($payload);
                }

                $user = $this->serializer->serialize($user, 'json');
                return new Response($user, Response::HTTP_OK);

            }
            else {
                throw new \Exception('User not found', 404);
            }
        }
        else {
            throw new \Exception('No token ID found', 404);
        }
    }

    /** {@inheritdoc} */
    public function register($payload = []) {
        if(empty($payload)) {
            return false;
        }

        $user = new UserGoogle();
        $today = new \DateTime(date('Y-m-d H:i:s'));
        $user
            ->setEmail($payload['email'])
            ->setFirstName($payload['given_name'])
            ->setLastName($payload['family_name'])
            ->setAvatar($payload['picture'])
            ->setCreatedAt($today);

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
}
