<?php

namespace App\Repository;

use App\Entity\Resident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Resident>
 *
 * @method Resident|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resident|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resident[]    findAll()
 * @method Resident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResidentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resident::class);
    }



    /**
     * @return Resident[] Returns an array of Resident objects
     */
    public function findAlphabeticalFirsts($limit = 5): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.room', 'room')
            ->leftJoin('r.referent', 'referent')
            ->orderBy('r.firstName', 'ASC')
            ->setMaxResults($limit)
//            ->setParameter('limit', $limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Resident[] Returns an array of Resident objects
     */
    public function findByRoom($value): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.room', 'room')
            ->andWhere('room.id = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


//    /**
//     * @return Resident[] Returns an array of Resident objects
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

//    public function findOneBySomeField($value): ?Resident
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
