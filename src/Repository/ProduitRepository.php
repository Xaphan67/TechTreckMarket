<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findByTrademarkOrName($search) {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.marque', 'm', 'WITH', 'm.id = p.marque')
            ->where('p.designation LIKE :search')
            ->orWhere('m.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
       ;
    }

    public function findByFilters($category, $name, $avaiable, $trademarks, $priceMin, $priceMax) {
        $query =  $this->createQueryBuilder('p')
            ->innerJoin('p.categorie', 'c', 'WITH', 'c.id = p.categorie')
            ->innerJoin('p.marque', 'm', 'WITH', 'm.id = p.marque')
            ->where('c.nom = :category')
            ->setParameter('category', $category);

            if ($name != null) {
                $query->andWhere('p.designation LIKE :name')
                ->setParameter('name', '%' . $name . '%');
            }

            if ($avaiable) {
                $query->andWhere('p.disponible = true');
            }

            if (count($trademarks) > 0) {
                $query->andWhere('m.nom IN (:trademarks)')
                ->setParameter('trademarks', $trademarks);
            }

            if ($priceMin != null) {
                $query->andWhere('p.prix >= :priceMin')
                ->setParameter('priceMin', $priceMin);
            }

            if ($priceMax != null) {
                $query->andWhere('p.prix <= :priceMax')
                ->setParameter('priceMax', $priceMax);
            }

            $query->getQuery()->getResult();
            return $query;
       ;
    }
}
