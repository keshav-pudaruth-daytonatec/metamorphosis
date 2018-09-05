<?php
namespace Tests;

use Metamorphosis\Middlewares\Handler\Producer as ProducerMiddleware;
use Metamorphosis\Producer;
use Tests\Dummies\ConsumerHandlerDummy;

class ProducerTest extends LaravelTestCase
{
    /** @test */
    public function it_should_produce_through_middleware_queue()
    {
        $record = ['message' => 'some message'];
        $topic = 'some-topic';
        $this->app->instance(ProducerMiddleware::class, $this->createMock(ProducerMiddleware::class));
        $producer = new Producer();

        $this->assertNull($producer->produce($record, $topic));
    }

    public function setUp()
    {
        parent::setUp();

        config([
            'kafka' => [
                'brokers' => [
                    'default' => [
                        'connections' => '',
                        'auth' => [],
                    ],
                ],
                'topics' => [
                    'some-topic' => [
                        'topic' => 'topic-name',
                        'broker' => 'default',
                        'consumer-groups' => [
                            'default' => [
                                'offset-reset' => 'earliest',
                                'offset' => 0,
                                'consumer' => ConsumerHandlerDummy::class,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
