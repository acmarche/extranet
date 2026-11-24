<?php

namespace AcMarche\Patrimoine\Repository;

use AcMarche\Patrimoine\Doctrine\OrmCrudTrait;
use AcMarche\Patrimoine\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Image::class);
    }

    /**
     * @return Image[]
     */
    public function findAllSorted(): array
    {
        $queryBuilder = $this->createQueryBuilder('image');

        return
            $queryBuilder
                ->addOrderBy('image.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }
}
