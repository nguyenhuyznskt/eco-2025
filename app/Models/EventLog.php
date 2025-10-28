<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','action','entity_type','entity_id','payload','ip','meta','created_at'];
    public $timestamps = false; // thường ghi thời điểm thủ công
    protected $casts = ['payload'=>'array','meta'=>'array','created_at'=>'datetime'];

    public function user(){ return $this->belongsTo(User::class); }
}
