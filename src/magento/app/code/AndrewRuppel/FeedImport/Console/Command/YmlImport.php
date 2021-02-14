<?php

namespace AndrewRuppel\FeedImport\Console\Command;

use AndrewRuppel\FeedImport\Api\ImportInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;

class YmlImport extends Command
{
    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";

    /**
     * @var ImportInterface
     */
    protected $import;

    /**
     * @var State
     */
    protected $appState;


    /**
     * Constructor.
     *
     * @param ImportInterface $importInterface
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        ImportInterface $importInterface,
        State $state,
        string $name = null
    )
    {
        $this->import = $importInterface;
        $this->appState = $state;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        try {
            $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $output->writeln($e->getMessage());
        }

        $output->writeln("Starting feed import...");

        try {
            $this->import->beginImport();
            $output->writeln("Import successful!");
        } catch (\Exception $e) {
            $output->writeln("Error processing import: ", $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("ymlimport:import");
        $this->setDescription("Import products from YML feed");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }
}

