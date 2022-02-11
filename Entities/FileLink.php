<?php

namespace Modules\FileLinkModule\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileLink extends Model
{
    use HasFactory;

    protected $primaryKey = 'link_id';
    protected $guarded    = ['link_id', 'created_at', 'deleted_at'];
    protected $hidden     = ['created_at', 'updated_at', 'user_id'];
    protected $appends    = ['file_count', 'expire_formatted', 'is_expired'];
    protected $casts      = [
        'created_at'   => 'datetime:M d, Y',
        'updated_at'   => 'datetime:M d, Y',
        'allow_upload' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory()
    {
        return \Modules\FileLInkModule\Database\Factories\FileLinkFactory::new();
    }

    public function getFileCountAttribute()
    {
        return $this->FileLinkFile->count();
    }

    public function getExpireFormattedAttribute()
    {
        return Carbon::parse($this->expire)->format('M d, Y');
    }

    public function getIsExpiredAttribute()
    {
        return $this->expire < Carbon::now() ? true : false;
    }

    public function FileLinkFile()
    {
        return $this->hasMany(FileLinkFile::class, 'link_id', 'link_id');
    }
}
