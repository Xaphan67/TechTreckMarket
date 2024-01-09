<?php

namespace App\Repository;

use App\Entity\CaracteristiqueTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CaracteristiqueTechnique>
 *
 * @method CaracteristiqueTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaracteristiqueTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaracteristiqueTechnique[]    findAll()
 * @method CaracteristiqueTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaracteristiqueTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaracteristiqueTechnique::class);
    }

//    /**
//     * @return CaracteristiqueTechnique[] Returns an array of CaracteristiqueTechnique objects
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

//    public function findOneBySomeField($value): ?CaracteristiqueTechnique
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
