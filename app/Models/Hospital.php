<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Hospital extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
