<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Service;

use Buepro\Wise\Domain\Model\Credit;
use Buepro\Wise\Domain\Model\Event;
use Buepro\Wise\Domain\Repository\CreditRepository;
use Buepro\Wise\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CreditService
{
    protected EventRepository $eventRepository;
    protected CreditRepository $creditRepository;
    protected PersistenceManager $persistenceManager;
    /** @var Credit[] $addedCredits */
    protected array $addedCredits = [];

    public function __construct(
        EventRepository $eventRepository,
        CreditRepository $creditRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->eventRepository = $eventRepository;
        $this->creditRepository = $creditRepository;
        $this->persistenceManager = $persistenceManager;
    }

    public function processTransactions(array $transactions, Site $site, ?array $unreferencedEvents = null): void
    {
        if (($storageUids = ($apiService = new ApiService())->getStorageUidArray($site)) === []) {
            return;
        }
        $this->eventRepository->setQuerySettings($storageUids);
        $this->creditRepository->setQuerySettings($storageUids);
        $transactions = array_filter($transactions, static fn ($t) => $t['type'] === 'CREDIT');
        uasort(
            $transactions,
            static fn (array $t1, array $t2) =>
            (new \DateTime($t1['date']))->getTimestamp() - (new \DateTime($t2['date']))->getTimestamp()
        );
        if ($unreferencedEvents === null) {
            $unreferencedEvents = $this->eventRepository->findAllUnreferenced($site);
        }
        $unreferencedEvents = $this->indexUnreferencedEvents($unreferencedEvents);

        foreach ($transactions as $transaction) {
            if ($this->creditRepository->findByReferenceNumber($transaction['referenceNumber'])->getFirst() !== null) {
                continue;
            }
            $credit = new Credit();
            $credit
                ->setReferenceNumber($transaction['referenceNumber'])
                ->setDate($transaction['date'])
                ->setDateProcessed((new \DateTime($transaction['date']))->getTimestamp())
                ->setAmountValue($transaction['amount']['value'])
                ->setAmountCurrency($transaction['amount']['currency'])
                ->setTotalFeesValue($transaction['totalFees']['value'])
                ->setTotalFeesCurrency($transaction['totalFees']['currency'])
                ->setDetailsType($transaction['details']['type'])
                ->setDetailsDescription($transaction['details']['description'])
                ->setDetailsSenderName($transaction['details']['senderName'])
                ->setDetailsSenderAccount($transaction['details']['senderAccount'])
                ->setDetailsPaymentReference($transaction['details']['paymentReference'])
                ->setExchangeDetails((string)$transaction['exchangeDetails'])
                ->setRunningBalanceValue($transaction['runningBalance']['value'])
                ->setRunningBalanceCurrency($transaction['runningBalance']['currency'])
                ->setPid($apiService->getStorageUid($site));
            if (($event = $this->getEventForTransaction($unreferencedEvents, $transaction)) instanceof Event) {
                $credit->setEvent($event);
                unset($unreferencedEvents[$event->getUid()]);
            }
            $this->addedCredits[] = $credit;
            $this->creditRepository->add($credit);
        }
    }

    /** @return Credit[] */
    public function getAddedCredits(): array
    {
        return $this->addedCredits;
    }

    public function persistAll(): void
    {
        $this->persistenceManager->persistAll();
    }

    /**
     * @param ?Event[] $unreferencedEvents
     * @return Event[]
     */
    protected function indexUnreferencedEvents(?array $unreferencedEvents): array
    {
        if (!is_array($unreferencedEvents)) {
            return [];
        }
        $indexedEvents = [];
        foreach ($unreferencedEvents as $event) {
            $indexedEvents[$event->getUid()] = $event;
        }
        return $indexedEvents;
    }

    protected function getEventForTransaction(array $unreferencedEvents, array $transaction): ?Event
    {
        return array_values(array_filter(
            $unreferencedEvents,
            static fn (Event $event) =>
                $event->getCurrency() === $transaction['amount']['currency'] &&
                round($event->getAmount(), 2) === round($transaction['amount']['value'], 2) &&
                round($event->getPostTransactionBalanceAmount(), 2) ===
                    round($transaction['runningBalance']['value'], 2) &&
                abs((new \DateTime($event->getOccurredAt()))->getTimestamp() -
                    (new \DateTime($transaction['date']))->getTimestamp()) < 300
        ))[0] ?? null;
    }
}
