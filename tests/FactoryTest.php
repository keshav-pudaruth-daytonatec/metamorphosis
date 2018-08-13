<?php
namespace Tests;

use Metamorphosis\Authentication\Factory;
use Metamorphosis\Authentication\NoAuthentication;
use Metamorphosis\Authentication\SSLAuthentication;
use Metamorphosis\Exceptions\AuthenticationException;

class FactoryTest extends LaravelTestCase
{
    /** @test */
    public function it_makes_ssl_authentication_class()
    {
        $authenticationConfig = [
            'protocol' => 'ssl',
            'ca' => 'path/to/ca',
            'certificate' => 'path/to/certificate',
            'key' => 'path/to/key',
        ];

        $authenticationClass = Factory::make($authenticationConfig);

        $this->assertInstanceOf(SSLAuthentication::class, $authenticationClass);
    }

    /** @test */
    public function it_makes_no_authentication_class()
    {
        $this->assertInstanceOf(NoAuthentication::class, Factory::make([]));
        $this->assertInstanceOf(NoAuthentication::class, Factory::make(null));
    }

    /** @test */
    public function it_throws_exception_when_invalid_protocol_is_passed()
    {
        $authenticationConfig = [
            'protocol' => 'some-invalid-protocol',
        ];

        $this->expectException(AuthenticationException::class);

        Factory::make($authenticationConfig);
    }
}
