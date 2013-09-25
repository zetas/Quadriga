<?php

namespace Main\UserBundle\Controller;

use Main\MarketBundle\Entity\ETFTransactionDetail;
use Main\MarketBundle\Entity\WUTransactionDetail;
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

        $form = $this->createFormBuilder(null)
            ->add('amount')
            ->add('ts','hidden',array('data'=>time()))
            ->add('next','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $svc = $this->get('main_market.orderFulfill');

            $checkTrans = $svc->checkTransaction('buy',$data['amount'],0,$this->getUser());

            $token = urlencode(base64_encode(serialize($data)));

            if (!$checkTrans)
                return $this->redirect($this->generateUrl('user_withdraw_fiat_confirm',array('method' => $method, 'token' => $token)));
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/withdraw/fiat/{method}/{token}/confirm", name="user_withdraw_fiat_confirm")
     * @Template
     */
    public function fiatWithdrawConfirmAction(Request $request, $method,$token) {

    }

    /**
     * @Route("/withdraw/digital", name="user_withdraw_digital")
     * @Template
     */
    public function digitalWithdrawAction() {

    }

    /**
     * @Route("/transfer", name="user_transfer")
     * @Template
     */
    public function transferAction(Request $request) {

        $feePercent = 0.005;

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
