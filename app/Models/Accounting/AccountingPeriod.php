<?php

namespace App\Models\Accounting;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingPeriod extends Model
{
    protected $table = 'accounting_periods';

    protected $fillable = [
        'code',
        'name',
        'year',
        'month',
        'start_date',
        'end_date',

        'organization_id',
        'fiscal_year_id', // 🔥 ADD THIS

        'status',
        'is_current',
        'is_closed',

        'closed_at',
        'closed_by',

        'locked_at',
        'locked_by',

        'allow_transaction',
        'allow_edit',

        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',

        'start_date' => 'date',
        'end_date' => 'date',

        'is_current' => 'boolean',
        'is_closed' => 'boolean',

        'allow_transaction' => 'boolean',
        'allow_edit' => 'boolean',

        'closed_at' => 'datetime',
        'locked_at' => 'datetime',
    ];

    /*
    |---------------------------------------------------
    | RELATIONS
    |---------------------------------------------------
    */

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }

    /*
    |---------------------------------------------------
    | SCOPES
    |---------------------------------------------------
    */

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'OPEN');
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', 'CLOSED');
    }

    public function scopeLocked(Builder $query): Builder
    {
        return $query->where('status', 'LOCKED');
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    public function scopeForOrganization(Builder $query, int $orgId): Builder
    {
        return $query->where('organization_id', $orgId);
    }

    public function scopeForFiscal(Builder $query, int $fiscalId): Builder
    {
        return $query->where('fiscal_year_id', $fiscalId);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('year')->orderByDesc('month');
    }

    /*
    |---------------------------------------------------
    | BUSINESS LOGIC
    |---------------------------------------------------
    */

    public function isOpen(): bool
    {
        return $this->status === 'OPEN';
    }

    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }

    public function isLocked(): bool
    {
        return $this->status === 'LOCKED';
    }

    public function isActive(): bool
    {
        return $this->is_current && $this->isOpen();
    }

    public function canTransact(): bool
    {
        return $this->isOpen() && $this->allow_transaction;
    }

    public function canEdit(): bool
    {
        return !$this->isLocked() && $this->allow_edit;
    }

    public function periodLabel(): string
    {
        return "{$this->name} ({$this->code})";
    }

    /*
    |---------------------------------------------------
    | HELPERS RELATION
    |---------------------------------------------------
    */

    public function isMonth(int $year, int $month): bool
    {
        return $this->year === $year && $this->month === $month;
    }
}
