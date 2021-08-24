<?php
namespace Metamorphosis\Middlewares;

use Metamorphosis\Avro\Serializer\Decoders\DecoderInterface;
use Metamorphosis\Avro\Serializer\MessageDecoder;
use Metamorphosis\ConfigManager;
use Metamorphosis\Exceptions\ConfigurationException;

use Metamorphosis\Middlewares\Handler\MiddlewareHandlerInterface;
use Metamorphosis\Record\RecordInterface;

class AvroSchemaDecoder implements MiddlewareInterface
{
    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var ConfigManager
     */
    private $configManager;

    public function __construct(MessageDecoder $decoder, ConfigManager $configManager)
    {
        if (!$this->configManager->get('url')) {
            throw new ConfigurationException("Avro schema url not found, it's required to use AvroSchemaDecoder Middleware");
        }

        $this->decoder = $decoder;
        $this->configManager = $configManager;
    }

    public function process(RecordInterface $record, MiddlewareHandlerInterface $handler): void
    {
        $record->setPayload($this->decoder->decodeMessage($record->getPayload()));

        $handler->handle($record);
    }
}
