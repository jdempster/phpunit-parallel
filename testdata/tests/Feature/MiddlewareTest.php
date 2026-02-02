<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    public function testMiddlewarePipeline(): void
    {
        $pipeline = [
            fn($data, $next) => $next($data . 'A'),
            fn($data, $next) => $next($data . 'B'),
            fn($data, $next) => $next($data . 'C'),
        ];

        $result = $this->runPipeline($pipeline, '');
        $this->assertEquals('ABC', $result);
    }

    public function testMiddlewareCanHalt(): void
    {
        $pipeline = [
            fn($data, $next) => $next($data . 'A'),
            fn($data, $next) => $data . 'STOPPED',
            fn($data, $next) => $next($data . 'C'),
        ];

        $result = $this->runPipeline($pipeline, '');
        $this->assertEquals('ASTOPPED', $result);
    }

    public function testBeforeAfterMiddleware(): void
    {
        $log = [];

        $middleware = function ($data, $next) use (&$log) {
            $log[] = 'before';
            $result = $next($data);
            $log[] = 'after';
            return $result;
        };

        $pipeline = [$middleware];
        $this->runPipeline($pipeline, 'test');

        $this->assertEquals(['before', 'after'], $log);
    }

    public function testMiddlewareModifiesRequest(): void
    {
        $request = ['headers' => []];

        $authMiddleware = function ($req, $next) {
            $req['headers']['Authorization'] = 'Bearer token';
            return $next($req);
        };

        $pipeline = [$authMiddleware];
        $result = $this->runPipeline($pipeline, $request);

        $this->assertEquals('Bearer token', $result['headers']['Authorization']);
    }

    public function testMiddlewareOrder(): void
    {
        $order = [];

        $pipeline = [
            function ($data, $next) use (&$order) {
                $order[] = 1;
                return $next($data);
            },
            function ($data, $next) use (&$order) {
                $order[] = 2;
                return $next($data);
            },
        ];

        $this->runPipeline($pipeline, null);
        $this->assertEquals([1, 2], $order);
    }

    public function testEmptyPipeline(): void
    {
        $result = $this->runPipeline([], 'input');
        $this->assertEquals('input', $result);
    }

    public function testMiddlewareWithCondition(): void
    {
        $isAdmin = true;

        $pipeline = [];
        if ($isAdmin) {
            $pipeline[] = fn($data, $next) => $next($data . '_admin');
        }

        $result = $this->runPipeline($pipeline, 'user');
        $this->assertEquals('user_admin', $result);
    }

    private function runPipeline(array $middlewares, mixed $input): mixed
    {
        $next = fn($data) => $data;

        foreach (array_reverse($middlewares) as $middleware) {
            $next = fn($data) => $middleware($data, $next);
        }

        return $next($input);
    }
}
