<?php

namespace src\Integrations;

use DateTime;
use Exception;
use src\DTO\Integrations\DataProviderDTO;
use src\DTO\Integrations\DataProviderDTOContract;

class DataProviderWithLogAndCache extends BaseDecorator
{
    protected DataProviderContract $dataProvider;
    protected CacheItemPoolInterface $cache;
    protected LoggerInterface $logger;

    public function __construct(
        DataProviderContract $dataProvider,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($dataProvider);

        // при использовании DI можнео было бы вызывать из контейнера зависимостей CacheItemPoolInterface и LoggerInterface
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function get(array $input): DataProviderDTOContract
    {
        try {
            $cacheItem = $this->cache->getItem($this->getCacheKey($input));

            if ($cacheItem->isHit()) {
                return DataProviderDTO::fromArray($cacheItem->get());
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result->toArray())
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return DataProviderDTO::fromArray([]);
    }

    private function getCacheKey(array $input): string
    {
        return sha1(json_encode($input));
    }

}