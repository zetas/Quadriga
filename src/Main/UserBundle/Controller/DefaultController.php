<?php

namespace Main\UserBundle\Controller;

use Main\MarketBundle\Entity\BTCTransactionDetail;
use Main\MarketBundle\Entity\ETFTransactionDetail;
use Main\MarketBundle\Entity\Transaction;
use Main\MarketBundle\Entity\WUTransactionDetail;
use Main\MarketBundle\Form\Type\BTCTransactionDetailFormType;
use Main\MarketBundle\Form\Type\ETFTransactionDetailFormType;
use Main\MarketBundle\Form\Type\WUTransactionDetailFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/verify", name="user_verify")
     * @Template
     */
    public function verifyAction() {
        return array();
    }

    /**
     * @Route("/withdraw", name="user_withdraw")
     * @Template
     */
    public function withdrawAction() {
        return array();
    }

    /**
     * @Route("/withdraw/fiat/{method}", name="user_withdraw_fiat")
     * @Template
     */
    public function fiatWithdrawAction(Request $request, $method) {
        if ($method != 'wu' && $method != 'etf')
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        $form = $this->createFormBuilder(null)
            ->add('amount')
            ->add('ts','hidden',array('data'=>time()))
            ->add('next','submit')
            ->getForm();

        $form->handleRequest($request);
        $checkTrans = null;
        if ($form->isValid()) {
            $data = $form->getData();

            ($method == 'etf') ? $cName = 'ETF' : $cName = 'Western Union';

            $currency = $this->getDoctrine()->getRepository('Main\MarketBundle\Entity\Currency')
                ->findOneBy(array('name' => $cName))
            ;

            $flatFee = $currency->getWithdrawFlatFee();
            $percentFee = $currency->getWithdrawPercentFee();

            $cost = (($data['amount'] * $percentFee) + $data['amount']) + $flatFee;

            $svc = $this->get('main_market.orderFulfill');

            $checkTrans = $svc->checkTransaction('buy',$cost,0,$this->getUser());

            $data['amount'] = $cost;

            $token = urlencode(base64_encode(serialize($data)));

            if (!$checkTrans)
                return $this->redirect($this->generateUrl('user_withdraw_fiat_confirm',array('method' => $method, 'token' => $token)));
        }

        ($method == 'etf') ? $methodStr = 'ETF/Wire Transfer' : $methodStr = "Western Union";

        return array('form' => $form->createView(), 'method' => $methodStr, 'allowed' => $checkTrans);
    }

    /**
     * @Route("/withdraw/fiat/{method}/{token}/confirm", name="user_withdraw_fiat_confirm")
     * @Template
     */
    public function fiatWithdrawConfirmAction(Request $request, $method,$token) {
        if ($method != 'etf' && $method != 'wu')
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('user_withdraw_fiat', array('method' => $method)));

        $svc = $this->get('main_market.orderFulfill');

        $tmpTransaction = new Transaction();
        $tmpTransaction->setTransactionType('withdrawal');


        if ($method == "wu") {
            $wutd = new WUTransactionDetail();
            $wutd->setTransaction($tmpTransaction)
                ->setAmount($data['amount'])
            ;
            $form = $this->createForm(new WUTransactionDetailFormType(), $wutd);
        } else {
            $etftd = new ETFTransactionDetail();
            $etftd->setTransaction($tmpTransaction)
                ->setAmount($data['amount'])
            ;
            $form = $this->createForm(new ETFTransactionDetailFormType(), $etftd);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $td = $form->getData();
            $pin = $form->get('pin')->getData();
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            if ($user->getPin() == $pin) {

                $transaction = $svc->newTransaction(
                    'fiat',
                    -$td->getAmount(),
                    0,
                    $user,
                    'withdrawal',
                    $method,
                    true
                );

                $td->setTransaction($transaction);

                $em->persist($td);

                $em->flush();

                $transaction->addTransactionDetail($td);



                return $this->redirect($this->generateUrl('fos_user_profile_show'));
            }
        }

        ($method == 'etf') ? $methodStr = 'ETF/Wire Transfer' : $methodStr = "Western Union";

        return array('form' => $form->createView(), 'method' => $methodStr);
    }

    /**
     * @Route("/withdraw/digital", name="user_withdraw_digital")
     * @Template
     */
    public function digitalWithdrawAction(Request $request) {
        $form = $this->createFormBuilder(null)
            ->add('amount')
            ->add('ts','hidden',array('data'=>time()))
            ->add('next','submit')
            ->getForm();

        $form->handleRequest($request);
        $checkTrans = null;
        if ($form->isValid()) {
            $data = $form->getData();
            $currency = $this->getDoctrine()->getRepository('Main\MarketBundle\Entity\Currency')
                ->findOneBy(array('name' => 'Bitcoin'))
            ;

            $flatFee = $currency->getWithdrawFlatFee();
            $percentFee = $currency->getWithdrawPercentFee();

            $cost = (($data['amount'] * $percentFee) + $data['amount']) + $flatFee;

            $svc = $this->get('main_market.orderFulfill');

            $checkTrans = $svc->checkTransaction('sell',0,$cost,$this->getUser());

            $data['amount'] = $cost;

            $token = urlencode(base64_encode(serialize($data)));

            if (!$checkTrans)
                return $this->redirect($this->generateUrl('user_withdraw_digital_confirm',array('token' => $token)));
        }

        return array('form' => $form->createView(), 'allowed' => $checkTrans);
    }

    /**
     * @Route("/withdraw/digital/{token}/confirm", name="user_withdraw_digital_confirm")
     * @Template
     */
    public function digitalWithdrawConfirmAction(Request $request, $token) {
        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('user_withdraw_digital'));

        $svc = $this->get('main_market.orderFulfill');

        $tmpTransaction = new Transaction();
        $tmpTransaction->setTransactionType('withdrawal');

        $btc = new BTCTransactionDetail();

        $btc->setAmount($data['amount'])
            ->setTransaction($tmpTransaction)
        ;

        $form = $this->createForm(new BTCTransactionDetailFormType(), $btc);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $td = $form->getData();
            $pin = $form->get('pin')->getData();
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            if ($user->getPin() == $pin) {

                $transaction = $svc->newTransaction(
                    'digital',
                    0,
                    -$td->getAmount(),
                    $user,
                    'withdrawal',
                    null,
                    true
                );

                $td->setTransaction($transaction);

                $em->persist($td);

                $em->flush();

                $transaction->addTransactionDetail($td);

                return $this->redirect($this->generateUrl('fos_user_profile_show'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/transfer", name="user_transfer")
     * @Template
     */
    public function transferAction(Request $request) {

        $configSvc = $this->get('main_site.configSvc');

        $fpData = $configSvc->getConfigFor('transfer');

        $feePercent = $fpData->getValue();

        $form = $this->createFormBuilder(null)
            ->add('username','text')
            ->add('amount', 'money', array('currency' => null))
            ->add('balance', 'choice', array('choices' => array('digital' => 'BTC', 'fiat' => 'USD'), 'expanded' => true, 'multiple' => false))
            ->add('pin', 'text')
            ->add('transfer', 'submit')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $targetUser = $this->getDoctrine()->getRepository('Main\UserBundle\Entity\User')
                ->findOneBy(array('username'=>$data['username']))
            ;

            $user = $this->getUser();

            if ($user->getPin() != $data['pin'])
                return $this->redirect($this->generateUrl('user_transfer'));

            if ($targetUser == $user)
                return $this->redirect($this->generateUrl('user_transfer'));

            $svc = $this->get('main_market.orderFulfill');

            if ($targetUser == null)
                return $this->redirect($this->generateUrl('user_transfer'));

            if ($data['balance'] == "digital") {
                if ($user->getBtcBalance() < $data['amount'])
                    return $this->redirect($this->generateUrl('user_transfer'));

                $svc->newTransaction('digital',0,-$data['amount'], $user, 'transfer');
                $svc->newTransaction('digital',0,$data['amount'], $targetUser, 'transfer');
            } else {
                $feeAmt = $data['amount'] * $feePercent;
                $totalFeeCost = $data['amount'] - $feeAmt;

                if ($user->getFiatBalance() < $data['amount'])
                    return $this->redirect($this->generateUrl('user_transfer'));

                $svc->newTransaction('fiat',-$data['amount'],0, $user, 'transfer');
                $svc->newTransaction('fiat',$totalFeeCost,0, $targetUser, 'transfer');
            }
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return array('form'=>$form->createView());
    }
}
