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

    public function setOthersAsNotFavorite($user, $currentId): int
    {
       return $this->createQueryBuilder('af')
            ->update()
            ->set('af.preferee', '0')
            ->where('af.utilisateur = :user')
            ->andWhere('af.id != :currentId')
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
