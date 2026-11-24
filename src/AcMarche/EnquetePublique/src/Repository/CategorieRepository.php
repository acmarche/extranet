<?php

namespace AcMarche\EnquetePublique\Repository;

use AcMarche\EnquetePublique\Doctrine\OrmCrudTrait;
use AcMarche\EnquetePublique\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Categorie::class);
    }

    /**
     * @return Categorie[]
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
