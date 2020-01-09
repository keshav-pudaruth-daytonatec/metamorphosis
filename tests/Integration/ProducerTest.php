<?php
namespace Tests\Integration;

use Exception;
use Metamorphosis\Facades\Metamorphosis;
use Tests\Integration\Dummies\ProductConsumer;
use Tests\Integration\Dummies\ProductHasChanged;
use Tests\LaravelTestCase;

class ProducerTest extends LaravelTestCase
{
    protected function setUp()
    {
        parent::setUp();
        config(['kafka.brokers.default.auth' => []]);
        config(['kafka.topics.default.consumer_groups.test-consumer-group.handler' => ProductConsumer::class]);
    }

    public function testShouldRunProducer(): void
    {
        // Set
        $record = 'someRecord';
        $producer = app(ProductHasChanged::class, compact('record'));

        // Expectations
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($record);

        // Actions
        Metamorphosis::produce($producer);
        $this->artisan('kafka:consume', [
            'topic' => 'default',
            '--timeout' => 20000,
            '--offset' => 0,
            '--partition' => 0,
        ]);
    }
}
