<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/23/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Main\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;



class LoadUser extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $users = array(
            array('david', 'david', 'della vecchia', 'nikedude', '1234', 'davidoftheold@gmail.com', '500', '50', true),
            array('ddv', 'david', 'della vecchia', 'nikedude', '1234', 'ddv@qubitlogic.net', '500', '50', false),
        );

        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($users as $u) {
            $newUser = $userManager->createUser();
            //$newUser = new User();

            $newUser->setUsername($u[0])
                ->setFirstName($u[1])
                ->setLastName($u[2])
                ->setPlainPassword($u[3])
                ->setPin($u[4])
                ->setEmail($u[5])
                ->setFiatBalance($u[6])
                ->setBtcBalance($u[7])
                ->setSuperAdmin($u[8])
                ->setEnabled(true)
            ;

            $userManager->updateUser($newUser);
        }
    }

    public function getOrder() {
        return 1;
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
}