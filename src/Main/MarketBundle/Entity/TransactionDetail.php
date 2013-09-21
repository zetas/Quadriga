<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/19/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table("transaction_detail")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"none" = "TransactionDetail", "wu" = "WUTransactionDetail", "etf" = "ETFTransactionDetail"})
 */
class TransactionDetail
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\OneToOne(targetEntity="Transaction")
     */
    protected $transaction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $senderName;

    /**
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * Set senderName
     *
     * @param string $senderName
     * @return TransactionDetail
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * Get senderName
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return float
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }


    public function setTransaction(Transaction $transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Main\MarketBundle\Entity\Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}