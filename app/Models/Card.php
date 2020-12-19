<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'card_order',
        'kanban_column_id'
    ];
    use HasFactory;
}
