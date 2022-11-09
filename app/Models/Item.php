<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'unit_id', 'vk_price', 'ek_price'];


public function unit(): belongsTo
{
    return $this->belongsTo(Unit::class);
}
}
