<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/23/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Main\MarketBundle\Entity\Offer;


class TestLoadOffers extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $countSell = 20;
        $countBuy = 20;

        $user = $manager->getRepository('Main\UserBundle\Entity\User')
            ->findOneBy(array('username' => 'ddv'));

        for ($i = 1; $i <= ($countSell+$countBuy); $i++) {

            $amt = mt_rand(0.2*10,5*10)/10;

            $offer = new Offer();
            if ($i <= 20) {
                $offer->setIsBuy(true);
                $minPrice = 124;
                $maxPrice = 127;
            } else {
                $offer->setIsBuy(false);
                $maxOffer = $manager->getRepository('Main\MarketBundle\Entity\Offer')
                    ->findOneBy(array('isBuy' => true),array('price' => 'DESC'))
                ;
                $minPrice = $maxOffer->getPrice();
                $maxPrice = ($minPrice+3);
            }

            $price = mt_rand($minPrice*10,$maxPrice*10)/10;
            $offer->setAmount($amt)
                ->setPrice($price)
                ->setUser($user)
            ;

            $manager->persist($offer);
            $manager->flush();
        }
    }

    public function getOrder() {
        return 3;
    }
}