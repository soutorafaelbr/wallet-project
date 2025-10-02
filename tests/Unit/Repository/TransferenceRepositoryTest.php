<?php

namespace Tests\Repository;

use App\Models\Transference;
use Domain\Transference\Repository\TransferenceRepository;
use Tests\TestCase;

class TransferenceRepositoryTest extends TestCase
{
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
