<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'subtype',
        'parameters',
        'frequency',
        'format',
        'recipients',
        'is_active',
        'next_run_at',
        'last_run_at',
        'created_by'
    ];

    protected $casts = [
        'parameters' => 'array',
        'recipients' => 'array',
        'is_active' => 'boolean',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime'
    ];

    protected $dates = [
        'next_run_at',
        'last_run_at'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDueForExecution($query)
    {
        return $query->where('is_active', true)
                    ->where('next_run_at', '<=', now());
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function isDue()
    {
        return $this->is_active && $this->next_run_at <= now();
    }

    public function calculateNextRun()
    {
        $nextRun = Carbon::parse($this->next_run_at);

        switch ($this->frequency) {
            case 'daily':
                $nextRun->addDay();
                break;
            case 'weekly':
                $nextRun->addWeek();
                break;
            case 'monthly':
                $nextRun->addMonth();
                break;
            case 'quarterly':
                $nextRun->addMonths(3);
                break;
            case 'yearly':
                $nextRun->addYear();
                break;
        }

        $this->next_run_at = $nextRun;
        $this->save();

        return $this;
    }

    public function markAsExecuted()
    {
        $this->last_run_at = now();
        $this->calculateNextRun();
        return $this;
    }

    public function activate()
    {
        $this->is_active = true;
        $this->save();
        return $this;
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
        return $this;
    }

    public function getRecipientsListAttribute()
    {
        return implode(', ', $this->recipients);
    }

    public function getFrequencyLabelAttribute()
    {
        return ucfirst($this->frequency);
    }

    public function getFormatLabelAttribute()
    {
        return strtoupper($this->format);
    }

    // Static methods
    public static function createScheduledReport($data)
    {
        $nextRun = now();
        
        switch ($data['frequency']) {
            case 'daily':
                $nextRun->addDay();
                break;
            case 'weekly':
                $nextRun->addWeek();
                break;
            case 'monthly':
                $nextRun->addMonth();
                break;
            case 'quarterly':
                $nextRun->addMonths(3);
                break;
            case 'yearly':
                $nextRun->addYear();
                break;
        }

        $data['next_run_at'] = $nextRun;
        
        return static::create($data);
    }

    public static function getDueReports()
    {
        return static::dueForExecution()->get();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (!$report->next_run_at) {
                $report->calculateNextRun();
            }
        });
    }
}
