<?php

namespace App\Repository;

use App\Entity\Blocks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blocks>
 *
 * @method Blocks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blocks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blocks[]    findAll()
 * @method Blocks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blocks::class);
    }

    public function save(Blocks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blocks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Blocks[] Returns an array of Blocks objects
     */
    public function findByInputString($value): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.enter_string LIKE %:val%')
            ->setParameter('val', $value)
            ->orderBy('b.attempts', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Blocks
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
