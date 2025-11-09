<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternetPackage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'bandwidth_up',
        'bandwidth_down',
        'price',
        'billing_cycle',
        'is_active',
        'duration_days',
        'ip_pool',
        'features'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array'
    ];

    // Force features to be array
    public function getFeaturesAttribute($value)
    {
        if (is_string($value)) {
            // Handle double-encoded JSON
            $decoded = json_decode($value, true);
            
            // If first decode gives us a string, decode again
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            
            return is_array($decoded) ? $decoded : [];
        }
        return $value ?? [];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function pppoeAccounts(): HasMany
    {
        return $this->hasMany(PPPoEAccount::class);
    }

    // Format bandwidth untuk display
    public function getBandwidthDisplayAttribute(): string
    {
        $up = $this->formatBandwidth($this->bandwidth_up);
        $down = $this->formatBandwidth($this->bandwidth_down);
        return "{$up}/{$down}";
    }

    public function formatBandwidth($kbps): string
    {
        if ($kbps >= 1024) {
            return round($kbps / 1024, 1) . ' Mbps';
        }
        return $kbps . ' Kbps';
    }

    public function getFormattedBandwidth($direction = null): string
    {
        if ($direction === 'up') {
            return $this->formatBandwidth($this->bandwidth_up);
        } elseif ($direction === 'down') {
            return $this->formatBandwidth($this->bandwidth_down);
        } else {
            // Return both if no direction specified
            $up = $this->formatBandwidth($this->bandwidth_up);
            $down = $this->formatBandwidth($this->bandwidth_down);
            return "{$down}/{$up}";
        }
    }
}
