<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $first_name
 * @property string $email
 * @property string $locale
 * @property string $phone
 * @property bool $phone_verified
 * @property string $password
 * @property string $verify_token
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property bool $phone_auth
 * @property string $role
 * @property string $status
 * @property string $avatar_url
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use AuditableTrait, Filterable, HasApiTokens, HasFactory, LogsActivity, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'name',
        'last_name',
        'email',
        'password',
        'locale',
        'email_verified_at',
        'phone',
        'avatar_url',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const array LOCALES = ['en', 'uk'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verify_token_expire' => 'datetime',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail);
    }

    // app/Models/User.php

    public function socialAccounts()
    {
        return $this->hasMany(UserSocial::class);
    }

    public function hasLinkedProvider(string $provider): bool
    {
        return $this->socialAccounts()->where('provider', $provider)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    public function isModerator()
    {
        return $this->roles()->where('name', 'moderator')->exists();
    }

    public function hasPermission($permissionKey)
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionKey) {
                $query->where('key', $permissionKey);
            })->exists();
    }

    public function getPermissions(): array
    {
        return $this->roles()
            ->with('permissions:key,id')
            ->get()
            ->flatMap(fn ($role) => $role->permissions->pluck('key'))
            ->unique()
            ->values()
            ->toArray();
    }

    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty.');
        }
        if (! empty($this->phone_verify_token) && $this->phone_verify_token_expire &&
            $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Phone is already requested.');
        }
        $this->phone_verified = false;
        $this->phone_verify_token = (string) random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
        }
        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function addToFavorites($id): void
    {
        if ($this->hasIsFavorites($id)) {
            throw new \DomainException('This advert is already in favorites.');
        }
        $this->favorites()->attach($id);
    }

    public function removeFromFavorites($id): void
    {
        if (! $this->hasIsFavorites($id)) {
            throw new \DomainException('This advert is not in favorites.');
        }
        $this->favorites()->detach($id);
    }

    public function hasIsFavorites($id): bool
    {
        return $this->favorites()->where('id', $id)->exists();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'advert_advert_favorites', 'user_id', 'advert_id');
    }

    public function findForPassport($identifier)
    {
        return self::where('email', $identifier)->whereNotNull('email_verified_at')->first();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // логуються тільки ці поля
            ->logOnlyDirty() // тільки коли змінились
            ->useLogName('user') // назва для фільтрації логів
            ->setDescriptionForEvent(fn (string $eventName) => "User was {$eventName}");
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }
}
