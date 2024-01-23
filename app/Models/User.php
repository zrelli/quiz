<?php
namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RolesEnum;
use App\Models\Scopes\TenantUserScope;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Log\Logger;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, BelongsToTenant, HasRoles;
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantUserScope);
        parent::boot();
        static::saving(function ($user) {
            $user->password=   Hash::make($user->password);
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'user_id');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->tenant_id  && $this->hasRole(RolesEnum::ADMIN)) {
            $url = url()->current();
            $domain = Domain::where('tenant_id', $this->tenant_id)->first();
            if ($domain) {
                return  Str::contains($url, $domain->domain);
            }
            return false;
        } else {
            return    $this->hasRole(RolesEnum::SUPERADMIN);
        }
    }
}
