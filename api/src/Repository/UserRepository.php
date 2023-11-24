<?php

namespace App\Repository;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findActiveUsersSinceDate(DateTimeImmutable $startDate)
    {
        return $this->createQueryBuilder('u')
            ->where('date(u.createdAt) >= :start_date')
            ->andWhere('u.isActive = true')
            ->setParameter('start_date', $startDate->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }

    /**
     * Paginate users.
     *
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function paginate(int $page, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('u')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}
