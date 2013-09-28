<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/26/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\SiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Main\SiteBundle\Entity\Config;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;



class LoadConfig extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $config = array(
            'Fees' => array(
                'Market Fee Percent' => 0.005,
                'User2User Transfer Fee Percent' => 0.005
            ),
            'Deposit Settings' =>array(
                'WU Target Name' => 'NAME',
                'WU Target Address' => 'ADDRESS',
                'WU Target City' => 'CITY',
                'WU Target State' => 'STATE',
                'WU Target Zip' => 'ZIP',
                'ETF Target Bank' => 'BANK NAME',
                'ETF Target Routing' => 'BANK ROUTING #',
                'ETF Target Account' => 'BANK ACCT #',
                'Bitcoin Address' => '1BBTRdQFt14iR4VPNntVGD6nn5oJ8tzbtt'
            )
        );


        foreach ($config as $heading => $c) {
            foreach ($c as $key => $val) {
                $con = new Config();
                $con->setHeading($heading);
                $con->setName($key)
                    ->setValue($val)
                ;
                $manager->persist($con);
            }
        }

        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
}

