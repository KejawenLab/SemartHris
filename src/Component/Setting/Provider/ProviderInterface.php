<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Setting\Provider;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ProviderInterface
{
    /**
     * @param string $key
     * @param string $value
     */
    public function update(string $key, string $value): void;

    /**
     * @param string $key
     *
     * @return null|string
     */
    public function get(string $key): ? string;

    /**
     * @param null|string $filtter
     *
     * @return array
     */
    public function all(?string $filtter = null): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isExist(string $key): bool;
}
