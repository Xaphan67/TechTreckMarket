<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

            $query->andWhere('p.archive = false');

            $query->getQuery()->getResult();
            return $query;
       ;
    }

    public function findByFilters($category, $words, $avaiable, $trademarks, $priceMin, $priceMax, $orderCol, $orderDir) {
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

            if (($orderCol == "designation" || $orderCol == "marque" || $orderCol == "prix") && ($orderDir == "ASC" || $orderDir == "DESC")) {
                $orderDir = strtoupper($orderDir) === Criteria::ASC ? Criteria::ASC : Criteria::DESC;
                $query->addOrderBy('p.' . $orderCol, $orderDir, 'p');
            }

            $query->andWhere('p.archive = false');

            $query->getQuery()->getResult();
            return $query;
       ;
    }
}
