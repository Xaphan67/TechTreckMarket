<?php

namespace App\Repository;

use App\Entity\AdresseFacturation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseFacturation>
 *
 * @method AdresseFacturation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseFacturation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseFacturation[]    findAll()
 * @method AdresseFacturation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseFacturationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseFacturation::class);
    }
}
