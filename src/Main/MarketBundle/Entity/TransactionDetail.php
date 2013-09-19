<?php
///**
// * Created by DavidDV <ddv@qubitlogic.net>
// * For project QuadrigaCX
// * Created: 9/19/13
// * <http://[github|bitbucket].com/zetas>
// */
//
//namespace Main\MarketBundle\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * @ORM\MappedSuperclass
// */
//
//class TransactionDetail
//{
//    /**
//     * @ORM\OneToOne(targetEntity="Transaction")
//     */
//    protected $transaction;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    protected $senderName;
//
//    /**
//     * @ORM\Column(type="float")
//     */
//    protected $amount;
//
//    /**
//     * Set senderName
//     *
//     * @param string $senderName
//     * @return WUTransactionDetail
//     */
//    public function setSenderName($senderName)
//    {
//        $this->senderName = $senderName;
//
//        return $this;
//    }
//
//    /**
//     * Get senderName
//     *
//     * @return string
//     */
//    public function getSenderName()
//    {
//        return $this->senderName;
//    }
//
//    /**
//     * Set amount
//     *
//     * @param float $amount
//     * @return WUTransactionDetail
//     */
//    public function setAmount($amount)
//    {
//        $this->amount = $amount;
//
//        return $this;
//    }
//
//    /**
//     * Get amount
//     *
//     * @return float
//     */
//    public function getAmount()
//    {
//        return $this->amount;
//    }
//
//    /**
//     * Set transaction
//     *
//     * @param \Main\MarketBundle\Entity\Transaction $transaction
//     * @return WUTransactionDetail
//     */
//    public function setTransaction(\Main\MarketBundle\Entity\Transaction $transaction = null)
//    {
//        $this->transaction = $transaction;
//
//        return $this;
//    }
//
//    /**
//     * Get transaction
//     *
//     * @return \Main\MarketBundle\Entity\Transaction
//     */
//    public function getTransaction()
//    {
//        return $this->transaction;
//    }
//}