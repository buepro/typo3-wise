<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Event;

use Buepro\Wise\Domain\Model\Credit;

final class AfterAddingCreditsEvent
{
    /** @var Credit[] $addedCredits */
    private array $addedCredits;

    /** @param Credit[] $addedCredits */
    public function __construct(array $addedCredits)
    {
        $this->addedCredits = $addedCredits;
    }

    /** @return Credit[] */
    public function getAddedCredits(): array
    {
        return $this->addedCredits;
    }
}
