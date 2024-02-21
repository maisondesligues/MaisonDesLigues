<?php

namespace App\Repository;

use App\Entity\CategorieChambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieChambre>
 *
 * @method CategorieChambre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieChambre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieChambre[]    findAll()
 * @method CategorieChambre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieChambre::class);
    }

//    /**
//     * @return CategorieChambre[] Returns an array of CategorieChambre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieChambre
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
