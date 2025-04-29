<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopItem extends Model // Ganti nama class dari Todo menjadi ShopItem
{
    use HasFactory;

    protected $table = 'shop_items'; // Pastikan nama tabelnya adalah 'shop_items'

    protected $fillable = [
        'user_id',
        'title',
        'quantity', // Mengganti 'description' menjadi 'quantity'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
