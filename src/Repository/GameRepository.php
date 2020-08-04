<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
      * @param User $user
      * @param bool $active
      *
      * return Game[]
    */    
    public function getGamesForUser(User $user, $active): array
    {
        return $this->createQueryBuilder('g')
                    ->innerJoin('g.users', 'u')
                    ->andWhere('g.active = :active')
                    ->setParameter('active', $active)
                    ->andWhere('u.id = :user_id')
                    ->setParameter('user_id', $user->getId())
                    ->getQuery()
                    ->getResult();
    }    
}
