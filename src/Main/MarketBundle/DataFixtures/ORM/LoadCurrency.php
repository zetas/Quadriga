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
use Main\MarketBundle\Entity\Currency;


class LoadCurrency extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $currencies = array(
            array('ETF', 'fiat', 'USD', '$', '15', '0.001', '15', '0.001'),
            array('Western Union', 'fiat', 'USD', '$', '2', '0.02','15','0.02'),
            array('Bitcoin', 'digital', 'BTC', '',0,0,0,'0.005'),
            array('USD', 'fiat', 'USD', '$',0,0,0,0),
        );

        foreach ($currencies as $c) {
            $curr = new Currency();

            $curr->setName($c[0])
                ->setType($c[1])
                ->setTla($c[2])
                ->setSymbol($c[3])
                ->setDepositFlatFee($c[4])
                ->setDepositPercentFee($c[5])
                ->setWithdrawFlatFee($c[6])
                ->setWithdrawPercentFee($c[7])
            ;

            $manager->persist($curr);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }
}