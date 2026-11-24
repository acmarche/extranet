<?php

namespace AcMarche\Patrimoine\Repository;

use AcMarche\Patrimoine\Doctrine\OrmCrudTrait;
use AcMarche\Patrimoine\Entity\TypePatrimoine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypePatrimoine|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePatrimoine|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePatrimoine[]    findAll()
 * @method TypePatrimoine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePatrimoineRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, TypePatrimoine::class);
    }

    public function getForList(): QueryBuilder
    {
        return $this->createQueryBuilder('type')
            ->addOrderBy('type.nom', 'ASC');
    }
}
