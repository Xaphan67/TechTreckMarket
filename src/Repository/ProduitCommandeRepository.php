<?php

namespace App\Repository;

use App\Entity\ProduitCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProduitCommande>
 *
 * @method ProduitCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitCommande[]    findAll()
 * @method ProduitCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitCommande::class);
    }
}
