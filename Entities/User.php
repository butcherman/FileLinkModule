<?php

namespace Modules\FileLinkModule\Entities;

use Carbon\Carbon;
use App\Models\User as UserBase;

class User extends UserBase
{
    public function __construct()
    {
        $this->appends[] = 'FileLinkCount';
        $this->appends[] = 'ExpiredLinkCount';
        parent::__construct();
    }

    public function FileLink()
    {
        return $this->hasMany(FileLink::class, 'user_id', 'user_id');
    }

    public function getFileLinkCountAttribute()
    {
        return $this->FileLink->count();
    }

    public function getExpiredLinkCountAttribute()
    {
        return $this->FileLink->where('expire', '<', Carbon::now())->count();
    }
}
