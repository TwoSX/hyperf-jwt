<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/jwt
 *
 * @link     https://github.com/hyperf-ext/jwt
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/jwt/blob/master/LICENSE
 */
namespace HyperfExt\Jwt\Contracts;

interface StorageInterface
{
    public function add(string $key, mixed $value, int $ttl);

    public function forever(string $key, mixed $value);

    public function get(string $key): mixed;

    public function destroy(string $key): bool;

    public function flush(): void;
}
