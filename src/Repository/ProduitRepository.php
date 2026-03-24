<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByLibelleOrTexte(string $recherche): array {
        $qb = $this->createQueryBuilder('p');

        if (empty($recherche)) {
            return $qb->getQuery()->getResult();
        }
        $qb->where('p.libelle LIKE :recherche')
            ->orWhere('p.texte LIKE :recherche')
            ->setParameter('recherche', '%' . $recherche . '%');

        return $qb->getQuery()->getResult();
    }

    public function findTopVendus($limit = 3)
    {
        return $this->createQueryBuilder('p')
            ->join('p.ligneCommandes', 'lc') // Adapte le nom de l'association
            ->select('p as produit, SUM(lc.quantite) as total')
            ->groupBy('p.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
