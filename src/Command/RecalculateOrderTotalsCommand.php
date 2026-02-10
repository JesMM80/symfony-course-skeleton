<?php

namespace App\Command;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// #[AsCommand(
//     name: 'RecalculateOrderTotalsCommand',
//     description: 'Recalcula precios totales de las ordenes',
// )]
#[AsCommand(
    name: 'app:orders:recalculate-totals',
    description: 'Recalcula precios totales de las ordenes',
)]

class RecalculateOrderTotalsCommand extends Command
{
    public function __construct(private OrderRepository $orderRepository,
        private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orders = $this->orderRepository->findAll();

        foreach ($orders as $order) {
            $order->calculateTotal();
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
