<?php
declare(strict_types=1);

namespace Xpify\Core\Helper;

final class Utils
{
    /**
     * Create HMAC hash based on provided options and secret key.
     *
     * @param array $opts
     * @param string $secret
     * @return string
     */
    public static function createHmac(array $opts, string $secret): string
    {
        // Exclude HMAC from options
        if (isset($opts['hmac'])) {
            unset($opts['hmac']);
        }

        // Setup defaults
        $data = $opts['data'];
        $raw = $opts['raw'] ?? false;
        $buildQuery = $opts['buildQuery'] ?? false;
        $buildQueryWithJoin = $opts['buildQueryWithJoin'] ?? false;
        $encode = $opts['encode'] ?? false;

        if ($buildQuery) {
            //Query params must be sorted and compiled
            ksort($data);
            $queryCompiled = [];
            foreach ($data as $key => $value) {
                $queryCompiled[] = "{$key}=" . (is_array($value) ? implode(',', $value) : $value);
            }
            $data = implode(
                $buildQueryWithJoin ? '&' : '',
                $queryCompiled
            );
        }

        // Create the hmac all based on the secret
        $hmac = hash_hmac('sha256', $data, $secret, $raw);

        // Return based on options
        return $encode ? base64_encode($hmac) : $hmac;
    }

    /**
     * Determines if request is valid by processing secret key through an HMAC-SHA256 hash function
     *
     * @param array  $params array of parameters parsed from a URL
     * @param string $secret the secret key associated with the app in the Partners Dashboard
     *
     * @return bool true if the generated hexdigest is equal to the hmac parameter, false otherwise
     */
    public static function validateHmac(array $params, string $secret): bool
    {
        if (empty($params['hmac']) || empty($secret)) {
            return false;
        }

        return hash_equals(
            $params['hmac'],
            self::createHmac($params, $secret)
        );
    }
}
