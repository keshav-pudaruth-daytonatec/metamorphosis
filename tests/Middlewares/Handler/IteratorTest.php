<?php
namespace Tests\Middlewares\Handler;

use Metamorphosis\Middlewares\Middleware;
use Metamorphosis\Record;
use Metamorphosis\Middlewares\Handler\Iterator;
use RdKafka\Message as KafkaMessage;
use Tests\LaravelTestCase;

class IteratorTest extends LaravelTestCase
{
    /** @test */
    public function it_should_process_current_middleware_and_advance_queue_pointer()
    {
        $middleware = $this->createMock(Middleware::class);

        $queue = [$middleware];
        $iterator = new Iterator($queue);

        $kafkaMessage = new KafkaMessage();
        $kafkaMessage->payload = 'original message';
        $kafkaMessage->err = RD_KAFKA_RESP_ERR_NO_ERROR;

        $record = new Record($kafkaMessage);

        $middleware->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo($record),
                $this->equalTo($iterator)
            );

        $iterator->handle($record);
    }
}