<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    
    protected $table = 'task';
    protected $primaryKey = 'id';

    protected $fillable = ["id", "itinerary_id", "reference_number", "task_name", "task_daily", "task_des", "assigned_by", "assigned_to", "start_date", "start_time", "end_date", "end_time", "priority", "status"];

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $task_id = $obj->id;

            $task_detail = TaskDetail::where('task_id', $task_id)->get();
            if (!empty($task_detail)) {
                $task_detail->each(function ($con) {
                    $con->delete();
                });
            }
        });
    }

    public function get_itinerary()
    {
        return $this->hasOne('App\Models\Itinerary', 'id', 'itinerary_id');
    }

    public function get_assigned_by()
    {
        $account = User::find($this->assigned_by);
        $assigned_by = $account->first_name . " " . $account->last_name;
        return $assigned_by;
    }

    public function get_assigned_to()
    {
        $account = User::find($this->assigned_to);
        $assigned_to = $account->first_name . " " . $account->last_name;
        return $assigned_to;
    }
    public function get_priority()
    {
        return $this->priority;
    }
    public function get_status()
    {
        return $this->status;
    }
}