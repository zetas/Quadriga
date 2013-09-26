<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/19/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Main\MarketBundle\Entity\TransactionDetail;

/**
 * @ORM\Entity
 */
class ETFTransactionDetail extends TransactionDetail
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bank;

    /**
     * @ORM\Column(type="integer")
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $swift;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bankAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bankCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bankState;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bankCountry;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bankZip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $country;

    /**
     * @ORM\Column(type="integer")
     */
    protected $zip;

    /**
     * Set bank
     *
     * @param string $bank
     * @return ETFTransactionDetail
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set account
     *
     * @param integer $account
     * @return ETFTransactionDetail
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return integer
     */
    public function getAccount()
    {
        return $this->account;
    }


    /**
     * Set swift
     *
     * @param string $swift
     * @return ETFTransactionDetail
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;
    
        return $this;
    }

    /**
     * Get swift
     *
     * @return string 
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Set bankAddress
     *
     * @param string $bankAddress
     * @return ETFTransactionDetail
     */
    public function setBankAddress($bankAddress)
    {
        $this->bankAddress = $bankAddress;
    
        return $this;
    }

    /**
     * Get bankAddress
     *
     * @return string 
     */
    public function getBankAddress()
    {
        return $this->bankAddress;
    }

    /**
     * Set bankCity
     *
     * @param string $bankCity
     * @return ETFTransactionDetail
     */
    public function setBankCity($bankCity)
    {
        $this->bankCity = $bankCity;
    
        return $this;
    }

    /**
     * Get bankCity
     *
     * @return string 
     */
    public function getBankCity()
    {
        return $this->bankCity;
    }

    /**
     * Set bankState
     *
     * @param string $bankState
     * @return ETFTransactionDetail
     */
    public function setBankState($bankState)
    {
        $this->bankState = $bankState;
    
        return $this;
    }

    /**
     * Get bankState
     *
     * @return string 
     */
    public function getBankState()
    {
        return $this->bankState;
    }

    /**
     * Set bankCountry
     *
     * @param string $bankCountry
     * @return ETFTransactionDetail
     */
    public function setBankCountry($bankCountry)
    {
        $this->bankCountry = $bankCountry;
    
        return $this;
    }

    /**
     * Get bankCountry
     *
     * @return string 
     */
    public function getBankCountry()
    {
        return $this->bankCountry;
    }

    /**
     * Set bankZip
     *
     * @param string $bankZip
     * @return ETFTransactionDetail
     */
    public function setBankZip($bankZip)
    {
        $this->bankZip = $bankZip;
    
        return $this;
    }

    /**
     * Get bankZip
     *
     * @return string 
     */
    public function getBankZip()
    {
        return $this->bankZip;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return ETFTransactionDetail
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

    /**
     * Set city
     *
     * @param string $city
     * @return ETFTransactionDetail
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
     * @return ETFTransactionDetail
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
     * Set country
     *
     * @param string $country
     * @return ETFTransactionDetail
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
     * Set zip
     *
     * @param integer $zip
     * @return ETFTransactionDetail
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
}