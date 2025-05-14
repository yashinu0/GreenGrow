<?php

namespace App\Repository;

use App\Entity\LivreurPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LivreurPosition>
 *
 * @method LivreurPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivreurPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivreurPosition[]    findAll()
 * @method LivreurPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreurPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivreurPosition::class);
    }

    public function save(LivreurPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LivreurPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatestByCommande($commande): ?LivreurPosition
    {
        return $this->createQueryBuilder('lp')
            ->andWhere('lp.commande = :commande')
            ->setParameter('commande', $commande)
            ->orderBy('lp.lastUpdate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
