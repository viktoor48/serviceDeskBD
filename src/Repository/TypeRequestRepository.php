<?php

namespace App\Repository;

use App\Entity\TypeRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeRequest>
 *
 * @method TypeRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeRequest[]    findAll()
 * @method TypeRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeRequest::class);
    }

//    /**
//     * @return TypeRequest[] Returns an array of TypeRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeRequest
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
