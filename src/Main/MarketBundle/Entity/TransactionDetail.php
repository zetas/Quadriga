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
 * @ORM\DiscriminatorMap({"none" = "TransactionDetail", "wu" = "WUTransactionDetail", "etf" = "ETFTransactionDetail", "btc" = "BTCTransactionDetail"})
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\ManyToOne(targetEntity="Transaction", inversedBy="transactionDetail")
     */
    protected $transaction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible;

    /**
     * @ORM\PrePersist()
     */
    public function setDefaults() {
        ($this->getVisible() == null) ? $this->setVisible(false) : null;
    }


    public function setVisible($bool) {
        $this->visible = $bool;

        return $this;
    }

    public function getVisible() {
        return $this->visible;
    }
    /**
     * Set name
     *
     * @param string $name
     * @return TransactionDetail
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    public function __toString() {
        return (string) $this->id;
    }
    
    
}