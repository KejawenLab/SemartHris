<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Setting\Service;

use KejawenLab\Application\SemartHris\Component\Setting\Provider\ProviderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Setting
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param string      $key
     * @param null|string $value
     */
    public function update(string $key, ?string $value): void
    {
        $this->provider->update($key, $value);
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        $this->provider->update($key, '');
    }

    /**
     * @param string $key
     *
     * @return null|string
     */
    public function get(string $key): ? string
    {
        return $this->provider->get($key);
    }

    /**
     * @param string|null $filter
     *
     * @return array
     */
    public function all(?string $filter = null): array
    {
        return $this->provider->all($filter);
    }
}
