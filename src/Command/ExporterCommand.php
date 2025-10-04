<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\ExporterService;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:exporter',
    description: 'Exporter sous format csv.',
    help: "Cette commande permet d'exporter les données sous format CSV"
)]
class ExporterCommand
{
    public const string FORMAT_CSV = 'csv';
    public const string FORMAT_TXT = 'txt';

    public function __construct(private readonly ExporterService $exporterService)
    {
    }

    public function __invoke(
        #[Argument('The format is required.')] string $format,
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $format = $input->getArgument('format');

        if(in_array($format, [self::FORMAT_CSV, self::FORMAT_TXT])){
            $output->writeln([ "Exportation de la liste des produits...\n"]);

            $this->exporterService->exporterProduits($format);

            $output->writeln([ "Fin de l'exportation de la liste des produits.\n"]);
        }else{
            $output->writeln([ "Oops! : le format '$format' n'est pas supporté par la commande!\n"]);
        }

        return Command::SUCCESS;
    }

    public static function getFormats(): array
    {
        return [
            self::FORMAT_CSV,
            self::FORMAT_TXT,
        ];
    }
}
