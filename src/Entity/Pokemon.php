<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PokemonRepository")
 */
class Pokemon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $passive;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ability_one;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ability_two;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ability_three;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ability_four;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPassive(): ?string
    {
        return $this->passive;
    }

    public function setPassive(?string $passive): self
    {
        $this->passive = $passive;

        return $this;
    }

    public function getAbilityOne(): ?string
    {
        return $this->ability_one;
    }

    public function setAbilityOne(?string $ability_one): self
    {
        $this->ability_one = $ability_one;

        return $this;
    }

    public function getAbilityTwo(): ?string
    {
        return $this->ability_two;
    }

    public function setAbilityTwo(?string $ability_two): self
    {
        $this->ability_two = $ability_two;

        return $this;
    }

    public function getAbilityThree(): ?string
    {
        return $this->ability_three;
    }

    public function setAbilityThree(?string $ability_three): self
    {
        $this->ability_three = $ability_three;

        return $this;
    }

    public function getAbilityFour(): ?string
    {
        return $this->ability_four;
    }

    public function setAbilityFour(?string $ability_four): self
    {
        $this->ability_four = $ability_four;

        return $this;
    }
}
