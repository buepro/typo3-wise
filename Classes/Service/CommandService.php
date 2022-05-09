<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Service;

use TYPO3\CMS\Core\Core\Environment;

class CommandService
{
    public function getCreditsInBackground(): void
    {
        $composerPath = $_ENV['TYPO3_PATH_COMPOSER_ROOT'] ?? Environment::getProjectPath();
        if (
            !(file_exists($composerFile = $composerPath . '/composer.json')) ||
            ($json = file_get_contents($composerFile)) === false ||
            ($binDir = (json_decode($json, true)['config']['bin-dir'] ?? '')) === '' ||
            !file_exists($cmd = implode('/', [$composerPath, $binDir, 'typo3']))
        ) {
            return;
        }
        if (Environment::isUnix()) {
            exec($cmd . ' wise:getcredits > /dev/null 2>&1 &');
        } else {
            $cmd = '"wise" "' . $cmd . '" wise:getcredits';
            if (($handle = popen('start /B ' . $cmd, 'r')) !== false) {
                pclose($handle);
            }
        }
    }
}
