<?php

namespace App\Repository;

use App\Entity\Banque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Banque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banque[]    findAll()
 * @method Banque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Banque::class);
    }

    /**
     * @return Banque[] Returns an array of Banque objects
     */
    public function findAllChequeBanque()
    {
        return $this->createQueryBuilder('b')
            ->select('b.nom as banque, COUNT(c) as cheques')
            ->join('b.cheques', 'c')
            ->groupBy('b')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Banque
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
