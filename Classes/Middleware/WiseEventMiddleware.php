<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Middleware;

use Buepro\Wise\Controller\EventController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WiseEventMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (
            strpos($request->getRequestTarget(), 'wise-event-handler') !== false &&
            ($site = $request->getAttribute('site')) instanceof Site &&
            ($challenge = trim($site->getConfiguration()['wise']['eventUrlSegmentChallenge'] ?? '')) !== '' &&
            strpos($request->getRequestTarget(), 'wise-event-handler-' . $challenge) !== false
        ) {
            return GeneralUtility::makeInstance(EventController::class)->handleEvent($request);
        }
        return $handler->handle($request);
    }
}
