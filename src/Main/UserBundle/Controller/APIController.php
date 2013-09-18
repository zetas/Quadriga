<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/18/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class APIController extends Controller
{
    /**
     * @Route("/bitcoin/{token}", name="api_bitcoin_callback")
     * @Template
     */
    public function bitcoinCallbackAction($token, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $bitcoinSVC = $this->get('main_user.bitcoinservice');

        if (!$userData = $bitcoinSVC->validateCallback($token,$request->get('destination_address')))
            return array('confirmations' => 0);

        $value = $request->get('value') / 100000000;
        $confirmations = $request->get('confirmations');
        if ($confirmations >= 3) {
            if (is_array($userData)) {
                $uid = $userData[0];
            } else {
                $uid = $userData;
            }

            $user = $em->getRepository("MainUserBundle:User")
                ->find($uid)
            ;
            $user->incrementDigitalBalance($value);


            $em->flush($user);
        }

        return array('confirmations' => $confirmations);
    }
}