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

    public const CORRECT = 'correct';
    public const LOWER   = 'lower';
    public const HIGHER  = 'higher';

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
      * @param GameEntity $game
      * @param int $value
      *
      * @return string
      */
    public function submitGuess(UserEntity $user, GameEntity $game, int $value)
    {
        $guess = new GuessEntity();
        $guess
            ->setUser($user)
            ->setGame($game)
            ->setValue($value)
            ->setInsertedOn(new \DateTime());

        $this->em->persist($guess);
        $this->em->flush();

        if ($value === $game->getValue()) {
            $result = self::CORRECT;

            $game->setActive(false);
            $game->setWinner($user);
            $this->em->persist($game);
            $this->em->flush();            
        } elseif ($value > $game->getValue()) {
            $result = self::LOWER;
        } else {
            $result = self::HIGHER;
        }

        return $result;
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

    /**
      * @param UserEntity $user
      * @param GameEntity $game
      *
      * @return bool
      */
    public function isUserTurn(UserEntity $user, GameEntity $game)
    {
        $guessAmount  = count($game->getGuesses());        
        $players      = $game->getUsers();
        $activePlayer = $players[$guessAmount % count($players)];
        
        if ($activePlayer->getId() === $user->getId())
        {
          return true;
        }

        return false;
    }
}
