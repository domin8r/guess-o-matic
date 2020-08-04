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
      * @param GameEntity $game
      *
      * @return bool
    */ 
    public function userInGame(UserEntity $user, GameEntity $game)
    {
      $users = $game->getUsers();
      foreach ($users as $gameUser) {
        if ($user->getId() === $gameUser->getId()) {
          return true;
        }
      }

      return false;
    }

    /**
      * @param UserEntity $user
      * @param int $value
      *
      */
    public function storeGuess(UserEntity $user, int $value)
    {

    }

    /**
      * @param UserEntity $user
      * @param bool       $active
      *
      * @return GameEntity[]
    */  
    public function getGamesForUser(UserEntity $user, $active = true)
    {
      $games = $this->em
          ->getRepository(GameEntity::class)
          ->getGamesForUser($user, $active);

      return $games;
    }
}
