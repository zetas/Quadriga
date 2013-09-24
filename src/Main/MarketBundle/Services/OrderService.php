<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/20/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Main\MarketBundle\Entity\Offer;
use Main\MarketBundle\Entity\Transaction;
use Main\UserBundle\Entity\User;

class OrderService {
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getEm() {
        return $this->em;
    }

    public function fulfillOrder($type, $direction, $data, User $user) {
        if ($type == "instant") {
            $priceData = $this->getInstantPrice($data['amount'], $direction, true);
            $price = round($priceData[0],2);
            $offers = $priceData[1];
        } else {
            $priceData = $this->getFulfillListDetails($data['amount'],$direction, true, round($data['price'],2));
            ($priceData['price'] > 0) ? $price = $priceData['price'] : $price = round($data['price'],2);
            $offers = $priceData['offers'];
        }

        $rawCost = ($data['amount'] * $price);
        $feePercent = 0.005;
        $fee = ($rawCost * $feePercent);

        ($direction == "buy") ? $cost = ($rawCost + $fee) : $cost = ($rawCost - $fee);

        $cost = round($cost,2);

        if ($direction == "buy" && ($user->getFiatBalance() < $cost))
            return false;

        if ($direction == "sell" && ($user->getBtcBalance() < $data['amount']))
            return false;

        if (count($offers) > 0 && $offers != null) {
            $total_amt = 0;
            foreach ($offers as $o) {
                $offerUser = $o->getUser();
                $partial = $o->getPartial();
                if ($partial) {
                    $originalAmt = $partial;
                    $currentAmt = $o->getAmount();
                    $price = $o->getPrice();

                    $chunk = $originalAmt - $currentAmt;
                    $offerValue = ($chunk * $price);
                    $amount = $chunk;
                } else {
                    $offerValue = $o->getValue();
                    $amount = $o->getAmount();
                }

                if ($direction == "buy") {
                    //fulfilling sell orders
                    $payOut = ($offerValue - ($offerValue * $feePercent));
                    $this->newTransaction('fiat',$payOut,0,$offerUser);
                } else {
                    //fulfilling buy orders
                    $this->newTransaction('digital',0,$amount,$offerUser);
                }
                $this->em->persist($o);
                (!$partial) ? $this->em->remove($o) : null;
                $this->em->flush();

                $total_amt += $amount;

            }
            if ($total_amt < $data['amount']) {
                $partialAmount = ($data['amount'] - $total_amt);
                $this->newLimitOffer($direction,$partialAmount,$price,$user);
                $total_amt += $partialAmount;
            }
            if ($direction == "buy") {
                $this->newTransaction('fiat',-$cost,0,$user);
                $this->newTransaction('digital',0,$total_amt,$user);
            } else {
                $this->newTransaction('fiat',$cost,0,$user);
                $this->newTransaction('digital',0,-$total_amt,$user);
            }

            //Pay fees, increment btc, so forth for initiating user
        } else {
            $this->newLimitOffer($direction,$data['amount'],$price, $user);
            ($direction == "buy") ? $type = "fiat" : $type = "digital";
            $this->newTransaction($type,-$cost,-$data['amount'],$user);
        }

        return true;
    }

    public function newLimitOffer($direction, $amount, $price, User $user) {

        ($direction == "buy") ? $buy = true : $buy = false;

        $offer = new Offer();

        $offer->setAmount($amount)
            ->setPrice($price)
            ->setUser($user)
            ->setIsBuy($buy)
        ;

        $this->em->persist($offer);
        $this->em->flush($offer);
    }

    public function newTransaction($type, $cost, $amount, User $user) {
        $transaction = new Transaction();
        $this->em->refresh($user);
        ($type == "fiat") ? $cName = 'USD' : $cName = "Bitcoin";

        $currency = $this->em->getRepository('MainMarketBundle:Currency')
            ->findOneBy(array('name' => $cName));

        $cost = round($cost,2);
        $amount = round($amount,2);

        if ($type == "fiat") {
            $user->incrementFiatBalance($cost);
            $amt = $cost;
        } else {
            $user->incrementDigitalBalance($amount);
            $amt = $amount;
        }

        $transaction->setTransactionType('market')
            ->setUser($user)
            ->setAmount($amt)
            ->setCurrency($currency)
            ->setStatus('completed')
        ;

        $this->em->persist($transaction);

        $this->em->flush();
    }

    public function getInstantPrice($amount,$direction, $returnOffers = false) {
        $data = $this->getFulfillListDetails($amount,$direction, $returnOffers);

        if ($returnOffers)
            return array($data['price'], $data['offers']);

        return $data['price'];
    }

    public function getLimitPrice($amount,$direction, $returnOffers = false, $price = 0.0) {
        $data = $this->getFulfillListDetails($amount,$direction, $returnOffers, $price);

        if ($returnOffers)
            return array($data['price'], $data['offers']);

        return $data['price'];
    }

    public function getFulfillListDetails($amount, $direction, $returnOffers = false, $price = 0.0) {
        $offers = $this->getFulfillList($amount,$direction, $price);

        $total_cost = 0;
        $total_amt = 0;

        /**
         * We know:
         * offers is in order of price, whatever that means for $direction, either cheapest or most expensive at top
         * We need to get a current price average for all offers potentially consumed.
         */
        $i = 1;
        foreach ($offers as $o) {
            $amt = $o->getAmount();
            $cost = $o->getValue();

            if ($i < count($offers)) {
                $total_amt += $amt;
                $total_cost += $cost;
            } else {
                if (($total_amt + $amt) > $amount) {
                    $price = $o->getPrice();
                    $remainder = ($amount - $total_amt);

                    $total_amt += $remainder;
                    $total_cost += ($remainder * $price);

                    $o->setAmount(($amt-$remainder));
                    $o->setPartial($amt);

                } else if (($total_amt + $amt) == $amount) {
                    $total_cost += $cost;
                    $total_amt += $amt;
                }
            }
            $i++;
        }

        if ($total_amt == 0 && $total_cost == 0)
            $price = 0;
        else
            $price = ($total_cost / $total_amt);

        $data = array('cost' => $total_cost,'amt' => $total_amt, 'price' => $price);
        ($returnOffers) ? $data['offers'] = $offers : null;

        return $data;
    }

    public function getFulfillList($amount, $direction, $price = 0.0) {

        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addRootEntityFromClassMetadata('Main\MarketBundle\Entity\Offer', 'o');
        $rsm->addJoinedEntityFromClassMetadata('Main\UserBundle\Entity\User', 'u', 'o', 'user', array('id' => 'user_id'));

        $query = $this->em->createNativeQuery('CALL GetFulfillList(?,?,?)', $rsm);
        $query->setParameter(1, $amount);
        $query->setParameter(2, $direction);
        $query->setParameter(3, $price);

        $offers = $query->getResult();

        return $offers;
    }
}