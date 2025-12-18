<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Carpooling;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Carpooling>
 */
class CarpoolingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpooling::class);
    }

public function searchCarpool($startTown, $endTown, $passenger, $startAt,$hour,$price,$traveltime,$electric,$note)
{
    $qb = $this->createQueryBuilder('c');

    if ($startTown) {
        $qb->andWhere('c.startTown = :startTown')->setParameter('startTown', $startTown);
    }
    if ($endTown) {
        $qb->andWhere('c.endTown = :endTown')->setParameter('endTown', $endTown);
    }
    if ($passenger) {
        $qb->andWhere('c.passenger >= :passenger')->setParameter('passenger', $passenger);
    }
    if ($startAt) {
        $qb->andWhere('c.startAt = :startAt')->setParameter('startAt', $startAt);
    }
    if ($hour) {
        $qb->andWhere('c.hour = :hour')->setParameter('hour', $hour);
    }


if ($price) {
    $qb->leftJoin('App\Entity\CreditTransaction','t','WITH','t.carpooling = c' )
    ->groupBy('c.id');

    if ($price === 'asc') {
        $qb->orderBy('MIN(t.amount)', 'ASC');
    } else {
        $qb->orderBy('MIN(t.amount)', 'DESC');
    }
}

    if ($traveltime) {
        [$min, $max] = explode('-', $traveltime);
        $qb->andWhere('c.traveltime BETWEEN :min AND :max')
            ->setParameter('min', $min)
            ->setParameter('max', $max);
    }

    if ($electric !== null) {
        $qb->andWhere('c.electric = :electric')->setParameter('electric', $electric);
    }

    if ($note) {
        $qb->andWhere('c.note >= :note')->setParameter('note', $note);
    }

    return $qb->getQuery()->getResult();
}

public function findByUserOrParticipation(User $user)
{
    return $this->createQueryBuilder('c')
        ->leftJoin('c.participants', 'p')
        ->addSelect('p') 
        ->where('c.user = :user')
        ->orWhere('p.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}


}
