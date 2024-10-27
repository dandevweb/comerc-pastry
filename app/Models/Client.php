<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $hidden = ['deleted_at'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
