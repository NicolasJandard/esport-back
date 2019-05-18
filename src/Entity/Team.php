<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemon_one;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemon_two;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemon_three;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemon_four;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemon_five;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemon_six;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tier;

    /**
     * @ORM\Column(type="integer")
     */
    private $creator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemonOne(): ?int
    {
        return $this->pokemon_one;
    }

    public function setPokemonOne(?int $pokemon_one): self
    {
        $this->pokemon_one = $pokemon_one;

        return $this;
    }

    public function getPokemonTwo(): ?int
    {
        return $this->pokemon_two;
    }

    public function setPokemonTwo(?int $pokemon_two): self
    {
        $this->pokemon_two = $pokemon_two;

        return $this;
    }

    public function getPokemonThree(): ?int
    {
        return $this->pokemon_three;
    }

    public function setPokemonThree(?int $pokemon_three): self
    {
        $this->pokemon_three = $pokemon_three;

        return $this;
    }

    public function getPokemonFour(): ?int
    {
        return $this->pokemon_four;
    }

    public function setPokemonFour(?int $pokemon_four): self
    {
        $this->pokemon_four = $pokemon_four;

        return $this;
    }

    public function getPokemonFive(): ?int
    {
        return $this->pokemon_five;
    }

    public function setPokemonFive(?int $pokemon_five): self
    {
        $this->pokemon_five = $pokemon_five;

        return $this;
    }

    public function getPokemonSix(): ?int
    {
        return $this->pokemon_six;
    }

    public function setPokemonSix(?int $pokemon_six): self
    {
        $this->pokemon_six = $pokemon_six;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getTier(): ?string
    {
        return $this->tier;
    }

    public function setTier(?string $tier): self
    {
        $this->tier = $tier;

        return $this;
    }

    public function getCreator(): ?int
    {
        return $this->creator;
    }

    public function setCreator(int $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
}
