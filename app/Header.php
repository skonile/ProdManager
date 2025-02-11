<?php
declare(strict_types = 1);

namespace App;

class Header{
    private string $key;
    private string|array $value;

    public function __construct(string $key, string|array $value){
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): string{
        return $this->key;
    }

    public function getValue(): string|array{
        return $this->value;
    }

    public function getStringValue(): string{
        return $this->combineValue($this->value, ', ');
    }

    private function combineValue(string|array $value, string $separator): string{
        if(is_string($value)) return $value;

        $strValue = array_shift($value) ?? '';
        foreach($value as $val)
            $strValue .= $separator . $val;
        return $strValue;
    }

    public function getKeyValueString(): string{
        return $this->key . ": " . $this->getStringValue();
    }
}