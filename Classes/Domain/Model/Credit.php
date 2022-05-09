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

class Credit extends AbstractEntity
{
    protected string $referenceNumber = '';
    protected string $date = '';
    protected float $amountValue = 0.0;
    protected string $amountCurrency = '';
    protected float $totalFeesValue = 0.0;
    protected string $totalFeesCurrency = '';
    protected string $detailsType = '';
    protected string $detailsDescription = '';
    protected string $detailsSenderName = '';
    protected string $detailsSenderAccount = '';
    protected string $detailsPaymentReference = '';
    protected string $exchangeDetails = '';
    protected float $runningBalanceValue = 0.0;
    protected string $runningBalanceCurrency = '';
    protected ?Event $event = null;

    public function setReferenceNumber(string $referenceNumber): self
    {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setAmountValue(float $amountValue): self
    {
        $this->amountValue = $amountValue;
        return $this;
    }

    public function getAmountValue(): float
    {
        return $this->amountValue;
    }

    public function setAmountCurrency(string $amountCurrency): self
    {
        $this->amountCurrency = $amountCurrency;
        return $this;
    }

    public function getAmountCurrency(): string
    {
        return $this->amountCurrency;
    }

    public function setTotalFeesValue(float $totalFeesValue): self
    {
        $this->totalFeesValue = $totalFeesValue;
        return $this;
    }

    public function getTotalFeesValue(): float
    {
        return $this->totalFeesValue;
    }

    public function setTotalFeesCurrency(string $totalFeesCurrency): self
    {
        $this->totalFeesCurrency = $totalFeesCurrency;
        return $this;
    }

    public function getTotalFeesCurrency(): string
    {
        return $this->totalFeesCurrency;
    }

    public function setDetailsType(string $detailsType): self
    {
        $this->detailsType = $detailsType;
        return $this;
    }

    public function getDetailsType(): string
    {
        return $this->detailsType;
    }

    public function setDetailsDescription(string $detailsDescription): self
    {
        $this->detailsDescription = $detailsDescription;
        return $this;
    }

    public function getDetailsDescription(): string
    {
        return $this->detailsDescription;
    }

    public function setDetailsSenderName(string $detailsSenderName): self
    {
        $this->detailsSenderName = $detailsSenderName;
        return $this;
    }

    public function getDetailsSenderName(): string
    {
        return $this->detailsSenderName;
    }

    public function setDetailsSenderAccount(string $detailsSenderAccount): self
    {
        $this->detailsSenderAccount = $detailsSenderAccount;
        return $this;
    }

    public function getDetailsSenderAccount(): string
    {
        return $this->detailsSenderAccount;
    }

    public function setDetailsPaymentReference(string $detailsPaymentReference): self
    {
        $this->detailsPaymentReference = $detailsPaymentReference;
        return $this;
    }

    public function getDetailsPaymentReference(): string
    {
        return $this->detailsPaymentReference;
    }

    public function setExchangeDetails(string $exchangeDetails): self
    {
        $this->exchangeDetails = $exchangeDetails;
        return $this;
    }

    public function getExchangeDetails(): string
    {
        return $this->exchangeDetails;
    }

    public function setRunningBalanceValue(float $runningBalanceValue): self
    {
        $this->runningBalanceValue = $runningBalanceValue;
        return $this;
    }

    public function getRunningBalanceValue(): float
    {
        return $this->runningBalanceValue;
    }

    public function setRunningBalanceCurrency(string $runningBalanceCurrency): self
    {
        $this->runningBalanceCurrency = $runningBalanceCurrency;
        return $this;
    }

    public function getRunningBalanceCurrency(): string
    {
        return $this->runningBalanceCurrency;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }
}
