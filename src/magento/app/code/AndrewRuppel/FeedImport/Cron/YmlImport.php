<?php

namespace AndrewRuppel\FeedImport\Cron;

use Psr\Log\LoggerInterface;
use AndrewRuppel\FeedImport\Api\ImportInterface;

class YmlImport
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ImportInterface
     */
    protected $import;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger,
        ImportInterface $importInterface
    )
    {
        $this->logger = $logger;
        $this->import = $importInterface;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->import->beginImport();
        $this->logger->addInfo("Cronjob YmlImport is executed.");
    }
}

