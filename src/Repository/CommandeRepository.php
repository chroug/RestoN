<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Calcule le Chiffre d'Affaires total du restaurant
     */
    public function findChiffreAffaires(Restaurant $restaurant): float
    {
        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.total)')
            ->andWhere('c.restaurant = :resto')
            ->setParameter('resto', $restaurant)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : 0.0;
    }

    /**
     * Compte le nombre de commandes passées AUJOURD'HUI
     */
    public function countCommandesDuJour(Restaurant $restaurant): int
    {

        $debut = new \DateTime('today midnight');
        $fin   = new \DateTime('tomorrow midnight');

        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.restaurant = :resto')
            ->andWhere('c.date >= :debut')
            ->andWhere('c.date < :fin')
            ->setParameter('resto', $restaurant)
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
