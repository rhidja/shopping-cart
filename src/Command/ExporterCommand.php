<?php
// src/Command/ExporterCommand.php
namespace App\Command;

use App\Service\ExporterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ExporterCommand extends Command
{
    const FORMAT_CSV = 'csv';
    const FORMAT_TXT = 'txt';

    private $exporterService;

    public function __construct(ExporterService $exporterService)
    {
        $this->exporterService = $exporterService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:exporter')
            ->addArgument('format', InputArgument::REQUIRED, 'The format is required.')
            ->setDescription('Exporter sous format csv.')
            ->setHelp("Cette commande permet d'exporter les données sous format CSV");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $format = $input->getArgument('format');

        if(in_array($format, [self::FORMAT_CSV, self::FORMAT_TXT])){
            $output->writeln([ "Exportation de la liste des produits...\n"]);

            $this->exporterService->exporterProduits($format);

            $output->writeln([ "Fin de l'exportation de la liste des produits.\n"]);
        }else{
            $output->writeln([ "Oops! : le format '$format' n'est pas supporté par la commande!\n"]);
        }
    }
}
