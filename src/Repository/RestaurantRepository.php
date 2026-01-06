<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    /**
     * Recherche les restaurants par leur nom
     * @return Restaurant[]
     */
    public function findBySearch(string $search): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.nom LIKE :val')
            ->setParameter('val', '%' . $search . '%')
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByComplexSearch(?string $search, ?string $sort): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.avis', 'a')
            ->addSelect('AVG(a.note) as HIDDEN avg_note')
            ->addSelect('COUNT(a.id) as HIDDEN nb_avis')
            ->groupBy('r.id');

        if ($search) {
            $qb->andWhere('r.nom LIKE :val OR r.adresse LIKE :val')
                ->setParameter('val', '%' . $search . '%');
        }

        switch ($sort) {
            case 'note_desc':
                $qb->orderBy('avg_note', 'DESC');
                break;
            case 'note_asc':
                $qb->orderBy('avg_note', 'ASC');
                break;
            case 'avis_desc':
                $qb->orderBy('nb_avis', 'DESC');
                break;
            case 'avis_asc':
                $qb->orderBy('nb_avis', 'ASC');
                break;
            default:
                $qb->orderBy('r.nom', 'ASC');
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Restaurant[] Returns an array of Restaurant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Restaurant
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
