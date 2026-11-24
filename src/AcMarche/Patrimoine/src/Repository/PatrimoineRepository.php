<?php

namespace AcMarche\Patrimoine\Repository;

use AcMarche\Patrimoine\Doctrine\OrmCrudTrait;
use AcMarche\Patrimoine\Entity\Patrimoine;
use AcMarche\Patrimoine\Entity\Statut;
use AcMarche\Patrimoine\Entity\TypePatrimoine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Patrimoine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patrimoine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patrimoine[]    findAll()
 * @method Patrimoine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatrimoineRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Patrimoine::class);
    }

    /**
     * @return Patrimoine[]
     */
    public function findAllSorted():array
    {
        $queryBuilder = $this->createQueryBuilder('patrimoine');

        return
            $queryBuilder
                ->addOrderBy('patrimoine.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }

    /**
     * @return Patrimoine[]
     */
    public function search(?string $nom, ?string $localite, ?TypePatrimoine $typePatrimoine, ?Statut $statut):array
    {
        $queryBuilder = $this->createQueryBuilder('patrimoine');

        if ($nom) {
            $queryBuilder->andWhere('patrimoine.nom LIKE :nom')
                ->setParameter('nom', '%'.$nom.'%');
        }

        if ($localite) {
            $queryBuilder->andWhere('patrimoine.localite = :localite')
                ->setParameter('localite', $localite);
        }

        if ($typePatrimoine instanceof TypePatrimoine) {
            $queryBuilder->andWhere('patrimoine.typePatrimoine = :type')
                ->setParameter('type', $typePatrimoine);
        }

        if ($statut instanceof Statut) {
            $queryBuilder->andWhere('patrimoine.statut = :statut')
                ->setParameter('statut', $statut);
        }

        return
            $queryBuilder
                ->addOrderBy('patrimoine.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }
}
