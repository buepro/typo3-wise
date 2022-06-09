<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Service;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Core\Environment;

class CommandService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getCreditsInBackground(?string $binDirectory = null): void
    {
        if (($cmd = $this->getTypo3Command($binDirectory)) === null) {
            return;
        }
        if (Environment::isUnix()) {
            $cmd .= ' wise:getcredits > /dev/null 2>&1 &';
            $result = exec($cmd, $output, $resultCode);
            $this->logger->debug('Unix command "{cmd}" executed', [
                'result' => $result,
                'output' => $output,
                'resultCode' => $resultCode,
                'cmd' => $cmd,
            ]);
        } else {
            $cmd = '"wise" "' . $cmd . '" wise:getcredits';
            if (($handle = popen('start /B ' . $cmd, 'r')) !== false) {
                pclose($handle);
            }
            $this->logger->debug('Windows command "{cmd}" executed.', ['cmd' => $cmd]);
        }
    }

    private function getTypo3Command(?string $binDirectory = null): ?string
    {
        if ($binDirectory !== null) {
            if ($binDirectory[strlen($binDirectory) - 1] === '/') {
                return $binDirectory . 'typo3';
            }
            return $binDirectory . '/typo3';
        }
        if (
            !Environment::isComposerMode() &&
            file_exists($cmd = Environment::getPublicPath() . '/typo3/sysext/core/bin/typo3')
        ) {
            return $cmd;
        }
        // Search binary in default location
        $composerPath = $_ENV['TYPO3_PATH_COMPOSER_ROOT'] ?? Environment::getProjectPath();
        if (file_exists($cmd = $composerPath . '/vendor/bin/typo3')) {
            return $cmd;
        }
        // Search binary in configured "bin-dir"
        if (
            (file_exists($composerFile = $composerPath . '/composer.json')) &&
            ($json = file_get_contents($composerFile)) !== false &&
            ($binDirectory = (json_decode($json, true)['config']['bin-dir'] ?? '')) !== '' &&
            file_exists($cmd = implode('/', [$composerPath, $binDirectory, 'typo3']))
        ) {
            return $cmd;
        }
        return null;
    }
}
