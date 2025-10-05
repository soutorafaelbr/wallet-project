<?php

namespace Tests\Repository;

use App\Models\Transference;
use Domain\Wallet\Repository\TransferenceRepository;
use Tests\TestCase;

class TransferenceRepositoryTest extends TestCase
{
    private TransferenceRepository $transferenceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transferenceRepository = $this->app[TransferenceRepository::class];
    }

    public function test_creates_transference()
    {
        $this->assertInstanceOf(
            Transference::class,
            $this->transferenceRepository->create(Transference::factory()->make()->toArray())
        );
    }
}
