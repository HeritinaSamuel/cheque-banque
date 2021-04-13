<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats() {
        $cheques = $this->getChequesCount();
        $bancs   = $this->getBancsCount();

        return compact('cheques', 'bancs');
    }

    public function getBancsCount() {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Banque b')->getSingleScalarResult();
    }

    public function getChequesCount() {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Cheque c')->getSingleScalarResult();
    }

    public function getBancStats(): Array {
        return $this->manager->createQuery(
            'SELECT COUNT(c) as cheque, b.nom as banque
            FROM App\Entity\Cheque c 
            JOIN c.banques b
            GROUP BY b '
        )
        ->getResult();
    }

}