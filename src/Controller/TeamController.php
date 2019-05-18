<?php

namespace App\Controller;

use App\Entity\UserGoogle;
use App\Entity\Pokemon;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     */
    public function __construct(\Google_Client $client,
      EntityManagerInterface $em, SerializerInterface $serializer) {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/team/create", name = "create_team", methods = { "POST" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function createTeam(Request $request) {
        $data = json_decode($request->getContent(), true);

        $user = $this->em->getRepository(UserGoogle::class)->findOneBy(['email' => $data['creator']]);
        if(!$user) {
            return new Response("User unknown", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $pokemonsId = $this->getPokemonsId($data['pokemons']);
        $team = $this->registerTeam($pokemonsId, $data['comment'], $data['tier'], $user->getId());

        if($team) {
            return new Response("Team created", Response::HTTP_OK);
        }
        else {
            return new Response("Team already exists for this user", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/team/top", name = "top_teams", methods = { "GET" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function getTopteams() {
        $teamsObject = $this->em->getRepository(Team::class)
            ->findBy(array(), array('id' => 'DESC'), 5);
        $teamsToShow = array();
        for($i = 0; $i < count($teamsObject); $i++) {
            $team = $teamsObject[$i];
            $teamsToShow[$i]["pokemonOne"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonOne()]);
            $teamsToShow[$i]["pokemonTwo"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonTwo()]);
            $teamsToShow[$i]["pokemonThree"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonThree()]);
            $teamsToShow[$i]["pokemonFour"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonFour()]);
            $teamsToShow[$i]["pokemonFive"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonFive()]);
            $teamsToShow[$i]["pokemonSix"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonSix()]);            
            $creator = $this->em->getRepository(UserGoogle::class)->findOneBy(['id' =>$team->getCreator()]);
            $teamsToShow[$i]["creator"] = $creator->getFirstName()." ".$creator->getLastName();
            $teamsToShow[$i]["creatorImage"] = $creator->getAvatar();
            $teamsToShow[$i]["comment"] = $team->getComment();
        }
        $teamsToShow = $this->serializer->serialize($teamsToShow, 'json');
        return new Response($teamsToShow, Response::HTTP_OK);
    }

    private function getPokemonsId($pokemons) {
        $pokemonsId = array();

        for($i = 0; $i < count($pokemons); $i++) {
            $pokemon = new Pokemon();
            $actives = $pokemons[$i]['actives'];

            $exists = $this->em->getRepository(Pokemon::class)->findOneBy([
                'name' => $pokemons[$i]['name'],
                'passive' => $pokemons[$i]['passive'],
                'ability_one' => $actives[0],
                'ability_two' => $actives[1],
                'ability_three' => $actives[2],
                'ability_four' => $actives[3]
            ]);

            if(!$exists) {
                $pokemon->setName($pokemons[$i]['name'])
                    ->setImage($pokemons[$i]['img'])
                    ->setPassive($pokemons[$i]['passive'])
                    ->setAbilityOne($actives[0])
                    ->setAbilityTwo($actives[1])
                    ->setAbilityThree($actives[2])
                    ->setAbilityFour($actives[3]);

                $this->em->persist($pokemon);
                $this->em->flush();
                $pokemonsId[$i] = $pokemon->getId();
            }
            else {
                $pokemonsId[$i] = $exists->getId();
            }
        }
        return $pokemonsId;
    }

    private function registerTeam($pokemonsId, $comment, $tier, $userId) {
        $exists = $this->em->getRepository(Team::class)->findOneBy([
            'pokemon_one' => $pokemonsId[0],
            'pokemon_two' => $pokemonsId[1],
            'pokemon_three' => $pokemonsId[2],
            'pokemon_four' => $pokemonsId[3],
            'pokemon_five' => $pokemonsId[4],
            'pokemon_six' => $pokemonsId[5],
            'creator' => $userId
        ]);

        if(!$exists) {
            $team = new Team();

            $team->setCreator($userId)
                ->setComment($comment)
                ->setTier($tier)
                ->setPokemonOne($pokemonsId[0])
                ->setPokemonTwo($pokemonsId[1])
                ->setPokemonThree($pokemonsId[2])
                ->setPokemonFour($pokemonsId[3])
                ->setPokemonFive($pokemonsId[4])
                ->setPokemonSix($pokemonsId[5]);

            $this->em->persist($team);
            $this->em->flush();

            return true;
        }
        else {
            return false;
        }
    }
}
