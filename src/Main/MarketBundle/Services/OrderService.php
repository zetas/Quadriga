<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/20/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Services;

use Doctrine\ORM\EntityManager;

class OrderService {
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getEm() {
        return $this->em;
    }
}