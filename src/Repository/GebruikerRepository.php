<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use GemeenteAmsterdam\FixxxSchuldhulp\Entity\Gebruiker;
use Doctrine\ORM\QueryBuilder;

/**
 * Class GebruikerRepository
 *
 * @package GemeenteAmsterdam\FixxxSchuldhulp\Repository
 */
class GebruikerRepository extends ServiceEntityRepository
{
    const INACTIVE_MODIFIER = '-3 months';

    use PaginationTrait;

    /**
     * OrganisatieRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gebruiker::class);
        $this->setPaginationAlias('g');
        $this->addPaginationLeftJoin('g.organisaties', 'o');
        $this->addPaginationLeftJoin('g.teamGka', 't');
    }

    public function generatePaginationQueryForUser(Gebruiker $gebruiker, bool $inactive): Query
    {
        switch ($gebruiker->getType()) {
            case Gebruiker::TYPE_SHV_KEYUSER:
                return $this->generatePaginationQueryForKeyuser($gebruiker, $inactive);
            case Gebruiker::TYPE_GKA_APPBEHEERDER:
                return $this->generatePaginationQueryForGkaAppbeheerder($gebruiker, $inactive);
            case Gebruiker::TYPE_ADMIN:
                return $this->generatePaginationQueryForAdmin($gebruiker, $inactive);
        }
    }

    public function generatePaginationQueryForKeyuser(Gebruiker $gebruiker, $inactive): Query {
        $query =  $this->generatePaginationQuery(sprintf('%s WHERE o.id IN (:organisaties) AND g.type IN (:types) %s', $this->generatePaginationQueryDql(), $this->generateInactiveQueryPart($inactive)));
        $query->setParameter('organisaties', $gebruiker->getOrganisaties());
        $query->setParameter('types', [Gebruiker::TYPE_SHV, Gebruiker::TYPE_SHV_KEYUSER]);
        return $query;
    }

    public function generatePaginationQueryForGkaAppbeheerder(Gebruiker $gebruiker, $inactive): Query {
        $query =  $this->generatePaginationQuery(sprintf('%s WHERE g.type IN (:types) %s', $this->generatePaginationQueryDql(), $this->generateInactiveQueryPart($inactive)));
        $query->setParameter('types', [Gebruiker::TYPE_GKA, Gebruiker::TYPE_GKA_APPBEHEERDER, Gebruiker::TYPE_SHV, Gebruiker::TYPE_SHV_KEYUSER]);
        return $query;
    }

    public function generatePaginationQueryForAdmin(Gebruiker $gebruiker, $inactive): Query {
        $query =  $this->generatePaginationQuery(sprintf('%s WHERE g.type IN (:types) %s', $this->generatePaginationQueryDql(), $this->generateInactiveQueryPart($inactive)));
        $query->setParameter('types', [Gebruiker::TYPE_ADMIN, Gebruiker::TYPE_GKA, Gebruiker::TYPE_GKA_APPBEHEERDER, Gebruiker::TYPE_SHV, Gebruiker::TYPE_SHV_KEYUSER]);
        return $query;
    }

    protected function generateInactiveQueryPart(bool $inactive) {
        if ($inactive) {
            return 'AND g.enabled = false';
        }

        return 'AND g.enabled = true';
    }

    /**
     * @param int $page
     * @param int $pageSize
     *
     * @return Paginator
     */
    public function findAll($page = 0, $pageSize = 100): Paginator
    {
        $qb = $this->createQueryBuilder('gebruiker');
        $qb->orderBy('gebruiker.username', 'ASC');
        $qb->setFirstResult($page * $pageSize);
        $qb->setMaxResults($pageSize);

        return new Paginator($qb->getQuery());
    }

    /**
     * @return Doctrine\ORM\QueryBuilder
     */
    public function findAllRaw(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('gebruiker');
        $qb->orderBy('gebruiker.username', 'ASC');
        return $qb;
    }

    /**
     * @param array $type
     * @param int   $page
     * @param int   $pageSize
     *
     * @return Paginator
     */
    public function findAllByType(array $type, int $page = 0, int $pageSize = 100): Paginator
    {
        $qb = $this->createQueryBuilder('gebruiker');
        $qb->andWhere('gebruiker.type IN (:type)');
        $qb->setParameter('type', $type);
        $qb->orderBy('gebruiker.username', 'ASC');
        $qb->setFirstResult($page * $pageSize);
        $qb->setMaxResults($pageSize);
        return new Paginator($qb->getQuery());
    }

    /**
     * @param array $type
     * @param array $organisaties
     * @param int   $page
     * @param int   $pageSize
     *
     * @return Paginator
     */
    public function findAllByTypeAndOrganisatie(array $type, $organisaties, int $page = 0, int $pageSize = 100): Paginator
    {
        $qb = $this->findAllByTypeAndOrganisatieRaw($type, $organisaties, $page, $pageSize);
        $qb->setFirstResult($page * $pageSize);
        $qb->setMaxResults($pageSize);

        return new Paginator($qb->getQuery());
    }

    /**
     * @param array $type
     * @param array $organisaties
     *
     * @return QueryBuilder
     */
    public function findAllByTypeAndOrganisatieRaw(array $type, $organisaties): QueryBuilder
    {
        $qb = $this->createQueryBuilder('gebruiker');
        $qb->andWhere('gebruiker.type IN (:type)');
        $qb->setParameter('type', $type);

        $qb->innerJoin('gebruiker.organisaties', 'o');
        $qb->andWhere('o.id IN (:o_ids)');
        $qb->setParameter('o_ids', $organisaties);

        $qb->orderBy('gebruiker.naam', 'ASC');

        return $qb;
    }

    /**
     * @return QueryBuilder
     */
    public function findAllOnbekendeGebruikers(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('gebruiker');
        $qb->andWhere('gebruiker.type = (:type)');
        $qb->setParameter('type', Gebruiker::TYPE_ONBEKEND);

        $qb->orderBy('gebruiker.naam', 'ASC');

        return $qb;
    }

    /**
     * @param int $organisatieId
     *
     * @return Array
     */
    public function findAllGebruikersByOrganisatie(int $organisatieId): Array
    {
            $qb = $this->createQueryBuilder('g');
            $qb->innerJoin('g.organisaties','o');
            $qb->where($qb->expr()->eq('o.id', ':organisatie_id'));
            $qb->andWhere($qb->expr()->orX(
               $qb->expr()->eq('g.type', ':shv_keyuser'),
               $qb->expr()->eq('g.type', ':shv')
            ));
            $qb->setParameter('shv_keyuser', Gebruiker::TYPE_SHV);
            $qb->setParameter('shv', Gebruiker::TYPE_SHV_KEYUSER);
            $qb->setParameter('organisatie_id', $organisatieId);
            $qb->addOrderBy('g.username', 'ASC');
            $qb = $qb->getQuery()->getResult();
        return $qb;
    }

    /**
     * @param int $gebruikerId
     *
     * @return String
     */
    public function getGebruikerEmailById(int $gebruikerId): String
    {
        return $this->findOneBy(array('id' => $gebruikerId))->getEmail();
    }
}
