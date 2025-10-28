<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','subject','content','status','priority','meta','closed_at'];
    protected $casts = ['meta'=>'array','closed_at'=>'datetime'];

    public function user(){ return $this->belongsTo(User::class); }
    public function messages(){ return $this->hasMany(Message::class,'receiver_id','user_id'); }
}
