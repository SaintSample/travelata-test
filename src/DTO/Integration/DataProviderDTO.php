<?php

namespace src\DTO\Integrations;

class DataProviderDTO implements DataProviderDTOContract
{

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        // some initialization
    }

    /**
     * @param array $array
     * @return static
     */
    public static function fromArray(array $array): static
    {
        return new static($array);
    }

    public function toArray(): array
    {
        return [
            // some fields mapping
        ];
    }
}