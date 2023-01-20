<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Participant extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'login_at' => 'datetime',
    ];

    public function getImageURL()
    {
        return isset($this->image) ? route('participant.user.picture.show') : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png';
    }

    /**
     * Get the Address for the Participant.
     */
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the Institution for the Participant.
     */
    public function institution()
    {
        return $this->hasOne(Institution::class);
    }

    /**
     * Get the Contact for the Participant.
     */
    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    /**
     * Get the Submissions for the Participant.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the Reviewer associated with the Participant.
     */
    public function reviewer()
    {
        return $this->hasOne(Reviewer::class);
    }

    public function getJoinedSince()
    {
        return Carbon::parse($this->created_at)->translatedFormat('j F Y');
    }

    public function getImageSrc()
    {
        if(isset($this->image)){
            $image = Storage::get('profile_picture/participant/' . $this->image);
            $type = pathinfo('profile_picture/participant/' . $this->image, PATHINFO_EXTENSION);
            return 'data:image/' . $type . ';base64,' . base64_encode($image);
        }

        return 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png';
    }
}
