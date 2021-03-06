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
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your first name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="The first name is too short.",
     *     maxMessage="The first name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your last name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="The last name is too short.",
     *     maxMessage="The last name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min=10,
     *     max="15",
     *     minMessage="The phone number is too short.",
     *     maxMessage="The phone number is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Please enter your pin.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=4,
     *     max="5",
     *     minMessage="The pin is too short.",
     *     maxMessage="The pin is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $pin;


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
        ($this->getBtcBalance() == null) ? $this->setBtcBalance(50) : null;
        ($this->getFiatBalance() == null) ? $this->setFiatBalance(500) : null;
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

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     * @return User
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    
        return $this;
    }

    /**
     * Get pin
     *
     * @return integer 
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param integer $zip
     * @return User
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    
        return $this;
    }

    /**
     * Get zip
     *
     * @return integer 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
}