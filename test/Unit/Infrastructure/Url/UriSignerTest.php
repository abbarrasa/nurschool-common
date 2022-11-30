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

namespace Nurschool\Common\Tests\Unit\Infrastructure\Url;

use Nurschool\Common\Infrastructure\Url\UriSigner;
use PHPUnit\Framework\TestCase;

final class UriSignerTest extends TestCase
{
    private const SECRET = '1234567890';
    private const PARAM = 'my_param';
    private UriSigner $uriSigner;

    protected function setUp(): void
    {
        $this->uriSigner = new UriSigner(self::SECRET, self::PARAM);
    }

    public function testSign(): void
    {
        $url = 'http://my.site.com/sign?a=1&b=2';
        $signedUrl = $this->uriSigner->sign($url);

        $this->assertSame('http://my.site.com/sign?a=1&b=2&'.self::PARAM.'=rD5LjgXRY1iXeYft93kJq0bQ3PQrXYaYpTDlq7y3ino%3D', $signedUrl);
    }

    public function testCheckOk(): void
    {
        $urlSigned = 'http://my.site.com/sign?'.self::PARAM.'=rD5LjgXRY1iXeYft93kJq0bQ3PQrXYaYpTDlq7y3ino%3D&a=1&b=2';
        $result = $this->uriSigner->check($urlSigned);

        $this->assertTrue($result);
    }

    public function testCheckNonOk(): void
    {
        $hash = hash('md5', 'something');
        $urlSigned = 'http://my.site.com/sign?'.self::PARAM.'='.$hash.'&a=1&b=2';
        $result = $this->uriSigner->check($urlSigned);

        $this->assertNotTrue($result);
    }
}