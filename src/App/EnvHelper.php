<?php

declare(strict_types=1);

namespace App;

/**
 * Class EnvHelper.
 */
class EnvHelper
{
    /**
     * Retrieve env-var if available, if not throw exception.
     *
     * @param string $envKey key/name of env-var
     * @return string
     */
    public static function get(string $envKey): string
    {
        if (empty($envKey)) {
            throw new \InvalidArgumentException('Missing arguments!');
        }

        $val = getenv($envKey);

        if (empty($val)) {
            throw new \RuntimeException(sprintf("Environment variable '%s' is not set!", $envKey));
        }

        return $val;
    }
}
