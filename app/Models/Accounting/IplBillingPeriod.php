<?php

namespace App\Models\Accounting;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Accounting\InvoiceIPL;

class IplBillingPeriod extends Model
{
    use SoftDeletes;

    protected $table = 'ipl_billing_periods';

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANT
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'draft';

    public const STATUS_OPEN = 'open';

    public const STATUS_GENERATED = 'generated';

    public const STATUS_CLOSED = 'closed';

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | RELATION
        |--------------------------------------------------------------------------
        */

        'organization_id',
        'accounting_period_id',

        /*
        |--------------------------------------------------------------------------
        | BILLING INFORMATION
        |--------------------------------------------------------------------------
        */

        'code',
        'name',

        'billing_type',
        'category',

        'description',

        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        'invoice_date',
        'due_date',
        'grace_days',

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        'status',
        'is_locked',
        'is_generated',

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        'total_invoices',
        'total_amount',
        'total_paid',
        'total_unpaid',

        /*
        |--------------------------------------------------------------------------
        | AUDIT
        |--------------------------------------------------------------------------
        */

        'generated_at',
        'closed_at',
        'cancelled_at',

        'closed_by',
        'created_by',
        'updated_by',

        /*
        |--------------------------------------------------------------------------
        | NOTES
        |--------------------------------------------------------------------------
        */

        'notes',
    ];

    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        'invoice_date' => 'date',
        'due_date' => 'date',

        'generated_at' => 'datetime',
        'closed_at' => 'datetime',
        'cancelled_at' => 'datetime',

        /*
        |--------------------------------------------------------------------------
        | BOOLEAN
        |--------------------------------------------------------------------------
        */

        'is_locked' => 'boolean',
        'is_generated' => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | INTEGER
        |--------------------------------------------------------------------------
        */

        'grace_days' => 'integer',
        'total_invoices' => 'integer',

        /*
        |--------------------------------------------------------------------------
        | DECIMAL
        |--------------------------------------------------------------------------
        */

        'total_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_unpaid' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function accountingPeriod(): BelongsTo
    {
        return $this->belongsTo(
            AccountingPeriod::class,
            'accounting_period_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IPL INVOICES
    |--------------------------------------------------------------------------
    |
    | nanti akan connect ke:
    | ipl_invoices.billing_period_id
    |
    */

    public function invoices(): HasMany
    {
        return $this->hasMany(
            InvoiceIPL::class,
            'billing_period_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function rt()
    {
        return $this->belongsTo(
            Rt::class
        );
    }

    public function scopeDraft(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            self::STATUS_DRAFT
        );
    }

    public function scopeOpen(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            self::STATUS_OPEN
        );
    }

    public function scopeGenerated(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            self::STATUS_GENERATED
        );
    }

    public function scopeClosed(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            self::STATUS_CLOSED
        );
    }

    public function scopeLocked(
        Builder $query
    ): Builder {

        return $query->where(
            'is_locked',
            true
        );
    }

    public function scopeForOrganization(
        Builder $query,
        int $organizationId
    ): Builder {

        return $query->where(
            'organization_id',
            $organizationId
        );
    }

    public function scopeLatest(
        Builder $query
    ): Builder {

        return $query
            ->latest('invoice_date');
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isGeneratedStatus(): bool
    {
        return $this->status === self::STATUS_GENERATED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    public function isGenerated(): bool
    {
        return $this->is_generated;
    }

    /*
    |--------------------------------------------------------------------------
    | CAN GENERATE INVOICE
    |--------------------------------------------------------------------------
    */

    public function canGenerate(): bool
    {
        return
            !$this->is_locked
            && $this->isOpen();
    }

    /*
    |--------------------------------------------------------------------------
    | CAN EDIT
    |--------------------------------------------------------------------------
    */

    public function canEdit(): bool
    {
        return
            !$this->is_locked
            && !$this->isClosed();
    }

    /*
    |--------------------------------------------------------------------------
    | LABEL
    |--------------------------------------------------------------------------
    */

    public function fullLabel(): string
    {
        return "{$this->code} - {$this->name}";
    }
}
