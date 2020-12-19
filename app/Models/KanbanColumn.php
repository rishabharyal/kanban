<?php

namespace App\Models;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    use HasFactory;

    protected $fillable  = [
        'title',
        'user_id'
    ];

    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
    	return $this
            ->hasMany(Card::class)
            ->orderBy('card_order', 'ASC');
    }
}
