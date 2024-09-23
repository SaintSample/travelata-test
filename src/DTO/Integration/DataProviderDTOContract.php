<?php

namespace src\DTO\Integrations;

interface DataProviderDTOContract
{
    /**
     * @param array $array
     * @return static
     */
    public static function fromArray(array $array): static;

    /**
     * @return array
     */
    public function toArray(): array;
}