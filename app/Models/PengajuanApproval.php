<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanApproval extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_approval';

    protected $fillable = [
        'pengajuan_id',
        'level',
        'status',
        'approved_by',
        'approved_at',
        'catatan'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanPerubahan::class, 'pengajuan_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvals()
    {
        return $this->hasMany(PengajuanApproval::class, 'pengajuan_id')
            ->orderBy('created_at');
    }
}
