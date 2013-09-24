<?php

namespace Main\MarketBundle\Controller;

use Main\MarketBundle\Entity\Offer;
use Main\MarketBundle\Form\Type\InstantOfferFormType;
use Main\MarketBundle\Form\Type\LimitOfferFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/{type}/{direction}", name="offer")
     * @Template
     */
    public function offerAction(Request $request, $type, $direction) {
        if (($direction != 'buy' && $direction != 'sell') || ($type != "instant" && $type != "limit"))
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        if ($type == "instant") {
            $form = $this->createFormBuilder(null)
                ->add('amount', 'money', array('currency' => null,'label' => 'Amount of BTC'))
                ->add('ts', 'hidden', array('data' => time()))
                ->getForm();
        } else {
            $form = $this->createFormBuilder(null)
                ->add('amount', 'money', array('currency' => null,'label' => 'Amount of BTC'))
                ->add('price', 'money', array('currency' => 'USD'))
                ->add('ts', 'hidden', array('data' => time()))
                ->getForm();
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $token = urlencode(base64_encode(serialize($data)));
            return $this->redirect($this->generateUrl('offer_confirm', array('type' => $type, 'direction' => $direction, 'token' => $token)));
            //return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return array('form' => $form->createView(), 'type' => $type, 'direction' => $direction);
    }

    /**
     * @Route("/{type}/{direction}/confirm/{token}", name="offer_confirm")
     * @Template
     */
    public function confirmAction($type, $direction, $token) {
        if (($direction != 'buy' && $direction != 'sell') || ($type != "instant" && $type != "limit"))
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));

        $orderSvc = $this->get('main_market.orderFulfill');

        if ($type == "instant") {
            $price = round($orderSvc->getInstantPrice($data['amount'], $direction),2);
        } else {
            $dbPrice = $orderSvc->getLimitPrice($data['amount'], $direction, false, $data['price']);
            ($dbPrice > 0) ? $price = round($dbPrice,2) : $price = round($data['price'],2);
        }

        $rawCost = ($data['amount'] * $price);
        $fee = ($rawCost * 0.005);

        ($direction == "buy") ? $cost = ($rawCost + $fee) : $cost = ($rawCost - $fee);

        $cost = number_format($cost, 2, '.', ',');
        $fee = number_format($fee, 2, '.', ',');

        $data['ts'] = time();
        $newToken = urlencode(base64_encode(serialize($data)));

        $user = $this->getUser();

        if ($direction == 'buy') {
            if ($user->getFiatBalance() >= $cost)
                $confirmAllowed = true;
            else
                $confirmAllowed = false;
        } else {
            if ($user->getBtcBalance() >= $data['amount'])
                $confirmAllowed = true;
            else
                $confirmAllowed = false;
        }
        $rawCost = number_format($rawCost, 2, '.', ',');
        return array('type' => $type,
                     'direction' => $direction,
                     'amount' => $data['amount'],
                     'price' => $price,
                     'cost' => $cost,
                     'totalWorth' => $rawCost,
                     'fee' => $fee,
                     'token' => $newToken,
                     'confirmAllowed' => $confirmAllowed
        );
    }

    /**
     * @Route("/{type}/{direction}/complete/{token}", name="offer_complete")
     */
    public function completeAction($type, $direction, $token) {
        if (($direction != 'buy' && $direction != 'sell') || ($type != "instant" && $type != "limit"))
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));

        $orderSvc = $this->get('main_market.orderFulfill');

        $fulfillResult = $orderSvc->fulfillOrder($type,$direction,$data, $this->getUser());

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * @Route("/", name="offer_index")
     * @Route("/{max}", name="offer_index_max")
     * @Template()
     */
    public function viewAction($max = 100) {
        $buyOffers = $this->getDoctrine()
            ->getRepository('MainMarketBundle:Offer')
            ->findBy(array('isBuy' => true, 'active' => true),array('price' => 'DESC'),$max);

        $sellOffers = $this->getDoctrine()
            ->getRepository('MainMarketBundle:Offer')
            ->findBy(array('isBuy' => false, 'active' => true),array('price' => 'ASC'),$max);

        if ($max < 100)
            return $this->render('MainMarketBundle:Default:viewContent.html.twig',array('buyOffers' => $buyOffers, 'sellOffers' => $sellOffers, 'max' => $max));

        return array('buyOffers' => $buyOffers, 'sellOffers' => $sellOffers, 'max' => $max);
    }
}
