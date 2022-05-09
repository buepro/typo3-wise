<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Command;

use Buepro\Wise\Api\Client;
use Buepro\Wise\Domain\Model\Event;
use Buepro\Wise\Domain\Repository\CreditRepository;
use Buepro\Wise\Domain\Repository\EventRepository;
use Buepro\Wise\Service\CreditService;
use Buepro\Wise\Utility\ApiUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GetCreditsCommand extends Command
{
    protected EventRepository $eventRepository;
    protected CreditRepository $creditRepository;
    protected CreditService $creditService;

    public function injectEventRepository(EventRepository $eventRepository): void
    {
        $this->eventRepository = $eventRepository;
    }

    public function injectCreditRepository(CreditRepository $creditRepository): void
    {
        $this->creditRepository = $creditRepository;
    }

    public function injectCreditService(CreditService $creditService): void
    {
        $this->creditService = $creditService;
    }

    protected function configure(): void
    {
        $this->setHelp('Get the latest balance credits from a wise account.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Getting the latest credit notes...');

        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        foreach ($sites as $site) {
            $unreferencedEvents = $this->eventRepository->findAllUnreferenced($site);
            $profileIds = array_unique(array_map(static fn (Event $event) => $event->getProfileId(), $unreferencedEvents));
            foreach ($profileIds as $profileId) {
                $apiClient = (GeneralUtility::makeInstance(Client::class))->initialize($site, $profileId);
                $balanceAccountStatement = $apiClient->getBalanceAccountStatement(
                    ApiUtility::getMinTimeFromObjects($unreferencedEvents, 'getOccurredAt'),
                    ApiUtility::getMaxTimeFromObjects($unreferencedEvents, 'getOccurredAt')
                );
                if (!is_array($transactions = $balanceAccountStatement['transactions'] ?? null)) {
                    continue;
                }
                $this->creditService->processTransactions($transactions, $unreferencedEvents, $site);
            }
        }
        $this->creditService->persistAll();

        $io->writeln(sprintf(
            '%d credits added.',
            count($this->creditService->getAddedCredits())
        ));
        return Command::SUCCESS;
    }
}
