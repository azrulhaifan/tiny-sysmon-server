<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'description',
        'retention',
        'last_metrics_dt',
        'is_active'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($server) {
            if (empty($server->api_key)) {
                $server->api_key = Str::uuid();
            }
        });
    }

    /**
     * Get the metrics for the server.
     */
    public function metrics()
    {
        return $this->hasMany(ServerMetric::class);
    }
}
