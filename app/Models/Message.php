<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    use SoftDeletes;
    
    protected $table = 'message';
    protected $primaryKey = 'id';

    protected $fillable = [
        'link_id', 'from_id', 'to_id', 'message', 'title', 'file', 'message_type', 'status',
    ];

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $file_name = $obj->file;
            $realfile = explode(':', $file_name);

            $file = 'public/message/' . $realfile[0];
            if (Storage::exists($file)) {
                Storage::delete($file);
            }
        });
    }

    public function get_Enquiry() {
        $enquiry = Enquiry::find($this->link_id);
        return $enquiry;
    }

    public function get_Task() {
        $task = Task::find($this->link_id);
        return $task;
    }

    public function get_Itinerary() {
        $itinerary = Itinerary::find($this->link_id);
        return $itinerary;
    }
    
    public function get_From() {
        return $this->hasOne('App\Models\User', 'id', 'from_id');
    }

    public function get_To() {
        return $this->hasOne('App\Models\User', 'id', 'to_id');
    }
}
