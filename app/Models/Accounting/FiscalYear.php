<?php

namespace App\Models\Accounting;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYear extends Model
{
    protected $table = 'fiscal_years';

    protected $fillable = [
        'code',
        'name',
        'year',

        'start_date',
        'end_date',

        'organization_id',

        'status',
        'is_current',
        'is_closed',

        'previous_fiscal_id',

        'closed_at',
        'closed_by',

        'locked_at',
        'locked_by',

        'notes',
        'created_by',
    ];

    protected $casts = [
        'year' => 'integer',

        'start_date' => 'date',
        'end_date' => 'date',

        'is_current' => 'boolean',
        'is_closed' => 'boolean',

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

    public function periods(): HasMany
    {
        return $this->hasMany(AccountingPeriod::class, 'fiscal_year_id');
    }

    public function previousFiscal(): BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_fiscal_id');
    }

    public function nextFiscal(): HasMany
    {
        return $this->hasMany(self::class, 'previous_fiscal_id');
    }

    /*
    |---------------------------------------------------
    | SCOPES (ERP STANDARD)
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

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('year');
    }

    /*
    |---------------------------------------------------
    | STATE CHECKERS (IFRS LOGIC)
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

    /*
    |---------------------------------------------------
    | BUSINESS RULES (ERP CONTROL)
    |---------------------------------------------------
    */

    public function canClose(): bool
    {
        return $this->isOpen();
    }

    public function canLock(): bool
    {
        return $this->isClosed();
    }

    public function canCreatePeriod(): bool
    {
        return $this->isOpen();
    }

    /*
    |---------------------------------------------------
    | HELPERS
    |---------------------------------------------------
    */

    public function label(): string
    {
        return "{$this->name} ({$this->year})";
    }

    public function hasPeriods(): bool
    {
        return $this->periods()->exists();
    }

    public function currentPeriod()
    {
        return $this->periods()->where('is_current', true)->first();
    }

    public function openPeriods()
    {
        return $this->periods()->where('status', 'OPEN');
    }

    public function closedPeriods()
    {
        return $this->periods()->where('status', 'CLOSED');
    }
}
