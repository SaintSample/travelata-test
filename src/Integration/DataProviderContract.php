<?php

namespace src\Integrations;

use src\DTO\Integrations\DataProviderDTOContract;

interface DataProviderContract
{
    /**
     * @return DataProviderDTOContract
     */
    public function get(array $input): DataProviderDTOContract;
}