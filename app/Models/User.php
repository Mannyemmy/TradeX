<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Send the email verification notification.
     *
     * @return void
     */

    public function sendEmailVerificationNotification()
    {
        \App\Jobs\SendEmailVerification::dispatch($this);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'l_name', 'email', 'phone', 'country', 'password',
         'ref_by', 'status','taxamount ','username',
         'email_verified_at','gender', 'account', 'currency_code',
         'dashboard_banner_message', 'dashboard_banner_type', 'dashboard_banner_enabled',
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'account' => 'array', // Automatically convert JSON to array when retrieving
        'signal_strength_enabled' => 'boolean',
        'dashboard_banner_enabled' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function dp()
    {
        return $this->hasMany(Deposit::class, 'user');
    }

    public function wd()
    {
        return $this->hasMany(Withdrawal::class, 'user');
    }

    public function signalSubscriptions()
    {
        return $this->hasMany(SignalSubscription::class);
    }
    public function copyTrades()
    {
        return $this->hasMany(CopyTrade::class);
    }


    public function isCopyingExpert($expertId)
{
    return $this->copiedExperts()->where('expert_id', $expertId)->exists();
}

public function trades()
{
    return $this->hasMany(Trade::class);
}
public function copiedExperts()
{
    return $this->hasMany(Copy::class);
}

    public function copyPositions()
    {
        return $this->hasMany(CopyPosition::class);
    }

    public function activeCopyPositions()
    {
        return $this->hasMany(CopyPosition::class)->where('status', 'active');
    }

    public function tuser()
    {
        return $this->belongsTo(Admin::class, 'assign_to');
    }

    public function dplan()
    {
        return $this->belongsTo(Plans::class, 'plan');
    }

    public function plans()
    {
        return $this->hasMany(User_plans::class, 'user', 'id');
    }

    public static function search($search): \Illuminate\Database\Eloquent\Builder
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('username', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
    }

    /**
     * Get the available (unfrozen) account balance.
     */
    public function getAvailableBalAttribute(): float
    {
        return round($this->account_bal - $this->frozen_bal, 2);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function preIpoHoldings()
    {
        return $this->hasMany(PreIpoHolding::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallets::class, 'user');
    }

    public function canConnectMoreWallets()
    {
        return $this->wallets()->count() < 10;
    }

    public function botSubscriptions()
    {
        return $this->hasMany(BotSubscription::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    /**
     * Get the user's currency symbol (decoded HTML entity).
     */
    public function getCurrencySymbolAttribute(): string
    {
        return ExchangeRate::getSymbol($this->currency_code ?? 'USD');
    }

    /**
     * Convert a USD amount to the user's preferred currency.
     */
    public function convertToUserCurrency(?float $usdAmount): float
    {
        $rate = ExchangeRate::getRate($this->currency_code ?? 'USD');
        return round(($usdAmount ?? 0) * $rate, 2);
    }

    /**
     * Convert a user-currency amount back to USD.
     */
    public function convertToUsd(?float $userAmount): float
    {
        $rate = ExchangeRate::getRate($this->currency_code ?? 'USD');
        $userAmount = $userAmount ?? 0;
        if ($rate <= 0) {
            return $userAmount;
        }
        return round($userAmount / $rate, 2);
    }
}
