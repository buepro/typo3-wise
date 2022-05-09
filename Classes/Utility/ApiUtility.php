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

    public static function getTimestampFromDateTimeString(string $dateTimeString): int
    {
        if (($dateTime = \DateTime::createFromFormat(\DateTimeInterface::RFC3339, $dateTimeString)) !== false) {
            return $dateTime->getTimestamp();
        }
        return 0;
    }

    public static function getDateTimeStringFromTimestamp(int $timestamp): string
    {
        return gmdate('Y-m-d\TH:i:s.v\Z', $timestamp);
    }

    public static function getMinTimeFromObjects(array $elements, string $method): int
    {
        $min = 0;
        foreach ($elements as $element) {
            if (!is_object($element) || !method_exists($element, $method)) {
                continue;
            }
            // @phpstan-ignore-next-line
            $time = self::getTimestampFromDateTimeString($element->$method());
            $min = $min === 0 || $time < $min ? $time : $min;
        }
        return $min;
    }

    public static function getMaxTimeFromObjects(array $elements, string $method): int
    {
        $max = 0;
        foreach ($elements as $element) {
            if (!is_object($element) || !method_exists($element, $method)) {
                continue;
            }
            // @phpstan-ignore-next-line
            $time = self::getTimestampFromDateTimeString($element->$method());
            $max = $max === 0 || $time > $max ? $time : $max;
        }
        return $max;
    }
}
