<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Service;

use Buepro\Wise\Domain\Model\Event;
use Buepro\Wise\Domain\Repository\EventRepository;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\Site;

class EventService
{
    protected EventRepository $eventRepository;
    protected ApiService $apiService;

    public function __construct(EventRepository $eventRepository, ApiService $apiService)
    {
        $this->eventRepository = $eventRepository;
        $this->apiService = $apiService;
    }

    public function requestValid(ServerRequestInterface $request): bool
    {
        if (
            ($signature = $request->getHeaderLine('x-signature-sha256')) !== '' &&
            ($base64Decoded = base64_decode($signature, true)) !== false
        ) {
            $json = (string)$request->getBody();
            return openssl_verify(
                $json,
                $base64Decoded,
                ApiService::WISE_PUBLIC_WEBHOOK_SIGNING_KEY,
                OPENSSL_ALGO_SHA256
            ) === 1;
        }
        return false;
    }

    public function getEvent(ServerRequestInterface $request): ?Event
    {
        if (
            $request->getHeaderLine('x-test-notification') !== 'true' &&
            ($site = $request->getAttribute('site')) instanceof Site &&
            is_array($decoded = json_decode((string)$request->getBody(), true, 512, JSON_THROW_ON_ERROR)) &&
            $decoded['event_type'] === 'balances#credit' &&
            is_array($data = $decoded['data'] ?? null) &&
            ($pid = $this->apiService->getStorageUid($site)) !== null &&
            $this->eventRepository
                ->setQuerySettings($this->apiService->getStorageUidArray($site))
                ->findByDeliveryId($request->getHeaderLine('x-delivery-id'))
                ->getFirst() === null
        ) {
            $event = new Event();
            $event
                ->setDeliveryId($request->getHeaderLine('x-delivery-id'))
                ->setId((int)($data['resource']['id'] ?? ''))
                ->setProfileId((int)($data['resource']['profile_id'] ?? ''))
                ->setOccurredAt((string)($data['occurred_at'] ?? ''))
                ->setOccurredAtProcessed((new \DateTime($data['occurred_at']))->getTimestamp())
                ->setCurrency((string)($data['currency'] ?? ''))
                ->setAmount((float)($data['amount'] ?? 0.0))
                ->setPostTransactionBalanceAmount((float)($data['post_transaction_balance_amount'] ?? 0.0))
                ->setPid($pid);
            return $event;
        }
        return null;
    }
}
