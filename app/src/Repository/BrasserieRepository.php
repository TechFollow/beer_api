<?php

namespace App\Repository;

use App\Entity\Brasserie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brasserie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brasserie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brasserie[]    findAll()
 * @method Brasserie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrasserieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brasserie::class);
    }

    // /**
    //  * @return Brasserie[] Returns an array of Brasserie objects
    //  */

    public function getByCountry()
    {
        $rawSql = "SELECT country, COUNT(*) as NUM FROM brasserie GROUP BY country ORDER BY NUM DESC";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);

        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Brasserie
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
