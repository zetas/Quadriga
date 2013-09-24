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
 * @ORM\Table(name="transaction")
 * @ORM\HasLifecycleCallbacks
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Main\UserBundle\Entity\User")
     */
    private $user;

    /**
     * One of deposit, withdrawal, or market
     *
     * @ORM\Column(type="string", length=255)
     */
    private $transactionType;

    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToOne(targetEntity="TransactionDetail")
     */
    private $transactionDetail;

    /**
     * @ORM\PrePersist()
     */
    public function setDefaults() {
        ($this->getCreated() == null) ? $this->created = new \DateTime() : null;
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

    /**
     * Set transactionType
     *
     * @param string $transactionType
     * @return Transaction
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    
        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string 
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Transaction
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

    /**
     * Set status
     *
     * @param string $status
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user
     *
     * @param \Main\UserBundle\Entity\User $user
     * @return Transaction
     */
    public function setUser(\Main\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Main\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Transaction
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }


    /**
     * Set currency
     *
     * @param \Main\MarketBundle\Entity\Currency $currency
     * @return Transaction
     */
    public function setCurrency(\Main\MarketBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return \Main\MarketBundle\Entity\Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set transactionDetail
     *
     * @param \Main\MarketBundle\Entity\TransactionDetail $transactionDetail
     * @return Transaction
     */
    public function setTransactionDetail(\Main\MarketBundle\Entity\TransactionDetail $transactionDetail = null)
    {
        $this->transactionDetail = $transactionDetail;
    
        return $this;
    }

    /**
     * Get transactionDetail
     *
     * @return \Main\MarketBundle\Entity\TransactionDetail 
     */
    public function getTransactionDetail()
    {
        return $this->transactionDetail;
    }
}