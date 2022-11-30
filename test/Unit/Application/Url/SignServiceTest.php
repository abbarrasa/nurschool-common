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

namespace Nurschool\Common\Tests\Unit\Application\Url;

use Nurschool\Common\Application\Url\Exception\ExpiredSignature;
use Nurschool\Common\Application\Url\Exception\InvalidSignature;
use Nurschool\Common\Application\Url\Signature;
use Nurschool\Common\Application\Url\SignService;
use Nurschool\Common\Application\Url\Signer;
use PHPUnit\Framework\TestCase;

final class SignServiceTest extends TestCase
{
    private const SIGNING_KEY = 'abcdefghijk';
    private static string $urlSigned;
    private static string $urlInvalid;
    private static string $urlExpired;
    private static string $urlInvalidToken;
    private SignService $service;

    protected function setUp(): void
    {
        self::$urlSigned = 'http://my.site.com/sign?c=3&d=4&_hash=MVjx2RJG%2Bflb6RdlOGzLcQwh%2FlIfweI0tzUIcDFlhok%3D&token=HyFaxiepADzocukG8cFew8LVqZ%2BYJoA3Uh8kosPUzT8%3D';
        self::$urlInvalid = 'http://my.site.com/sign?c=3&d=4&_hash=invalidHash&token=HyFaxiepADzocukG8cFew8LVqZ%2BYJoA3Uh8kosPUzT8%3D';
        self::$urlExpired = 'http://my.site.com/sign?c=3&d=4&expires=1667570238&_hash=DYp%2B0Vwk54yd%2Bh5xdDmw6f5yUaAaAtZkdqF2HDEjBSg%3D&token=HyFaxiepADzocukG8cFew8LVqZ%2BYJoA3Uh8kosPUzT8%3D';
        self::$urlInvalidToken = 'http://my.site.com/sign?c=3&d=4&_hash=MVjx2RJG%2Bflb6RdlOGzLcQwh%2FlIfweI0tzUIcDFlhok%3D&token=invalidToken';

        $signer = $this->getMockBuilder(Signer::class)->getMock();
        $signer->method('sign')->willReturn(self::$urlSigned);

        $signer->expects($this->any())
            ->method('check')
            ->will($this->returnCallback(function($url) {
                return ($url === self::$urlInvalid) ? false : true;
            }))
        ;

        $this->service = new SignService(self::SIGNING_KEY, $signer);
    }

    public function testCreateSignature(): void
    {
        $url = 'http://my.site.com/sign';
        $tokenParams = ['a' => 1, 'b' => 2];
        $extraParams = ['c' => 3, 'd' => 4];
        $signature = $this->service->createSignature($url, $tokenParams, $extraParams);

        $this->assertInstanceOf(Signature::class, $signature);
        $this->assertSame(self::$urlSigned, $signature->getSignedUrl());
        $this->assertNotTrue($signature->isExpired());
    }

    public function testValidateSignedUrlOk(): void
    {
        $tokenParams = ['a' => 1, 'b' => 2];
        $result = $this->service->validateSignedUrl(self::$urlSigned, $tokenParams);

        $this->assertTrue($result);
    }

    public function testInvalidSignedUrl(): void
    {
        $tokenParams = ['a' => 1, 'b' => 2];

        $this->expectException(InvalidSignature::class);
        $this->service->validateSignedUrl(self::$urlInvalid, $tokenParams);
    }

    public function testExpiredSignedUrl(): void
    {
        $tokenParams = ['a' => 1, 'b' => 2];

        $this->expectException(ExpiredSignature::class);
        $this->service->validateSignedUrl(self::$urlExpired, $tokenParams);
    }

    public function testInvalidToken(): void
    {
        $tokenParams = ['a' => 1, 'b' => 2];

        $this->expectException(InvalidSignature::class);
        $this->service->validateSignedUrl(self::$urlInvalidToken, $tokenParams);
    }
}