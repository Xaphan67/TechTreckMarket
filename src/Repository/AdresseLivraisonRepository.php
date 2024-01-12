<?php

namespace App\Repository;

use App\Entity\AdresseLivraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseLivraison>
 *
 * @method AdresseLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseLivraison[]    findAll()
 * @method AdresseLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseLivraison::class);
    }

    public function setOthersAsNotFavorite($user, $currentId): int
    {
       return $this->createQueryBuilder('al')
            ->update()
            ->set('al.preferee', '0')
            ->where('al.utilisateur = :user')
            ->andWhere('al.id != :currentId')
            ->setParameter('user', $user)
            ->setParameter('currentId', $currentId)
            ->getQuery()
            ->execute();
       ;
    }

    public function findAllOrdered($user, $value)
    {
        return $this->findBy(['utilisateur' => $user], [$value => 'DESC']);
    }
}
