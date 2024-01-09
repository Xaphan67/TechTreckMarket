<?php

namespace App\Repository;

use App\Entity\ProduitCaracteristiqueTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProduitCaracteristiqueTechnique>
 *
 * @method ProduitCaracteristiqueTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitCaracteristiqueTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitCaracteristiqueTechnique[]    findAll()
 * @method ProduitCaracteristiqueTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitCaracteristiqueTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitCaracteristiqueTechnique::class);
    }

//    /**
//     * @return ProduitCaracteristiqueTechnique[] Returns an array of ProduitCaracteristiqueTechnique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProduitCaracteristiqueTechnique
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
