<?php

namespace Modules\FileLinkModule\Entities;

use App\Models\FileUploads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileLinkFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'link_file_id';
    protected $guarded    = ['link_file_id', 'created_at', 'updated_at'];
    protected $hidden     = ['link_id', 'update_at'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
    ];

    public function FileUploads()
    {
        return $this->hasOne(FileUploads::class, 'file_id', 'file_id');
    }

    public function User()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
