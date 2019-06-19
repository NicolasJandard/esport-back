<?php

namespace App\Controller;

use App\Entity\UserGoogle;
use App\Entity\Pokemon;
use App\Entity\Team;
use App\Entity\Comment;
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
        $team = $this->registerTeam($pokemonsId, $data['comment'], $data['tier'], $user->getId(), $data['name']);

        if($team) {
            return new Response(Response::HTTP_OK);
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
            ->findBy(array(), array('id' => 'DESC'), 4);
        $teamsToShow = array();
        for($i = 0; $i < count($teamsObject); $i++) {
            $teamsToShow[$i] = $this->composeTeamJson($teamsObject[$i]);
        }
        $teamsToShow = $this->serializer->serialize($teamsToShow, 'json');
        return new Response($teamsToShow, Response::HTTP_OK);
    }

    /**
     * @Route("/team/details/{id}", name = "details_team", methods = { "GET" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function getDetailsTeam($id) {
        $teamObject = $this->em->getRepository(Team::class)->findOneBy(['id' => $id]);
        $team = $this->composeTeamJson($teamObject);
        return new Response($this->serializer->serialize($team, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/team/comment", name = "comment_team", methods = { "POST" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function teamComment(Request $request) {
        $data = json_decode($request->getContent(), true);

        $user = $this->em->getRepository(UserGoogle::class)->findOneBy(['email' => $data['author']]);
        if(!$user) {
            return new Response("User unknown", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $today = new \DateTime(date('Y-m-d H:i:s'));
        $comment = new Comment();

        $comment->setText($data['text'])
            ->setAuthor($user->getId())
            ->setTeam($data['teamId'])
            ->setRate($data['rate'])
            ->setDate($today);

        $this->em->persist($comment);
        $this->em->flush();

        return $this->getCommentsTeam($data['teamId']);
    }

    /**
     * @Route("/team/comments/{teamId}", name = "comments_team", methods = { "GET" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function getCommentsTeam($teamId) {
        $commentsObject = $this->em->getRepository(Comment::class)->findBy(['team' => $teamId]);
        $commentsToShow = array();
        for($i = 0; $i < count($commentsObject); $i++) {
            $commentsToShow[$i] = $this->composeCommentJson($commentsObject[$i]);
        }
        return new Response($this->serializer->serialize($commentsToShow, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/team/user/{mail}", name = "user_teams", methods = { "GET" })
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception | \LogicException
     */
    public function getUserTeams($mail) {
        $user = $this->em->getRepository(UserGoogle::class)->findOneBy(['email' => $mail]);
        $teamsObject = $this->em->getRepository(Team::class)->findBy(['creator' => $user->getId()]);
        $teamsToShow = array();
        for($i = 0; $i < count($teamsObject); $i++) {
            $teamsToShow[$i] = $this->composeTeamJson($teamsObject[$i]);
        }
        return new Response($this->serializer->serialize($teamsToShow, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/team/delete/{id}", name = "delete_team", methods = { "GET" })
     *
     * @param Request $request
     *
     * @throws \Exception | \LogicException
     */
    public function deleteTeam($id) {
        $team = $this->em->getRepository(Team::class)->findOneById($id);
        $comments = $this->em->getRepository(Comment::class)->findBy(['team' => $team->getId()]);
        $user = $this->em->getRepository(UserGoogle::class)->findOneById($team->getCreator());

        $this->em->remove($team);
        for($i = 0; $i < count($comments); $i++) {
            $this->em->remove($comments[$i]);
        }
        $this->em->flush();

        return $this->getUserTeams($user->getEmail());
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

    private function registerTeam($pokemonsId, $comment, $tier, $userId, $name) {
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
                ->setName($name)
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

    private function composeTeamJson($team) {
        $teamToShow["teamId"] = $team->getId();
        $teamToShow["name"] = $team->getName();
        $teamToShow["pokemonOne"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonOne()]);
        $teamToShow["pokemonTwo"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonTwo()]);
        $teamToShow["pokemonThree"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonThree()]);
        $teamToShow["pokemonFour"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonFour()]);
        $teamToShow["pokemonFive"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonFive()]);
        $teamToShow["pokemonSix"] = $this->em->getRepository(Pokemon::class)->findOneBy(['id' =>$team->getPokemonSix()]);            
        $creator = $this->em->getRepository(UserGoogle::class)->findOneBy(['id' =>$team->getCreator()]);
        $teamToShow["creator"] = $creator->getFirstName()." ".$creator->getLastName();
        $teamToShow["creatorImage"] = $creator->getAvatar();
        $teamToShow["comment"] = $team->getComment();
        $teamToShow["tier"] = $team->getTier();

        return $teamToShow;
    }

    private function composeCommentJson($comment) {
        $commentToShow["text"] = $comment->getText();
        $commentToShow["rate"] = $comment->getRate();
        $commentToShow["date"] = $comment->getDate();         
        $author = $this->em->getRepository(UserGoogle::class)->findOneBy(['id' =>$comment->getAuthor()]);
        $commentToShow["author"] = $author->getFirstName()." ".$author->getLastName();
        $commentToShow["authorImage"] = $author->getAvatar();
        $commentToShow["authorEmail"] = $author->getEmail();
        return $commentToShow;
    }
}
