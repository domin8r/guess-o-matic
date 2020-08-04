<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="games")
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Guess::class, mappedBy="game")
     */
    private $guesses;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->guesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Guess[]
     */
    public function getGuesses(): Collection
    {
        return $this->guesses;
    }

    public function addGuess(Guess $guess): self
    {
        if (!$this->guesses->contains($guess)) {
            $this->guesses[] = $guess;
            $guess->setGame($this);
        }

        return $this;
    }

    public function removeGuess(Guess $guess): self
    {
        if ($this->guesses->contains($guess)) {
            $this->guesses->removeElement($guess);
            // set the owning side to null (unless already changed)
            if ($guess->getGame() === $this) {
                $guess->setGame(null);
            }
        }

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
