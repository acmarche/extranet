<?php

namespace AcMarche\QrCode\Repository;

use AcMarche\QrCode\Entity\QrCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QrCode>
 */
class QrCodeRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QrCode::class);
    }

    //    /**
    //     * @return QrCode[] Returns an array of QrCode objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findByUuid(string $uuid): ?QrCode
    {
        return $this
            ->createQueryBuilder('qr')->andWhere('qr.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $username
     * @return QrCode[]
     */
    public function findByUser(string $username): array
    {
        return $this
            ->createQueryBuilder('qr')->andWhere('qr.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }

}
