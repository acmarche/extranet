<?php

namespace AcMarche\EnquetePublique\Repository;

use AcMarche\EnquetePublique\Doctrine\OrmCrudTrait;
use AcMarche\EnquetePublique\Entity\CategorieWp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieWp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieWp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieWp[]    findAll()
 * @method CategorieWp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieWpRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, CategorieWp::class);
    }

    /**
     * @return CategorieWp[]
     */
    public function findAllSorted(): array
    {
        $queryBuilder = $this->createQueryBuilder('categorie');

        return
            $queryBuilder
                ->addOrderBy('categorie.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }

}
