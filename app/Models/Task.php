<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'updated_at'];

    protected $with = ['client'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
