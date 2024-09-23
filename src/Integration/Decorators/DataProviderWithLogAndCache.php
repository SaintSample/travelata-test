<?php

namespace src\Integrations;

use DateTime;
use Throwable;
use src\DTO\Integrations\DataProviderDTO;
use src\DTO\Integrations\DataProviderDTOContract;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class DataProviderWithLogAndCache extends BaseDecorator
{
    /**
     * @var DataProviderContract
     */
    protected DataProviderContract $dataProvider;
    /**
     * @var CacheItemPoolInterface
     */
    protected CacheItemPoolInterface $cache;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param DataProviderContract $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
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

    /**
     * @param array $input
     * @throws Throwable
     * @return DataProviderDTOContract
     */
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
        } catch (Throwable $e) {
            $this->logger->critical('Error', ['message' => $e->getMessage()]);
        }

        return DataProviderDTO::fromArray([]);
    }

    /**
     * @param array $input
     * @return string
     */
    private function getCacheKey(array $input): string
    {
        return sha1(json_encode($input));
    }

}