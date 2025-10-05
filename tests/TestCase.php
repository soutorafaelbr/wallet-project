<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function mockGatewaySuccessful(): void
    {
        Http::fake([
            '*' => Http::response('{"status": "success","data": {"authorization": true}}', JsonResponse::HTTP_OK),
        ]);
    }

    protected function mockGatewayFailed(): void
    {
        Http::fake([
            '*' => Http::response(
                '{"status": "fail","data": {"authorization": false}}',
                JsonResponse::HTTP_FORBIDDEN
            ),
        ]);
    }

    protected function mockNotificationSuccessful()
    {
        Http::fake([
            '*' => Http::response('{}', JsonResponse::HTTP_OK),
        ]);
    }
}
