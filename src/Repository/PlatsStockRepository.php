<?php

namespace App\Repository;

use App\Entity\PlatsStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlatsStock>
 *
 * @method PlatsStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatsStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatsStock[]    findAll()
 * @method PlatsStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatsStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlatsStock::class);
    }

    /**
     * Permet de sauvegarder ou mettre à jour un stock
     */
    public function save(PlatsStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Permet de supprimer une ligne de stock
     */
    public function remove(PlatsStock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Tu pourras ajouter ici tes propres requêtes plus tard.
    // Par exemple : Trouver tous les plats qui ont moins de 5 en quantité.
    /*
    public function findLowStock(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.quantite < :val')
            ->setParameter('val', $limit)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
