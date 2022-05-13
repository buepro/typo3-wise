<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Command;

use Buepro\Wise\Api\Client;
use Buepro\Wise\Domain\Repository\CreditRepository;
use Buepro\Wise\Domain\Repository\EventRepository;
use Buepro\Wise\Event\AfterAddingCreditsEvent;
use Buepro\Wise\Service\CreditService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GetCreditsCommand extends Command
{
    protected EventRepository $eventRepository;
    protected CreditRepository $creditRepository;
    protected CreditService $creditService;
    protected EventDispatcher $eventDispatcher;

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

    public function injectEventDispatcher(EventDispatcher $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Get credit transactions from a wise account. In case no option is used
transactions for one month back and all sites are obtained. By default the period
for which transactions are received is one month. Use the options "from" and "to"
to adjust the period. Note that the maximal period length is limited by the wise API.'
            )
            ->addOption(
                'from',
                'f',
                InputOption::VALUE_REQUIRED,
                'Date defining from when on credit transactions should be
obtained. The PHP function "strtotime" is used to get
the timestamp.'
            )
            ->addOption(
                'to',
                't',
                InputOption::VALUE_REQUIRED,
                'Date defining upto when credit transactions should be
obtained. The PHP function "strtotime" is used to get
the timestamp.'
            )
            ->addOption(
                'site',
                's',
                InputOption::VALUE_REQUIRED,
                'Site identifier from the site for which credit transactions
should be obtained.'
            )
            ->addOption(
                'profile-id',
                'p',
                InputOption::VALUE_REQUIRED,
                "Profile-ID for which credit transactions should be received.
If not used profile id's are obtained by the registered events."
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Getting the latest credit transactions...');

        if (($sites = $this->getSites($input, $io)) === null) {
            return Command::INVALID;
        }

        foreach ($sites as $site) {
            if (!isset($site->getConfiguration()['wise'])) {
                continue;
            }
            $unreferencedEvents = $this->eventRepository->findAllUnreferenced($site);
            $profileIds = $this->eventRepository->findAllProfileIds($site);
            $from = $this->getFromTimestamp($input);
            $to = $this->getToTimestamp($input);

            if (($profileId = $input->getOption('profile-id')) !== null) {
                $profileIds = [$profileId];
            }

            // @phpstan-ignore-next-line
            foreach ($profileIds as $profileId) {
                $apiClient = (GeneralUtility::makeInstance(Client::class))->initialize($site, $profileId);
                $balanceAccountStatement = $apiClient->getBalanceAccountStatement($from, $to);
                if (!is_array($transactions = $balanceAccountStatement['transactions'] ?? null)) {
                    continue;
                }
                $this->creditService->processTransactions($transactions, $site, $unreferencedEvents);
            }
        }

        if (count($addedCredits = $this->creditService->getAddedCredits()) > 0) {
            $this->creditService->persistAll();
            $this->eventDispatcher->dispatch((new AfterAddingCreditsEvent($addedCredits)));
        }

        $io->writeln(sprintf(
            '%d credits added.',
            count($this->creditService->getAddedCredits())
        ));
        return Command::SUCCESS;
    }

    /** @return ?Site[] */
    protected function getSites(InputInterface $input, SymfonyStyle $io): ?array
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        if (count($sites) === 0) {
            $io->writeln('<error>No site available.</error>');
            return null;
        }
        if (($siteOption = $input->getOption('site')) !== null) {
            $sites = [];
            try {
                $site = $siteFinder->getSiteByIdentifier($siteOption);
                $sites[] = $site;
            } catch (Exception $e) {
                $io->writeln('<error>The site "' . $siteOption . '" is not available.</error>');
                return null;
            }
        }
        return $sites;
    }

    protected function getFromTimestamp(InputInterface $input): int
    {
        if (
            ($fromDate = $input->getOption('from')) !== null &&
            ($timestamp = strtotime($fromDate)) !== false
        ) {
            return $timestamp;
        }
        if (
            ($toDate = $input->getOption('to')) !== null &&
            ($timestamp = strtotime($toDate)) !== false
        ) {
            return ((new \DateTime)->setTimestamp($timestamp))->sub(new \DateInterval('P1M'))->getTimestamp();
        }
        return (new \DateTime('now'))->sub(new \DateInterval('P1M'))->getTimestamp();
    }

    protected function getToTimestamp(InputInterface $input): int
    {
        if (
            ($toDate = $input->getOption('to')) !== null &&
            ($timestamp = strtotime($toDate)) !== false
        ) {
            return $timestamp;
        }
        if (
            ($fromDate = $input->getOption('from')) !== null &&
            ($timestamp = strtotime($fromDate)) !== false
        ) {
            return ((new \DateTime)->setTimestamp($timestamp))->add(new \DateInterval('P1M'))->getTimestamp();
        }
        return (new \DateTime('now'))->getTimestamp();
    }
}
