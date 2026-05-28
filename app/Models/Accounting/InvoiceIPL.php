<?php

namespace App\Models\Accounting;

use App\Models\Keluarga;
use App\Models\Organization;
use App\Models\Rumah;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceIPL extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'invoices';

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public const STATUS_UNPAID = 'unpaid';

    public const STATUS_PARTIAL = 'partial';

    public const STATUS_PAID = 'paid';

    public const STATUS_OVERDUE = 'overdue';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | IDENTITAS
        |--------------------------------------------------------------------------
        */

        'invoice_no',

        /*
        |--------------------------------------------------------------------------
        | RELATION
        |--------------------------------------------------------------------------
        */

        'organization_id',
        'billing_period_id',

        'rumah_id',
        'keluarga_id',
        'warga_id',

        /*
        |--------------------------------------------------------------------------
        | SNAPSHOT
        |--------------------------------------------------------------------------
        */

        'status_hunian_snapshot',
        'billing_rule_snapshot',

        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        'amount',
        'paid_amount',
        'remaining_amount',

        'status',
        'is_active',

        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        'due_date',
        'paid_at',

        /*
        |--------------------------------------------------------------------------
        | AUDIT
        |--------------------------------------------------------------------------
        */

        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'amount' => 'decimal:2',

        'paid_amount' => 'decimal:2',

        'remaining_amount' => 'decimal:2',

        'is_active' => 'boolean',

        'due_date' => 'date',

        'paid_at' => 'datetime',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ATTRIBUTE
    |--------------------------------------------------------------------------
    */

    protected $attributes = [

        'amount' => 0,

        'paid_amount' => 0,

        'remaining_amount' => 0,

        'status' => self::STATUS_UNPAID,

        'is_active' => true,
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Organization / RT
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    /**
     * Billing Period
     */
    public function billingPeriod(): BelongsTo
    {
        return $this->belongsTo(
            IplBillingPeriod::class,
            'billing_period_id'
        );
    }

    /**
     * Rumah
     */
    public function rumah(): BelongsTo
    {
        return $this->belongsTo(
            Rumah::class,
            'rumah_id'
        );
    }

    /**
     * Keluarga
     */
    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(
            Keluarga::class,
            'keluarga_id'
        );
    }

    /**
     * Warga
     */
    public function warga(): BelongsTo
    {
        return $this->belongsTo(
            Warga::class,
            'warga_id'
        );
    }

    /**
     * Creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Updater
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive(
        Builder $query
    ): Builder {
        return $query->where('is_active', true);
    }

    public function scopeUnpaid(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            self::STATUS_UNPAID
        );
    }

    public function scopePaid(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            self::STATUS_PAID
        );
    }

    public function scopeOverdue(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            self::STATUS_OVERDUE
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isUnpaid(): bool
    {
        return $this->status === self::STATUS_UNPAID;
    }

    public function isPartial(): bool
    {
        return $this->status === self::STATUS_PARTIAL;
    }

    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function ($invoice) {
            /*
            |--------------------------------------------------------------------------
            | AUTO REMAINING
            |--------------------------------------------------------------------------
            */
            if (
                empty($invoice->remaining_amount)
            ) {
                $invoice->remaining_amount =
                    $invoice->amount ?? 0;
            }
            /*
            |--------------------------------------------------------------------------
            | AUTO STATUS
            |--------------------------------------------------------------------------
            */
            if (
                empty($invoice->status)
            ) {
                $invoice->status =
                    self::STATUS_UNPAID;
            }
        });
    }
}
