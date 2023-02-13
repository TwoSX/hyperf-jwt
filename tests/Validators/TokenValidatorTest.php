<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/jwt
 *
 * @link     https://github.com/hyperf-ext/jwt
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/jwt/blob/master/LICENSE
 */
namespace HyperfTest\Validators;

use HyperfExt\Jwt\Contracts\TokenValidatorInterface;
use HyperfExt\Jwt\Exceptions\TokenInvalidException;
use HyperfExt\Jwt\Validators\TokenValidator;
use HyperfTest\AbstractTestCase;

/**
 * @internal
 * @coversNothing
 */
class TokenValidatorTest extends AbstractTestCase
{
    protected TokenValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->container->get(TokenValidatorInterface::class);
    }

    /** @test */
    public function itShouldReturnTrueWhenProvidingAWellFormedToken()
    {
        $this->assertTrue($this->validator->isValid('one.two.three'));
    }

    public function dataProviderMalformedTokens(): array
    {
        return [
            ['one.two.'],
            ['.two.'],
            ['.two.three'],
            ['one..three'],
            ['..'],
            [' . . '],
            [' one . two . three '],
        ];
    }

    /**
     * @test
     * @dataProvider TokenValidatorTest::dataProviderMalformedTokens
     */
    public function itShouldReturnFalseWhenProvidingAMalformedToken(string $token)
    {
        $this->assertFalse($this->validator->isValid($token));
    }

    /**
     * @test
     * @dataProvider TokenValidatorTest::dataProviderMalformedTokens
     */
    public function itShouldThrowAnExceptionWhenProvidingAMalformedToken(string $token)
    {
        $this->expectExceptionMessage('Malformed token');
        $this->expectException(TokenInvalidException::class);
        $this->validator->check($token);
    }

    public function dataProviderTokensWithWrongSegmentsNumber(): array
    {
        return [
            ['one.two'],
            ['one.two.three.four'],
            ['one.two.three.four.five'],
        ];
    }

    /**
     * @test
     * @dataProvider TokenValidatorTest::dataProviderTokensWithWrongSegmentsNumber
     */
    public function itShouldReturnFalseWhenProvidingATokenWithWrongSegmentsNumber(string $token)
    {
        $this->assertFalse($this->validator->isValid($token));
    }

    /**
     * @test
     * @dataProvider TokenValidatorTest::dataProviderTokensWithWrongSegmentsNumber
     */
    public function itShouldThrowAnExceptionWhenProvidingAMalformedTokenWithWrongSegmentsNumber(string $token)
    {
        $this->expectExceptionMessage('Wrong number of segments');
        $this->expectException(TokenInvalidException::class);
        $this->validator->check($token);
    }
}
