<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/20/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="offer")
 * @ORM\HasLifecycleCallbacks
 */
class Offer
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
     * @ORM\Column(type="boolean")
     */
    private $isBuy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInstant;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\PrePersist()
     */
    public function setDefaults() {
        ($this->getCreated() == null) ? $this->created = new \DateTime() : null;
    }

    public function getValue() {
        $this->setValue();

        return $this->value;
    }

    private function setValue() {
        $this->value = number_format(($this->price * $this->amount), 2, '.', ',');
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
     * Set isBuy
     *
     * @param boolean $isBuy
     * @return Offer
     */
    public function setIsBuy($isBuy)
    {
        $this->isBuy = $isBuy;
    
        return $this;
    }

    /**
     * Get isBuy
     *
     * @return boolean 
     */
    public function getIsBuy()
    {
        return $this->isBuy;
    }

    /**
     * Set isInstant
     *
     * @param boolean $isInstant
     * @return Offer
     */
    public function setIsInstant($isInstant)
    {
        $this->isInstant = $isInstant;
    
        return $this;
    }

    /**
     * Get isInstant
     *
     * @return boolean 
     */
    public function getIsInstant()
    {
        return $this->isInstant;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Offer
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
     * Set price
     *
     * @param float $price
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return number_format($this->price, 2, '.', ',');
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Offer
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
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
     * @return Offer
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
}