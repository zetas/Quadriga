<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/18/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Controller;

use Main\MarketBundle\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BitcoinController extends Controller
{
    /**
     * @Route("/deposit", name="bitcoin_deposit")
     * @Template
     */
    public function depositAction() {
        $bitcoinSVC = $this->get('main_user.bitcoinservice');
        $user = $this->getUser();

        $address = $bitcoinSVC->getNewAddress($user->getId());

        return array('address' => $address);
    }
}