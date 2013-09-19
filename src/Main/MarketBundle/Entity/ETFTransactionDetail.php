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
//use Main\MarketBundle\Entity\TransactionDetail;
//
///**
// * @ORM\Entity
// * @ORM\Table(name="transaction_etf_detail")
// */
//class ETFTransactionDetail extends TransactionDetail
//{
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    protected $senderBank;
//
//    /**
//     * @ORM\Column(type="integer")
//     */
//    protected $senderAccount;
//
//
//    /**
//     * Set senderBank
//     *
//     * @param string $senderBank
//     * @return ETFTransactionDetail
//     */
//    public function setSenderBank($senderBank)
//    {
//        $this->senderBank = $senderBank;
//
//        return $this;
//    }
//
//    /**
//     * Get senderBank
//     *
//     * @return string
//     */
//    public function getSenderBank()
//    {
//        return $this->senderBank;
//    }
//
//    /**
//     * Set senderAccount
//     *
//     * @param integer $senderAccount
//     * @return ETFTransactionDetail
//     */
//    public function setSenderAccount($senderAccount)
//    {
//        $this->senderAccount = $senderAccount;
//
//        return $this;
//    }
//
//    /**
//     * Get senderAccount
//     *
//     * @return integer
//     */
//    public function getSenderAccount()
//    {
//        return $this->senderAccount;
//    }
//}