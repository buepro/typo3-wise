<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Event extends AbstractEntity
{
    protected string $deliveryId = '';
    protected int $id = 0;
    protected int $profileId = 0;
    protected float $amount = 0.0;
    protected string $currency = 'EUR';
    protected float $postTransactionBalanceAmount = 0.0;
    protected string $occurredAt = '';

    public function setDeliveryId(string $deliveryId): self
    {
        $this->deliveryId = $deliveryId;
        return $this;
    }

    public function getDeliveryId(): string
    {
        return $this->deliveryId;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setProfileId(int $profileId): self
    {
        $this->profileId = $profileId;
        return $this;
    }

    public function getProfileId(): int
    {
        return $this->profileId;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setPostTransactionBalanceAmount(float $postTransactionBalanceAmount): self
    {
        $this->postTransactionBalanceAmount = $postTransactionBalanceAmount;
        return $this;
    }

    public function getPostTransactionBalanceAmount(): float
    {
        return $this->postTransactionBalanceAmount;
    }

    public function setOccurredAt(string $occurredAt): self
    {
        $this->occurredAt = $occurredAt;
        return $this;
    }

    public function getOccurredAt(): string
    {
        return $this->occurredAt;
    }
}
