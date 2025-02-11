<?php
declare(strict_types = 1);

namespace App\Models;

use \App\Database;

class ShippingModel extends BaseModel{
    public function __construct(Database $db){
        parent::__construct($db);
    }

    /**
     * Create a shipping object from the 
     * database shipping information.
     * 
     * @param ?float $local The local shipping fee
     * @param ?float $nationwide The country shipping fee
     * @param ?float $international The international shipping fee
     * @return Shipping The shipping object
     */
    private function convertDBShippingToObj(
        ?float $local,
        ?float $nationwide,
        ?float $international
    ){
        return new Shipping(
            $local,
            $nationwide,
            $international
        );
    }
}