<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Service;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ApiService
{
    public const WISE_PUBLIC_WEBHOOK_SIGNING_KEY = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvO8vXV+JksBzZAY6GhSO
XdoTCfhXaaiZ+qAbtaDBiu2AGkGVpmEygFmWP4Li9m5+Ni85BhVvZOodM9epgW3F
bA5Q1SexvAF1PPjX4JpMstak/QhAgl1qMSqEevL8cmUeTgcMuVWCJmlge9h7B1CS
D4rtlimGZozG39rUBDg6Qt2K+P4wBfLblL0k4C4YUdLnpGYEDIth+i8XsRpFlogx
CAFyH9+knYsDbR43UJ9shtc42Ybd40Afihj8KnYKXzchyQ42aC8aZ/h5hyZ28yVy
Oj3Vos0VdBIs/gAyJ/4yyQFCXYte64I7ssrlbGRaco4nKF3HmaNhxwyKyJafz19e
HwIDAQAB
-----END PUBLIC KEY-----';

    public function getPrivateKey(Site $site): string
    {
        return (string)file_get_contents(sprintf(
            '%s/sites/%s/wise/private.pem',
            Environment::getConfigPath(),
            $site->getIdentifier()
        ));
    }

    /**
     * Get the first element from the sites wise.storageUid property.
     */
    public function getStorageUid(Site $site): ?int
    {
        if (
            ($pidList = $site->getConfiguration()['wise']['storageUid'] ?? '') !== '' &&
            ($pid = GeneralUtility::intExplode(',', $pidList, true)[0] ?? 0) > 0
        ) {
            return $pid;
        }
        return null;
    }

    /** @return int[] */
    public function getStorageUidArray(Site $site): array
    {
        return GeneralUtility::intExplode(
            ',',
            $site->getConfiguration()['wise']['storageUid'] ?? '',
            true
        );
    }
}
