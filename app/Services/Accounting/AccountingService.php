<?php

namespace App\Services\Accounting;

class AccountingService
{
    protected PeriodService $period;

    public function __construct(PeriodService $period)
    {
        $this->period = $period;
    }

    // =========================
    // PERIOD API (PUBLIC GATEWAY)
    // =========================

    public function createPeriod(array $data)
    {
        return $this->period->create($data);
    }

    public function setCurrentPeriod(int $id)
    {
        return $this->period->setCurrent($id);
    }

    public function closePeriod(int $id)
    {
        return $this->period->close($id);
    }

    public function lockPeriod(int $id)
    {
        return $this->period->lock($id);
    }

    public function getPeriodStats(): array
    {
        return $this->period->getStats();
    }
}
