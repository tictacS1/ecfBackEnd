<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    /**
     * @return Emprunt[] Returns an array of Livre objects
     */
    public function findAllByLast(): array
    {
        return $this->createQueryBuilder('ep')
            ->andWhere('ep.date_emprunt IS NOT null')
            ->orderBy('ep.date_emprunt', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer
     * @return Emprunt[] Returns an array of Livre objects
     */
    public function findAllByEmpr($emp): array
    {
        return $this->createQueryBuilder('ep')
            ->innerJoin("ep.emprunteur", "em")
            ->andWhere('em.id = :val')
            ->setParameter('val', $emp)
            ->andWhere('ep.date_emprunt IS NOT null')
            ->orderBy('ep.date_emprunt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param integer
     * @return Emprunt[] Returns an array of Livre objects
     */
    public function findByLivre($livre): array
    {
        return $this->createQueryBuilder('ep')
            ->innerJoin("ep.livre", "li")
            ->andWhere('li.id = :val')
            ->setParameter('val', $livre)
            ->andWhere('ep.date_emprunt IS NOT null')
            ->orderBy('ep.date_emprunt', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunt[] Returns an array of Livre objects
     */
    public function findAllByLastRetour(): array
    {
        return $this->createQueryBuilder('ep')
            ->andWhere('ep.date_retour IS NOT null')
            ->orderBy('ep.date_retour', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunt[] Returns an array of Livre objects
     */
    public function findAllByNull(): array
    {
        return $this->createQueryBuilder('ep')
            ->andWhere('ep.date_retour IS null')
            ->orderBy('ep.date_emprunt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    
    //    /**
    //     * @return Emprunt[] Returns an array of Emprunt objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Emprunt
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
