<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {   
        parent::__construct($registry, Utilisateur::class);
    }
    
    public function findInactiveUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.isActive = :isActive')
            ->setParameter('isActive', false)
            ->getQuery()
            ->getResult();
    }




      public function search(string $query): JsonResponse
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like('u.nom_user', ':query'),
            $qb->expr()->like('u.prenom_user', ':query'),
            $qb->expr()->like('u.email_user', ':query'),
            $qb->expr()->like('u.role_user', ':query')

        ))
        ->setParameter('query', '%' . $query . '%');

        $users = $qb->getQuery()->getResult();

        if (!empty($users)) {
            $jsonData = [];
            foreach ($users as $user) {
                $jsonData[] = [
                    'id_user' => $user->getIdUser(),
                    'nom_user' => $user->getNomUser(),
                    'prenom_user' => $user->getPrenomUser(),
                    'email_user' => $user->getEmailUser(),
                    'role_user' => $user->getRoleUser(),
                ];
            }

            return new JsonResponse($jsonData);
        } else {
            return new JsonResponse([]);
        }
    }




    //    /**
    //     * @return Utilisateur[] Returns an array of Utilisateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Utilisateur
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
