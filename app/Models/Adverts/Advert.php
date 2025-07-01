<?php

namespace App\Models\Adverts;

use App\Models\Adverts\Boost\AdvertAutoLift;
use App\Models\Adverts\Boost\AdvertBoost;
use App\Models\Adverts\Boost\AdvertService;
use App\Models\Adverts\Dialog\Dialog;
use App\Models\Location;
use App\Models\User;
use App\Traits\Filterable;
use App\Traits\Searchable;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $region_id
 * @property string $title
 * @property string $content
 * @property int $price
 * @property string $address
 * @property string $status
 * @property string $reject_reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $published_at
 * @property Carbon $expires_at
 * @property User $user
 * @property Location $region
 * @property Category $category
 * @property Value[] $values
 * @property Photo[] $photos
 *
 * @method Builder forUser(User $user)
 */
class Advert extends Model implements Auditable
{
    use AuditableTrait, Filterable, HasFactory, HasSlug, LogsActivity, Searchable, SoftDeletes;

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_MODERATION = 'moderation';

    public const string STATUS_ACTIVE = 'active';

    public const string STATUS_CLOSED = 'closed';

    public const string STATUS_MODERATION_FAIL = 'moderation_fail';

    public const string STATUS_BANNED = 'banned';

    public const string STATUS_DELETED = 'deleted';

    public const string STATUS_EXPIRED = 'expired';

    protected $table = 'advert_adverts';

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $appends = ['is_favorited'];

    public function services()
    {
        return $this->hasMany(AdvertService::class);
    }

    public function boosts()
    {
        return $this->hasMany(AdvertBoost::class);
    }

    public function scopeWithActiveBoosts($query)
    {
        return $query->whereHas('boosts', function ($q) {
            $q->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function value()
    {
        return $this->hasMany(Value::class, 'advert_id');
    }

    public function getValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->attribute_id === $id) {
                return $value->value;
            }
        }

        return null;
    }

    public function photo()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }

    public function firstPhoto()
    {
        return $this->hasOne(Photo::class, 'advert_id', 'id')->orderBy('id');
    }

    public function region()
    {
        return $this->belongsTo(Location::class, 'region_id');
    }

    public function autoLifts()
    {
        return $this->hasMany(AdvertAutoLift::class);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function sendToModeration(): void
    {
        if (! $this->isDraft()) {
            throw new DomainException('Advert is not draft.');
        }
        //        if (!\count($this->photo)) {
        //            throw new \DomainException('Upload photos.');
        //        }
        $this->update([
            'status' => self::STATUS_MODERATION,
            'reject_reason' => '',
        ]);
    }

    public function moderate(Carbon $date): void
    {
        if ($this->status !== self::STATUS_MODERATION) {
            throw new DomainException('Advert is not sent to moderation.');
        }
        $this->update([
            'published_at' => $date,
            'expires_at' => $date->copy()->addDays(15),
            'status' => self::STATUS_ACTIVE,
            'reject_reason' => '',
        ]);
    }

    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function backToDraft(): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
        ]);
    }

    public function expire(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    public function active(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'reject_reason' => '',
        ]);
    }

    public function close(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    public function writeClientMessage(int $fromId, string $message): Model
    {
        return $this->getOrCreateDialogWith($fromId)->writeMessageByClient($fromId, $message);
    }

    public function writeOwnerMessage(int $toId, string $message): Model
    {
        return $this->getDialogWith($toId)->writeMessageByOwner($this->user_id, $message);
    }

    public function readClientMessages(int $userId): void
    {
        $this->getDialogWith($userId)->readByClient();
    }

    public function readOwnerMessages(int $userId): void
    {
        $this->getDialogWith($userId)->readByOwner();
    }

    private function getDialogWith(int $userId): Dialog
    {
        $dialog = $this->dialogs()->where([
            'user_id' => $this->user_id,
            'client_id' => $userId,
        ])->first();
        if (! $dialog) {
            throw new DomainException('Dialog not found.');
        }

        return $dialog;
    }

    private function getOrCreateDialogWith(int $userId): Dialog
    {
        if ($userId === $this->user_id) {
            throw new DomainException('Cannot create dialog to myself.');
        }

        return $this->dialogs()->firstOrCreate([
            'user_id' => $this->user_id,
            'client_id' => $userId,
        ]);
    }

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Чернетка',
            self::STATUS_MODERATION => 'На модерації',
            self::STATUS_ACTIVE => 'Активний',
            self::STATUS_CLOSED => 'Закритий',
            self::STATUS_MODERATION_FAIL => 'Не пройшов модерацію',
            self::STATUS_BANNED => 'Заблокований',
            self::STATUS_DELETED => 'Видалений',
            self::STATUS_EXPIRED => 'Закінчився термін дії',
        ];
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeForCategory(Builder $query, Category $category): Builder
    {
        return $query->whereIn('category_id', array_merge(
            [$category->id],
            $category->descendants()->pluck('id')->toArray()
        ));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeFavoriteByUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('favorites', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'advert_advert_favorites', 'advert_id', 'user_id');
    }

    public function dialogs(): HasMany
    {
        return $this->hasMany(Dialog::class, 'advert_id', 'id');
    }

    public function getIsFavoritedAttribute()
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $this->favorites->contains('id', $user->id);
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }

    public function toElasticsearchDocumentArray(): array
    {
        return $this->toArray();
    }

    public function getSearchableFields(): array
    {
        return [
            'title',
            'price',
            'address',
            'content',
            'status',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // логуються тільки ці поля
            ->logOnlyDirty() // тільки коли змінились
            ->useLogName('advert') // назва для фільтрації логів
            ->setDescriptionForEvent(fn (string $eventName) => "Advert was {$eventName}");
    }
}
