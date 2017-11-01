<?php

namespace KejawenLab\Application\SemartHris\Component\Setting;

use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Dotenv\Dotenv;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class Setting
{
    const SETTING_PREFIX = 'SEMART_';

    /**
     * @var Dotenv
     */
    private $dotEnv;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->dotEnv = new Dotenv();
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function save(string $key, string $value): void
    {
        $key = self::getKey($key);
        if ($this->isExist($key)) {
            $this->replaceEnv($key, $value);
        } else {
            file_put_contents($this->path, $this->createEnvValue($key, $value), FILE_APPEND);
        }

        $this->dotEnv->load($this->path);
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        $key = self::getKey($key);
        if (!$this->isExist($key)) {
            return;
        }

        $env = file_get_contents($this->path);
        $env = str_replace($this->createEnvValue($key, self::get($key)), '', $env);
        file_put_contents($this->path, $env);

        $this->dotEnv->load($this->path);
    }

    /**
     * @param string $key
     *
     * @return null|string
     */
    public static function get(string $key): ? string
    {
        if ($env = getenv($key)) {
            return $env;
        }

        return null;
    }

    /**
     * @param string|null $filter
     *
     * @return array
     */
    public static function all(string $filter = null): array
    {
        $variables = explode(',', getenv('SYMFONY_DOTENV_VARS'));
        $envVariables = [];
        foreach ($variables as $key) {
            if (false !== strpos($key, self::SETTING_PREFIX)) {
                if ($filter) {
                    if (false !== strpos($key, StringUtil::uppercase($filter))) {
                        $envVariables[self::getKey($key, true)] = self::get($key);
                    }
                } else {
                    $envVariables[self::getKey($key, true)] = self::get($key);
                }
            }
        }

        return $envVariables;
    }

    /**
     * @param string $key
     * @param bool   $removePrefix
     *
     * @return string
     */
    public static function getKey(string $key, bool $removePrefix = false): string
    {
        $key = StringUtil::uppercase($key);
        if (false === strpos($key, sprintf('%s', self::SETTING_PREFIX))) {
            $key = sprintf('%s%s', self::SETTING_PREFIX, $key);
        }

        if ($removePrefix) {
            $key = str_replace(self::SETTING_PREFIX, '', $key);
        }

        return $key;
    }

    /**
     * @param string $key
     * @param string $newValue
     */
    private function replaceEnv(string $key, string $newValue): void
    {
        $oldValue = self::get($key);

        $env = file_get_contents($this->path);
        $env = str_replace(sprintf('%s="%s"', $key, $oldValue), sprintf('%s="%s"', $key, $newValue), $env);

        file_put_contents($this->path, $env);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    private function createEnvValue(string $key, string $value): string
    {
        return sprintf('%s="%s"%s', $key, $value, PHP_EOL);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isExist(string $key): bool
    {
        $loadedVars = array_flip(explode(',', getenv('SYMFONY_DOTENV_VARS')));
        unset($loadedVars['']);

        if (isset($loadedVars[$key])) {
            $env = file_get_contents($this->path);
            if (false !== strpos($env, $key)) {
                return true;
            }
        }

        return false;
    }
}
