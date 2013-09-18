<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/17/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $btcBalance;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $fiatBalance;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $verified;

    /**
     * @ORM\PrePersist()
     */
    public function setDefaults() {
        ($this->getVerified() == null) ? $this->setVerified(false) : null;
    }


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function incrementDigitalBalance($balance) {
        $this->btcBalance += $balance;
    }

    public function incrementFiatBalance($balance) {
        $this->fiatBalance += $balance;
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
     * Set btcBalance
     *
     * @param float $btcBalance
     * @return User
     */
    public function setBtcBalance($btcBalance)
    {
        $this->btcBalance = $btcBalance;
    
        return $this;
    }

    /**
     * Get btcBalance
     *
     * @return float 
     */
    public function getBtcBalance()
    {
        return $this->btcBalance;
    }

    /**
     * Set fiatBalance
     *
     * @param float $fiatBalance
     * @return User
     */
    public function setFiatBalance($fiatBalance)
    {
        $this->fiatBalance = $fiatBalance;
    
        return $this;
    }

    /**
     * Get fiatBalance
     *
     * @return float 
     */
    public function getFiatBalance()
    {
        return $this->fiatBalance;
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     * @return User
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    
        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean 
     */
    public function getVerified()
    {
        return $this->verified;
    }
}