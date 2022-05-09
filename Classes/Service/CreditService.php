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
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CreditService
{
    protected CreditRepository $creditRepository;
    protected PersistenceManager $persistenceManager;
    /** @var Credit[] $addedCredits */
    protected array $addedCredits = [];

    public function __construct(CreditRepository $creditRepository, PersistenceManager $persistenceManager)
    {
        $this->creditRepository = $creditRepository;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Add credits that can be associated with an unreferenced event to the repository.
     */
    public function processTransactions(array $transactions, array $unreferencedEvents, Site $site): void
    {
        if (($pid = (new ApiService())->getStorageUid($site)) === null) {
            return;
        }
        foreach ($transactions as $transaction) {
            if (
                $transaction['type'] === 'CREDIT' &&
                // @phpstan-ignore-next-line
                $this->creditRepository->findByReferenceNumber($transaction['referenceNumber'])->getFirst() === null &&
                ($event = array_values(array_filter(
                    $unreferencedEvents,
                    static fn (Event $event) =>
                            $event->getCurrency() === $transaction['amount']['currency'] &&
                            round($event->getAmount(), 2) === round($transaction['amount']['value'], 2) &&
                            round($event->getPostTransactionBalanceAmount(), 2) === round($transaction['runningBalance']['value'], 2)
                ))[0] ?? null) !== null
            ) {
                $credit = new Credit();
                $credit
                    ->setReferenceNumber($transaction['referenceNumber'])
                    ->setDate($transaction['date'])
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
                    ->setEvent($event)
                    ->setPid($pid);
                $this->addedCredits[] = $credit;
                $this->creditRepository->add($credit);
            }
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
}
