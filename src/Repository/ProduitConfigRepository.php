<?php

namespace App\Repository;

use App\Entity\ProduitConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProduitConfig>
 *
 * @method ProduitConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitConfig[]    findAll()
 * @method ProduitConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitConfig::class);
    }
}
