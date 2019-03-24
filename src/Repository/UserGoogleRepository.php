<?php

namespace App\Repository;

use App\Entity\UserGoogle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserGoogle|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGoogle|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGoogle[]    findAll()
 * @method UserGoogle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGoogleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserGoogle::class);
    }

    // /**
    //  * @return UserGoogle[] Returns an array of UserGoogle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserGoogle
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
