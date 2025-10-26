<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Scopes
    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set($key, $value, $type = 'string', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }

    public static function has($key)
    {
        return static::where('key', $key)->exists();
    }

    public static function forget($key)
    {
        return static::where('key', $key)->delete();
    }

    public static function getAllSettings()
    {
        return static::pluck('value', 'key')->toArray();
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'array':
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function getCastedValue()
    {
        return static::castValue($this->value, $this->type);
    }

    // Boot method to clear cache when settings change
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function () {
            // Clear settings cache if you implement caching
            // Cache::forget('settings');
        });
        
        static::deleted(function () {
            // Clear settings cache if you implement caching
            // Cache::forget('settings');
        });
    }
}
