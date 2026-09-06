<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Users\Pet;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\Costumer_profile;
use App\Models\Bussines\Reservation;
use App\Models\Survey\Survey_response;
use App\Models\Bussines\Lead;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'user_id');
    }

    public function costumerProfile()
    {
        return $this->hasOne(Costumer_profile::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
    public function leads()
    {
        return $this->hasMany(Lead::class, 'converted_user_id');
    }

    public function surveyResponses()
    {
        return $this->hasMany(Survey_response::class, 'user_id');
    }
}
