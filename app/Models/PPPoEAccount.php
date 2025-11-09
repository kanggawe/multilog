<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PPPoEAccount extends Model
{
    protected $fillable = [
        'customer_id',
        'internet_package_id',
        'username',
        'password',
        'static_ip',
        'profile_name',
        'status',
        'last_login',
        'last_ip',
        'bytes_in',
        'bytes_out',
        'expires_at',
        'notes'
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function internetPackage(): BelongsTo
    {
        return $this->belongsTo(InternetPackage::class);
    }

    // Format bytes untuk display
    public function getFormattedBytesInAttribute(): string
    {
        return $this->formatBytes($this->bytes_in);
    }

    public function getFormattedBytesOutAttribute(): string
    {
        return $this->formatBytes($this->bytes_out);
    }

    public function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes > 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Check if account is expired
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Generate username otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            if (empty($account->username)) {
                $customer = Customer::find($account->customer_id);
                if ($customer) {
                    // Generate username dari customer code + nomor urut
                    $baseUsername = strtolower(str_replace('CUST', 'usr', $customer->customer_code));
                    $counter = 1;
                    $username = $baseUsername;
                    
                    while (static::where('username', $username)->exists()) {
                        $username = $baseUsername . $counter;
                        $counter++;
                    }
                    
                    $account->username = $username;
                }
            }
            
            // Generate random password jika kosong
            if (empty($account->password)) {
                $account->password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
            }
        });
    }
}
