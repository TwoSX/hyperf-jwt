<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/jwt
 *
 * @link     https://github.com/hyperf-ext/jwt
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/jwt/blob/master/LICENSE
 */
namespace HyperfTest;

use Carbon\Carbon;
use Hyperf\Utils\ApplicationContext;
use HyperfExt\Jwt\Claims\Factory;
use HyperfExt\Jwt\Contracts\ManagerInterface;
use HyperfExt\Jwt\Manager;
use HyperfExt\Jwt\ManagerFactory;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    protected int $testNowTimestamp;

    protected ContainerInterface $container;

    /**
     * @var LegacyMockInterface|Manager|ManagerInterface|MockInterface
     */
    protected LegacyMockInterface|Manager|ManagerInterface|MockInterface $manager;

    /**
     * @var Factory
     */
    protected Factory $claimFactory;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow($now = Carbon::now());
        $this->testNowTimestamp = $now->getTimestamp();
        $this->container = ApplicationContext::getContainer();
        $this->container->set(ManagerInterface::class, $this->manager = \Mockery::mock(ManagerFactory::class));
        $this->manager->shouldReceive('getClaimFactory')->andReturn($this->claimFactory = new Factory(3600, 3600 * 24 * 14));
    }

    public function tearDown(): void
    {
        Carbon::setTestNow();
        \Mockery::close();

        parent::tearDown();
    }
}
