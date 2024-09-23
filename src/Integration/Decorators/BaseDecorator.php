<?php

namespace src\Integrations;

use src\DTO\Integrations\DataProviderDTOContract;

abstract class BaseDecorator implements DataProviderContract
{
    protected DataProviderContract $dataProvider;

    public function __construct(
        DataProviderContract $dataProvider,
    ) {
        $this->dataProvider = $dataProvider;
    }

    public function get(array $input): DataProviderDTOContract
    {
        return $this->dataProvider->get($input);
    }
}