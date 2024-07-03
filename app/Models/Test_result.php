<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test_result extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'status',
        'status_code',
    ];

    /**
     * get endpoint
     */
    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}
