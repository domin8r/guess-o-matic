<?php

namespace App\Manager;

use Doctrine\ORM\EntityManager;

use App\Entity\User as UserEntity;
use App\Entity\Game as GameEntity;
use App\Entity\Guess as GuessEntity;

class Game
{
    /** @var EntityManager */
    protected $em;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return null|GameEntity
     */
    public function getGameById(int $id)
    {
      /** @var null|GameEntity $game */
      $game = $this->em
          ->getRepository(GameEntity::class)
          ->find($id);

      return $game;
    }

    /**
      * @param UserEntity $user
      * @param int $value
      *
      */
    public function storeGuess(UserEntity $user, int $value)
    {
        
    }
}
