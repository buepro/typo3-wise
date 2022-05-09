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
use Buepro\Wise\Service\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ResponseFactory;
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
        if (
            ($eventService = GeneralUtility::makeInstance(EventService::class))->requestValid($request) &&
            ($event = $eventService->getEvent($request)) !== null
        ) {
            $this->eventRepository->add($event);
            GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();
        }
        return $this->getConfirmationResponse();
    }

    public function getConfirmationResponse(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->getBody()->write('Received, tank you.');
        return $response;
    }
}
