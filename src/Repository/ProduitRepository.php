<?php
namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function countProductsByCategory(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('c.name as category', 'COUNT(p.id) as product_count')
            ->join('p.id_categories', 'c')
            ->groupBy('c.id');

        return $qb->getQuery()->getResult();
    }
}