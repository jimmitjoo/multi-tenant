<?php

namespace App\Models;

use App\Mail\OrganizationInvite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class OrganizationInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'accepted_at',
        'invited_by_id',
        'organization_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->invited_by_id = auth()->id();
            $invitation->token = bin2hex(random_bytes(32));
            $invitation->expires_at = now()->addDays(1);
        });

        static::created(function ($invitation) {
            $invitation->sendEmail();
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by_id');
    }

    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function accept(): void
    {
        $this->update(['accepted_at' => now()]);
        $this->organization->members()->attach(auth()->user());
    }

    protected function sendEmail(): void
    {
        Mail::to($this->email)->send(new OrganizationInvite($this));
    }
}
