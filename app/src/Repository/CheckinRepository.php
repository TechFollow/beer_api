<?php

namespace App\Repository;

use App\Entity\Beer;
use App\Entity\Checkin;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Checkin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Checkin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Checkin[]    findAll()
 * @method Checkin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Checkin::class);
    }

    // /**
    //  * @return Checkin[] Returns an array of Checkin objects
    //  */

    public function getByMark()
    {
        $rawSql = "
            SELECT checkin.mark, beer.name as beer, beer.abv, beer.ibu, beer.date_create, beer.date_update, brasserie.name as brasserie
            FROM checkin
            LEFT JOIN beer ON checkin.beer_id = beer.id
            LEFT JOIN brasserie ON beer.brasserie_id = brasserie.id
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);

        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Checkin
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
