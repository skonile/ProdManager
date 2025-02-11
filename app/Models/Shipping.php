<?php
declare(strict_types = 1);

namespace App\Models;

use App\Exceptions\ModelExceptions\NegativePriceException;

/**
 * 
 */
class Shipping{
    /**
     * Local fee. null if no shipping applicable.
     */
    private ?float $local;

    /**
     * Nationwide fee. null if no shipping applicable.
     */
    private ?float $nationwide;

    /**
     * International fee. null id no shipping applicable.
     */
    private ?float $international;

    /**
     * Initialize the properties.
     */
    public function __construct(
            ?float $local = null, 
            ?float $nationwide = null, 
            ?float $international = null){
        $this->setLocal($local);
        $this->setNationwide($nationwide);
        $this->setInternational($international);
    }

    /**
     * Get the local shipping fee.
     * 
     * @return ?float The local shipping fee
     */
    public function getLocal(): ?float{
        return $this->local;
    }

    /**
     * Set the local shipping fee.
     * 
     * @param ?float $local The local fee
     * @throws NegativePriceException
     */
    public function setLocal(?float $local){
        if($local < 0) 
            throw new NegativePriceException();
        $this->local = $local;
    }

    /**
     * Get the nationwide shipping fee.
     * 
     * @return ?float The nationwide shipping fee
     */
    public function getNationwide(): ?float{
        return $this->nationwide;
    }

    /**
     * Set the nationwide shipping fee.
     * 
     * @param ?float $nationwide The nationwide fee
     * @throws NegativePriceException
     */
    public function setNationwide(?float $nationwide){
        if($nationwide < 0) 
            throw new NegativePriceException();
        $this->nationwide = $nationwide;
    }

    /**
     * Get international shipping fee.
     * 
     * @return ?float The international fee
     */
    public function getInternational(): ?float{
        return $this->international;
    }

    /**
     * Set the international shipping fee.
     * 
     * @param ?float $international The international fee
     * @throws NegativePriceException
     */
    public function setInternational(?float $international){
        if($international < 0) 
            throw new NegativePriceException();
        $this->international = $international;
    }
}