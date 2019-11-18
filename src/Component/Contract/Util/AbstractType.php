<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Contract\Util;

use KejawenLab\Semart\Collection\Collection;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AbstractType implements TypeInterface
{
    /**
     * @param int|string $key
     *
     * @return string
     */
    public function converToValue($key): string
    {
        return Collection::collect($this->getValues())->get($key);
    }

    /**
     * @param string $value
     *
     * @return int|string|null
     */
    public function convertToKey(string $value)
    {
        return Collection::collect($this->getValues())->flip()->get($value);
    }
}
