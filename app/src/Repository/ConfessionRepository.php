<?php

namespace App\Repository;

use App\Entity\Confession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Confession::class);
    }

    public function getARandomConfession()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT u FROM App\Entity\Confession u ORDER BY u.id');

        // returns an array of Product objects
        return $query->execute()[0];
    }

    public function registerAConfession($body)
    {
        $em = $this->getEntityManager();
        $confession = new Confession();
        $confession->setBody($body);
        $em->persist($confession);
        $em->flush();
    }
}
