<?php

namespace App\Repository;

use App\Entity\ConfigurationPC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigurationPC>
 *
 * @method ConfigurationPC|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigurationPC|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigurationPC[]    findAll()
 * @method ConfigurationPC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigurationPCRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigurationPC::class);
    }

//    /**
//     * @return ConfigurationPC[] Returns an array of ConfigurationPC objects
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

//    public function findOneBySomeField($value): ?ConfigurationPC
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
