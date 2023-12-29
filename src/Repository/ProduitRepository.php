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

    public function findByTrademarkOrName($words) {
        $query = $this->createQueryBuilder('p')
            ->innerJoin('p.marque', 'm', 'WITH', 'm.id = p.marque');

            foreach ($words as $word) {
                $query->andWhere('p.designation LIKE :word')
                ->orWhere('m.nom LIKE :word')
                ->setParameter('word', '%' . $word . '%');
            };

            $query->getQuery()->getResult();
            return $query;
       ;
    }

    public function findByFilters($category, $words, $avaiable, $trademarks, $priceMin, $priceMax) {
        $query =  $this->createQueryBuilder('p')
            ->innerJoin('p.categorie', 'c', 'WITH', 'c.id = p.categorie')
            ->innerJoin('p.marque', 'm', 'WITH', 'm.id = p.marque')
            ->where('c.nom = :category')
            ->setParameter('category', $category);

            if (count($words) > 0) {
                foreach ($words as $word) {
                    $query->andWhere('p.designation LIKE :word')
                    ->setParameter('word', '%' . $word . '%');
                }
            }

            if ($avaiable) {
                $query->andWhere('p.stock > 0');
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
