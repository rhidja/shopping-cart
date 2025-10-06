<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ExportService;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:export',
    description: 'Export in CSV format.',
    help: 'This command allows exporting the data in CSV format.'
)]
class ExportCommand
{
    public const string FORMAT_CSV = 'csv';
    public const string FORMAT_TXT = 'txt';

    public function __construct(private readonly ExportService $exporterService)
    {
    }

    public function __invoke(
        #[Argument('The format is required.')] string $format,
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $format = $input->getArgument('format');

        if (!in_array($format, [self::FORMAT_CSV, self::FORMAT_TXT])) {
            $output->writeln(["Oops! The format '$format' is not supported by the command!\n"]);

            return Command::INVALID;
        }

        $output->writeln(["Exporting the product list…\n"]);

        $this->exporterService->exportProducts($format);

        $output->writeln(["Product list export completed.\n"]);

        return Command::SUCCESS;
    }

    /**
     * @return string[]
     */
    public static function getFormats(): array
    {
        return [
            self::FORMAT_CSV,
            self::FORMAT_TXT,
        ];
    }
}
