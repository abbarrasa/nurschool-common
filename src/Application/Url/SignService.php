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

namespace Nurschool\Common\Application\Url;

use Nurschool\Common\Application\Url\Exception\InvalidSignature;
use Nurschool\Common\Application\Url\Exception\ExpiredSignature;

final class SignService
{
    private string $signingKey;    
    private Signer $signer;

    public function __construct(string $signingKey, Signer $signer)
    {
        $this->signingKey = $signingKey;
        $this->signer = $signer;
    }

    public function createSignature(
        string $url,
        array $tokenParams,
        array $extraParams = [],
        ?int $lifetime = null): Signature
    {
        $extraParams['token'] = $this->createToken($tokenParams);
        if (null !== $lifetime) {
            $expires = time() + $lifetime;
            $extraParams['expires'] = $expires;
        } else {
            $expires = null;
        }

        $url = $url . '?' . http_build_query($extraParams);
        $signedUrl = $this->signer->sign($url);
        $expiresAt = null !== $expires ?
            \DateTimeImmutable::createFromFormat('U', (string) $expires) :
            null
        ;

        return new Signature($signedUrl, $expiresAt);
    }

    public function validateSignedUrl(string $signedUrl, array $tokenParams = []): bool
    {
        if (!$this->signer->check($signedUrl)) {
            throw InvalidSignature::create();
        }

        $queryParams = $this->getQueryParams($signedUrl);
        $expires = $queryParams['expires'] ?? null; 
        $expiresAt = !empty($expires) ? \DateTimeImmutable::createFromFormat('U', $expires) : null;
        $signature = new Signature($signedUrl, $expiresAt);
        if ($signature->isExpired()) {
            throw ExpiredSignature::create();
        } 

        $knownToken = $this->createToken($tokenParams);
        $userToken = $queryParams['token'] ?? null;
        if (!hash_equals($knownToken, $userToken)) {
            throw InvalidSignature::create();
        }

        return true;
    }

    private function createToken(array $parameters): string
    {
        $encodedData = json_encode($parameters);

        return base64_encode(hash_hmac('sha256', $encodedData, $this->signingKey, true));
    }

    private function getQueryParams(string $url): array
    {
        $params = [];
        $urlComponents = parse_url($url);

        if (\array_key_exists('query', $urlComponents)) {
            parse_str($urlComponents['query'] ?? '', $params);
        }

        return $params;
    }

}