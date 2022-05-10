<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Utility;

class ApiUtility
{
    public static function getArrayFromJson(string $json): array
    {
        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function getDateTimeStringFromTimestamp(int $timestamp): string
    {
        return gmdate('Y-m-d\TH:i:s.v\Z', $timestamp);
    }
}
