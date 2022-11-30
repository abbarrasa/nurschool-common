<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Common\Infrastructure\Url;

use Nurschool\Common\Application\Url\Signer;

/**
 * Signs URIs.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class UriSigner implements Signer
{
    private string $privateKey;
    private string $parameter;

    public function __construct(string $privateKey, string $parameter = '_hash')
    {
        $this->privateKey = $privateKey;
        $this->parameter = $parameter;
    }

    public function sign(string $uri): string
    {
        $url = parse_url($uri);
        if (isset($url['query'])) {
            parse_str($url['query'], $params);
        } else {
            $params = [];
        }

        $uri = $this->buildUrl($url, $params);
        $params[$this->parameter] = $this->computeHash($uri);

        return $this->buildUrl($url, $params);
    }

    public function check(string $uri): bool
    {
        $url = parse_url($uri);
        if (isset($url['query'])) {
            parse_str($url['query'], $params);
        } else {
            $params = [];
        }

        if (empty($params[$this->parameter])) {
            return false;
        }

        $hash = $params[$this->parameter];
        unset($params[$this->parameter]);

        return hash_equals($this->computeHash($this->buildUrl($url, $params)), $hash);
    }

    private function computeHash(string $uri): string
    {
        return base64_encode(hash_hmac('sha256', $uri, $this->privateKey, true));
    }

    private function buildUrl(array $url, array $params = []): string
    {
        ksort($params, \SORT_STRING);
        $url['query'] = http_build_query($params, '', '&');

        $scheme = isset($url['scheme']) ? $url['scheme'].'://' : '';
        $host = $url['host'] ?? '';
        $port = isset($url['port']) ? ':'.$url['port'] : '';
        $user = $url['user'] ?? '';
        $pass = isset($url['pass']) ? ':'.$url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = $url['path'] ?? '';
        $query = isset($url['query']) && $url['query'] ? '?'.$url['query'] : '';
        $fragment = isset($url['fragment']) ? '#'.$url['fragment'] : '';

        return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
    }
}
