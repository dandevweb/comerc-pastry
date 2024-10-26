<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
    public const UPDATED_AT = null;

    protected $primaryKey = 'email';
}
