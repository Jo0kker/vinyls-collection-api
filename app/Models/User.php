<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'avatar',
        'first_name',
        'last_name',
        'phone',
        'birth_date',
        'audio_equipment',
        'influence',
        'description',
        'discogs_id',
        'discogs_token',
        'discogs_token_secret',
        'discogs_username',
        'discogs_avatar',
        'discogs_data'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'discogs_token',
        'discogs_token_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'discogs_data' => 'array',
    ];

    /**
     * Get the user's badges.
     *
     * @return HasMany<Badge>
     */
    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class);
    }

    /**
     * Get the user's trades.
     *
     * @return HasMany<Trade>
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Get the user's search.
     *
     * @return HasMany<Search>
     */
    public function searches(): HasMany
    {
        return $this->hasMany(Search::class);
    }

    /**
     * Get the user's Collections.
     *
     * @return HasMany<Collection>
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * Get the user's collection vinyls.
     *
     * @return HasMany<CollectionVinyl>
     */
    public function collectionVinyls(): HasMany
    {
        return $this->hasMany(CollectionVinyl::class);
    }

    /**
     * Get the total number of vinyls in all collections.
     *
     * @return int
     */
    public function getCollectionVinylsCountAttribute(): int
    {
        return $this->collectionVinyls()->count();
    }

    public function sendPasswordResetNotification($param)
    {
        $url = config('app.web') . '/reset-password' . $param;

        $this->notify(new ResetPasswordNotification($url));
    }
}
