<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/20/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Main\MarketBundle\Entity\Offer;

class OrderService {
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getEm() {
        return $this->em;
    }

    public function fulfill(Offer $offer) {

    }

    public function getInstantPrice($amount,$direction) {
        $offers = $this->getFulfillList($amount,$direction);

        $total_cost = 0;
        $total_amt = 0;

        /**
         * We know:
         * offers is in order of price, whatever that means for $direction, either cheapest or most expensive at top
         * We need to get a current price average for all offers potentially consumed.
         */

        foreach ($offers as $o) {

        }
    }

    public function getFulfillList($amount, $direction) {
        //50, buy

        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addRootEntityFromClassMetadata('Main\MarketBundle\Entity\Offer', 'o');
        $rsm->addJoinedEntityFromClassMetadata('Main\UserBundle\Entity\User', 'u', 'o', 'user', array('id' => 'user_id'));

        $query = $this->em->createNativeQuery('CALL GetFulfillList(?,?)', $rsm);
        $query->setParameter(1, $amount);
        $query->setParameter(2, $direction);

        $offers = $query->getResult();

        return $offers;
    }
}