<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Setting\Provider;

use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Dotenv\Dotenv;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DotEnvProvider implements ProviderInterface
{
    /**
     * @var Dotenv
     */
    private $dotEnv;

    /**
     * @var string
     */
    private $path;

    /**
     * @param Dotenv $dotenv
     * @param string $path
     */
    public function __construct(Dotenv $dotenv, string $path)
    {
        $this->dotEnv = $dotenv;
        $this->path = $path;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function update(string $key, string $value): void
    {
        $key = SettingKey::getRealKey($key);
        if ($this->isExist($key)) {
            $this->replaceValue($key, $value);
        }

        $this->dotEnv->load($this->path);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function get(string $key): ? string
    {
        if ($env = getenv(SettingKey::getRealKey($key))) {
            return $env;
        }

        return null;
    }

    /**
     * @param null|string $filter
     *
     * @return array
     */
    public function all(?string $filter = null): array
    {
        $variables = explode(',', getenv('SYMFONY_DOTENV_VARS'));
        $envVariables = [];
        foreach ($variables as $key) {
            if (false !== strpos($key, SettingKey::PREFIX)) {
                $key = SettingKey::getRealKey($key, true);
                if ($filter) {
                    if (false !== strpos($key, StringUtil::uppercase($filter))) {
                        $envVariables[$key] = $this->get($key);
                    }
                } else {
                    $envVariables[$key] = $this->get($key);
                }
            }
        }

        return $envVariables;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isExist(string $key): bool
    {
        $env = array_flip(array_keys($this->all()));
        if (in_array(SettingKey::getRealKey($key), $env)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @param string $newValue
     */
    private function replaceValue(string $key, string $newValue): void
    {
        $oldValue = $this->get($key);
        $env = file_get_contents($this->path);
        $env = str_replace(sprintf('%s="%s"', $key, $oldValue), sprintf('%s="%s"', $key, $newValue), $env);
        file_put_contents($this->path, $env);
    }
}
