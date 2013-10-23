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
     * @Route("/", name="order_index")
     * @Template
     */
    public function indexAction() {
        return array();
    }

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

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'transaction_expired'
            );
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));
        }

        $orderSvc = $this->get('main_market.orderFulfill');
        $configSvc = $this->get('main_site.configSvc');

        $fpData = $configSvc->getConfigFor('market');

        $feePercent = $fpData->getValue();

        if ($type == "instant") {
            $price = round($orderSvc->getInstantPrice($data['amount'], $direction),2);
        } else {
            $dbPrice = $orderSvc->getLimitPrice($data['amount'], $direction, false, $data['price']);
            ($dbPrice > 0) ? $price = round($dbPrice,2) : $price = round($data['price'],2);
        }

        $rawCost = ($data['amount'] * $price);
        $fee = ($rawCost * $feePercent);

        ($direction == "buy") ? $cost = ($rawCost + $fee) : $cost = ($rawCost - $fee);

        $costFormatted = number_format($cost, 2, '.', ',');
        $fee = number_format($fee, 2, '.', ',');

        $data['ts'] = time();
        $newToken = urlencode(base64_encode(serialize($data)));

        $checkResult = $orderSvc->checkTransaction($direction,$cost,$data['amount'],$this->getUser());

        ($checkResult == false) ? $confirmAllowed = true : $confirmAllowed = false;

        $rawCost = number_format($rawCost, 2, '.', ',');

        $form = $this->createFormBuilder(null, array('action' => $this->generateUrl('offer_complete',array('type' => $type, 'direction' => $direction, 'token' => $newToken))))
            ->add('pin','text')
            ->getForm();

        if ($checkResult != false) {
            if ($checkResult == 'limit')
                $failString = 'error_limit';
            elseif ($checkResult == 'balance')
                $failString = 'error_balance';
            else
                $failString = 'error_unknown';

            $this->get('session')->getFlashBag()->add(
                'error',
                $failString
            );
        }

        return array('type' => $type,
                     'direction' => $direction,
                     'amount' => $data['amount'],
                     'price' => $price,
                     'cost' => $costFormatted,
                     'totalWorth' => $rawCost,
                     'fee' => $fee,
                     'feePercent' => ($feePercent * 100),
                     'form' => $form->createView(),
                     'confirmAllowed' => $confirmAllowed,
        );
    }

    /**
     * @Route("/{type}/{direction}/complete/{token}", name="offer_complete")
     */
    public function completeAction(Request $request, $type, $direction, $token) {
        if (($direction != 'buy' && $direction != 'sell') || ($type != "instant" && $type != "limit"))
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));

        $form = $this->createFormBuilder(null)
            ->add('pin','text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $user = $this->getUser();

            if ($formData['pin'] != $user->getPin()) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'error_invalid_pin'
                );
                return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));
            }

        } else {
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));
        }

        $orderSvc = $this->get('main_market.orderFulfill');

        $fulfillResult = $orderSvc->fulfillOrder($type,$direction,$data, $this->getUser());

        if (!$fulfillResult) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'error_order_unknown'
            );
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));
        }


        $successStr = "Order for <strong>$data[amount] BTC</strong>";
        if (is_array($fulfillResult)) {
            $fulfillLimitAmt = $fulfillResult['amt'];
            $fulfillLimitPrice = number_format($fulfillResult['price'],2, '.', ',');
            $fulfillAmt = $data['amount'] - $fulfillLimitAmt;
            $successStr .= " was Partially Fulfilled for <strong>$fulfillAmt BTC</strong> and a Limit Order for the remainder of <strong>$fulfillLimitAmt BTC</strong> was created at price <strong>".'$'.$fulfillLimitPrice."</strong>.";
        } elseif ($fulfillResult === 'limit') {
            $successStr = "Limit Order for <strong>$data[amount] BTC</strong> Created.";
        } else {
            $successStr .= " Fulfilled Successfully.";
        }

        $this->get('session')->getFlashBag()->add(
            'success',
            $successStr
        );

        if ($fulfillResult === "limit") {
            $this->get('session')->getFlashBag()->add(
                'info',
                'info_limit'
            );
        }


        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }


}
