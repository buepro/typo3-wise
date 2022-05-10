<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Domain\Repository;

use Buepro\Wise\Domain\Model\Event;
use Buepro\Wise\Service\ApiService;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Property\PropertyMapper;

class EventRepository extends Repository
{
    /**
     * @return Event[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findAllUnreferenced(?Site $site = null)
    {
        $queryBuilder = $this->getQueryBuilder();
        $constraints = [$queryBuilder->expr()->or(
            $queryBuilder->expr()->eq('credit.event', 0),
            $queryBuilder->expr()->isNull('credit.event')
        )];
        $constraints = $this->addStorageConstraints($constraints, $queryBuilder, $site);
        $result = $queryBuilder
            ->select('event.uid')
            ->from('tx_wise_domain_model_event', 'event')
            ->leftJoin(
                'event',
                'tx_wise_domain_model_credit',
                'credit',
                $queryBuilder->expr()->eq('credit.event', $queryBuilder->quoteIdentifier('event.uid'))
            )
            ->where(...$constraints)
            ->executeQuery();
        $events = [];
        $propertyMapper = GeneralUtility::makeInstance(PropertyMapper::class);
        while (($row = $result->fetchAssociative()) !== false) {
            if (($event = $propertyMapper->convert($row['uid'], Event::class)) instanceof Event) {
                $events[] = $event;
            }
        }
        return $events;
    }

    /** @return int[] */
    public function findAllProfileIds(Site $site): array
    {
        $queryBuilder = $this->getQueryBuilder();
        $constraints = $this->addStorageConstraints([], $queryBuilder, $site);
        $result = $queryBuilder
            ->select('profile_id')
            ->from('tx_wise_domain_model_event', 'event')
            ->where(...$constraints)
            ->groupBy('profile_id')
            ->executeQuery();
        $eventIds = [];
        while (($row = $result->fetchAssociative()) !== false) {
            $eventIds[] = (int)$row['profile_id'];
        }
        return $eventIds;
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_wise_domain_model_event')
            ->createQueryBuilder();
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        return $queryBuilder;
    }

    protected function addStorageConstraints(array $constraints, QueryBuilder $queryBuilder, ?Site $site): array
    {
        if ($site !== null && ($storageUidArray = (new ApiService())->getStorageUidArray($site)) !== []) {
            $constraints[] = $queryBuilder->expr()->in(
                'event.pid',
                $queryBuilder->createNamedParameter($storageUidArray, Connection::PARAM_INT_ARRAY)
            );
        }
        return $constraints;
    }
}
