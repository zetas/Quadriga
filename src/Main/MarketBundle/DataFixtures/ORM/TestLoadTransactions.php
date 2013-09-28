<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/28/13
 * <http://[github|bitbucket].com/zetas>
 */


namespace Main\MarketBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Main\MarketBundle\Entity\Transaction;


class TestLoadTransactions extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $countTrans = 0;

        $possibleTypes = array(
            'deposit',
            'withdrawal',
            'transfer',
            'market'
        );

        $user = $manager->getRepository('Main\UserBundle\Entity\User')
            ->findOneBy(array('username' => 'david'));

        for ($i = 1; $i <= $countTrans; $i++) {
            $minPrice = 1;
            $maxPrice = 250;

            $amt = mt_rand($minPrice*10,$maxPrice*10)/10;

            $typeNum = mt_rand(0,7);

            ($typeNum > 3) ? $typeNum = 3 : null;
            $type = $possibleTypes[$typeNum];

            if ($type == 'deposit')
                $cName = 'ETF';
            elseif ($type == 'withdrawal')
                $cName = 'Western Union';
            else
            $cName = 'USD';

            $currency = $manager->getRepository('MainMarketBundle:Currency')
                ->findOneBy(array('name' => $cName))
            ;

            $transaction = new Transaction();

            $transaction->setAmount($amt)
                ->setUser($user)
                ->setTransactionType($type)
                ->setCurrency($currency)
                ->setStatus('confirmed')
            ;

            $manager->persist($transaction);
            $manager->flush();
        }
    }

    public function getOrder() {
        return 4;
    }
}