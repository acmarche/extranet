<?php

namespace AcMarche\Patrimoine\Repository;

use AcMarche\Patrimoine\Doctrine\OrmCrudTrait;
use AcMarche\Patrimoine\Entity\Localite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Localite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Localite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Localite[]    findAll()
 * @method Localite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocaliteRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Localite::class);
    }

    public function getList(): QueryBuilder
    {
        return $this->createQueryBuilder('type')
            ->addOrderBy('type.nom', 'ASC');
    }

    /**
     * @return array|Localite[]
     */
    public function findAllSorted(): array
    {
        return $this->createQueryBuilder('localite')

                ->addOrderBy('localite.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }
}
