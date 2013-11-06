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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class FiatController extends Controller
{
    /**
     * @Route("/deposit/etf", name="fiat_etf_deposit")
     * @Template
     */
    public function etfDepositAction(Request $request) {
        $form = $this->createFormBuilder(null)
            ->add('amount', 'money', array('currency' => 'USD', 'constraints' => array(new NotBlank(),new Range(array('min' => 0)))))
            ->add('ts','hidden',array('data'=>time()))
            ->add('Continue','submit')
            ->getForm();

        $form->handleRequest($request);
        $checkTrans = false;
        if ($form->isValid()) {
            $currency = $this->getDoctrine()
                ->getRepository('MainMarketBundle:Currency')
                ->findOneBy(array('name' => 'ETF'));

            $data = $form->getData();

            $flatFee = $currency->getDepositFlatFee();
            $percentFee = $currency->getDepositPercentFee();

            $cost = (($data['amount'] * $percentFee) + $data['amount']) + $flatFee;

            $user = $this->getUser();

            ($user->getVerified() == false) ? $checkTrans = 'limit' : null;

            $data['preFeeAmount'] = $data['amount'];

            $data['amount'] = number_format($cost, 2, '.', ',');

            $token = urlencode(base64_encode(serialize($data)));

            if (!$checkTrans) {
                return $this->redirect($this->generateUrl('fiat_etf_deposit_confirm',array('token' => $token)));
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'error_etf_deposit_unverified'
                );
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/deposit/etf/{token}/confirm", name="fiat_etf_deposit_confirm")
     * @Template
     */
    public function etfDepositConfirmAction(Request $request, $token) {
        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('fiat_etf_deposit'));

        $form = $this->createFormBuilder(null)
            ->add('amount', 'money', array('currency' => 'USD', 'data' => $data['amount'], 'constraints' => array(new NotBlank(),new Range(array('min' => 0)))))
            ->add('preFeeAmount', 'hidden', array('data' => $data['preFeeAmount']))
            ->add('pin','integer')
            ->add('Complete','submit')
            ->getForm();

        $form->handleRequest($request);
        $finished = false;
        $amount = 0;
        $targetData = array();
        if ($form->isValid()) {
            $data = $form->getData();
            $pin = $form->get('pin')->getData();
            $user = $this->getUser();
            $data['amount'] = number_format($data['amount'], 2, '.', ',');
            $em = $this->getDoctrine()->getManager();

            if ($user->getPin() == $pin) {

                $currency = $this->getDoctrine()
                    ->getRepository('MainMarketBundle:Currency')
                    ->findOneBy(array('name' => 'ETF'));

                $transaction = new Transaction();

                $transaction->setAmount($data['amount'])
                    ->setPreFeeAmount($data['preFeeAmount'])
                    ->setCurrency($currency)
                    ->setStatus('pending')
                    ->setUser($user)
                    ->setTransactionType('deposit')
                ;

                $em->persist($transaction);

                $em->flush();

                $finished = true;
                $amount = $data['amount'];

                $configSvc = $this->get('main_site.configSvc');

                $configData = $configSvc->getConfigFor('etf');

                $targetData = array(
                    'bank' => $configData['etf_bank']->getValue(),
                    'routing' => $configData['etf_routing']->getValue(),
                    'account' => $configData['etf_account']->getValue(),
                );
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'error_invalid_pin'
                );
            }
        }

        return array('form' => $form->createView(), 'finished' => $finished, 'amount' => $amount, 'target' => $targetData);
    }

    /**
     * @Route("/deposit/wu", name="fiat_wu_deposit")
     * @Template
     */
    public function wuDepositAction(Request $request) {
        $form = $this->createFormBuilder(null)
            ->add('amount', 'money', array('currency' => 'USD', 'constraints' => array(new NotBlank(),new Range(array('min' => 0)))))
            ->add('ts','hidden',array('data'=>time()))
            ->add('Continue','submit')
            ->getForm();

        $form->handleRequest($request);
        $checkTrans = false;
        if ($form->isValid()) {
            $currency = $this->getDoctrine()
                ->getRepository('MainMarketBundle:Currency')
                ->findOneBy(array('name' => 'Western Union'));

            $data = $form->getData();

            $flatFee = $currency->getDepositFlatFee();
            $percentFee = $currency->getDepositPercentFee();

            $cost = (($data['amount'] * $percentFee) + $data['amount']) + $flatFee;

            $user = $this->getUser();

            ($user->getVerified() == false) ? $checkTrans = 'limit' : null;

            $data['preFeeAmount'] = $data['amount'];
            $data['amount'] = number_format($cost, 2, '.', ',');

            $token = urlencode(base64_encode(serialize($data)));

            if (!$checkTrans) {
                return $this->redirect($this->generateUrl('fiat_wu_deposit_confirm',array('token' => $token)));
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'error_wu_deposit_unverified'
                );
            }
        }

        return array('form' => $form->createView(), 'allowed' => $checkTrans);
    }

    /**
     * @Route("/deposit/wu/{token}/confirm", name="fiat_wu_deposit_confirm")
     * @Template
     */
    public function wuDepositConfirmAction(Request $request, $token) {
        $data = unserialize(base64_decode(urldecode($token)));
        $delta = (time()-300);

        if (!array_key_exists('ts', $data) || $data['ts'] < $delta)
            return $this->redirect($this->generateUrl('fiat_wu_deposit'));

        $form = $this->createFormBuilder(null)
            ->add('amount', 'money', array('currency' => 'USD', 'data' => $data['amount'], 'constraints' => array(new NotBlank(),new Range(array('min' => 0)))))
            ->add('preFeeAmount', 'hidden', array('data' => $data['preFeeAmount']))
            ->add('pin','integer')
            ->add('Complete','submit')
            ->getForm();

        $form->handleRequest($request);
        $finished = false;
        $amount = 0;
        $targetData = array();
        if ($form->isValid()) {
            $data = $form->getData();
            $pin = $form->get('pin')->getData();
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $data['amount'] = number_format($data['amount'], 2, '.', ',');
            if ($user->getPin() == $pin) {

                $currency = $this->getDoctrine()
                    ->getRepository('MainMarketBundle:Currency')
                    ->findOneBy(array('name' => 'Western Union'));

                $transaction = new Transaction();

                $transaction->setAmount($data['amount'])
                    ->setPreFeeAmount($data['preFeeAmount'])
                    ->setCurrency($currency)
                    ->setStatus('pending')
                    ->setUser($user)
                    ->setTransactionType('deposit')
                ;

                $em->persist($transaction);

                $em->flush();

                $finished = true;
                $amount = $data['amount'];

                $configSvc = $this->get('main_site.configSvc');

                $configData = $configSvc->getConfigFor('wu');

                $targetData = array(
                    'name' => $configData['wu_name']->getValue(),
                    'address' => $configData['wu_address']->getValue(),
                    'city' => $configData['wu_city']->getValue(),
                    'state' => $configData['wu_state']->getValue(),
                    'zip' => $configData['wu_zip']->getValue(),
                );
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'error_invalid_pin'
                );
            }
        }

        return array('form' => $form->createView(), 'finished' => $finished, 'amount' => $amount, 'target' => $targetData);
    }

    /**
     * @Route("/deposit", name="fiat_deposit")
     * @Template
     */
    public function depositAction() {
        return array();
    }

}