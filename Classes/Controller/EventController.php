<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Controller;

use Buepro\Wise\Domain\Repository\EventRepository;
use Buepro\Wise\Service\ApiService;
use Buepro\Wise\Service\CommandService;
use Buepro\Wise\Service\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class EventController
{
    protected ResponseFactory $responseFactory;
    protected EventRepository $eventRepository;

    public function __construct(ResponseFactory $responseFactory, EventRepository $eventRepository)
    {
        $this->responseFactory = $responseFactory;
        $this->eventRepository = $eventRepository;
    }

    public function handleEvent(ServerRequestInterface $request): ResponseInterface
    {
        if (!(($site = $request->getAttribute('site')) instanceof Site)) {
            return $this->getResponse('Request not processed. Check site configuration.');
        }
        if (
            ($eventService = GeneralUtility::makeInstance(EventService::class))->requestValid($request) &&
            ($event = $eventService->getEvent($request)) !== null &&
            ($storageUids = (new ApiService())->getStorageUidArray($site)) !== []
        ) {
            $this->eventRepository->setQuerySettings($storageUids)->add($event);
            GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();
        }
        $binDirectory = $site->getConfiguration()['wise']['binDirectory'] ?? null;
        (GeneralUtility::makeInstance(CommandService::class))->getCreditsInBackground($binDirectory);
        return $this->getResponse('Received, tank you.');
    }

    public function getResponse(string $text): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->getBody()->write($text);
        return $response;
    }
}
