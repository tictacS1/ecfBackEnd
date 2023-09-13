<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function findAllByTitre(): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.titre IS NOT null')
            ->orderBy('l.titre', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string
     * @return Livre[] Returns an array of Livre objects
     */
   public function findAllByField($mot): array
   {
       return $this->createQueryBuilder('l')
           ->andWhere("l.titre LIKE :val ")
           ->setParameter('val', '%'.$mot.'%')
           ->orderBy('l.titre', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult();
       ;
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function findAllByAuthor(): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.auteur = 87')
            ->orderBy('l.titre', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string
     * @return Livre[] Returns an array of Livre objects
     */
   public function findAllByGenre($genre): array
   {
       return $this->createQueryBuilder('l')
           ->innerJoin("l.genres", "g")
           ->andWhere("g.nom LIKE :val ")
           ->setParameter('val', '%'.$genre.'%')
           ->orderBy('l.titre', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult();
       ;
    }

}



