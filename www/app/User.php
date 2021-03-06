<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'sid', 'name', 'email', 'recovery_email', 'phone', 'password', 'phone_verified_at',
        'timezone_id', 'pkcs12', 'last_login', 'terms', 'api_token', 'locked', 'customer_type_id',
        'phone_secondary', 'country', 'state', 'city', 'type', 'date_of_birth', 'ldap_user',
        'recovery_email', 'token_2fa', 'token_2fa_expiry', 'change_password', 'updated_by_id',
        'login_attempts', 'login_max_attempts', 'job_and_position_id', 'client',
        'disadvantaged', 'google2fa_secret', 'otp_secret', 'otp_exemption', 'assigned_to',
        'user_status_id', 'avatar_file_id', 'employee', 'auth_type_id', 'life_cycle_id', 'lead_status_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fname() {
        return explode(' ', $this->getAttribute('name'))[0];
    }

    public function account() {
        return $this->hasOne(Account::class);
    }

    public function setting() {
        return $this->hasOne(Setting::class);
    }

    public function sessions() {
        return $this->hasMany(UserSession::class);
    }

    public function session() {
        return $this->hasMany(UserSession::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function lockAccount() {
        return $this->setAttribute('locked', true);
    }

    public function unlockAccount() {
        return $this->setAttribute('locked', false);
    }

    public function getRoles() {
        return $this->getRoleNames();
    }

    public function userType() {
        return $this->hasOne(UserType::class);
    }

    public function getTimezone() {
        $timezone = Timezone::find($this->getAttribute('timezone_id'));
        return $timezone->value;
    }

    public function tokens() {
        return $this->hasMany(Token::class);
    }

    public function createSid() {
        $name = explode(' ', $this->getAttribute('name'));
        $limit = 5;
        if (is_array($name)) {
            if (count($name) >= 2) {
                if (strlen($name[1]) < $limit) {
                    $name[1] = substr($name[1], 0, 6);
                }

                $sid = $name[0][0] . $name[1];
                if (User::where('sid', $sid)->exists()) {
                    list($alpha, $numeric) = sscanf($sid, "%[a-zA-Z]%d");

                    $sid = $alpha . ($numeric + 1);
                }

                while(User::where('sid', $sid)->exists()) {
                    $newSid = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $sid);
                    $num = $newSid[1] + 1;
                    $sid = $newSid[0] . $num;
                }
                return strtolower($sid);
            }
        }
        return 'error';
    }

    public function getAvatarUrl() {
        if (!is_null($this->getAttribute('avatar_file_id'))) {
            $avatar = File::find($this->getAttribute('avatar_file_id'));
            return route("get-avatar", ['user_uid' => $this->getAttribute('uid'), 'uuid' => $avatar->uuid, 'name' => $avatar->name]);
        }
    }

    public static function findByUid($uid) {
        $user = self::where('uid', $uid)->first();
        return (!is_null($user) ? $user : abort(404));
    }
}
