<?php

namespace App\Repository;

use DateTime;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunteur>
 *
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }

    /**
     * @return Emprunteur[] Returns an array of Livre objects
     */
    public function findAllByName(): array
    {
        return $this->createQueryBuilder('em')
            ->andWhere('em.id IS NOT null')
            ->orderBy('em.nom, em.prenom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunteur[] Returns an array of Livre objects
     */
    public function findById(): array
    {
        return $this->createQueryBuilder('em')
            ->andWhere('em.user = 3')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string
     * @return Emprunteur[] Returns an array of Livre objects
     */
    public function findAllByField($mot): array
    {
        return $this->createQueryBuilder('em')
            ->andWhere("em.nom LIKE :val ")
            ->andWhere("em.prenom LIKE :val ")
            ->setParameter('val', '%' . $mot . '%')
            ->orderBy('em.nom, em.prenom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string
     * @return Emprunteur[] Returns an array of Livre objects
     */
    public function findAllByNum($mot): array
    {
        return $this->createQueryBuilder('em')
            ->andWhere("em.tel LIKE :val ")
            ->setParameter('val', '%' . $mot . '%')
            ->orderBy('em.nom, em.prenom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DateTime
     * @return Emprunteur[] Returns an array of Livre objects
     */
    public function findAllByDate($date): array
    {
        return $this->createQueryBuilder('em')
            ->innerJoin("em.emprunts", "ep")
            ->andWhere("ep.date_emprunt < :val ")
            ->setParameter('val', $date)
            ->orderBy('em.nom, em.prenom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Emprunteur[] Returns an array of Emprunteur objects
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

    //    public function findOneBySomeField($value): ?Emprunteur
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
