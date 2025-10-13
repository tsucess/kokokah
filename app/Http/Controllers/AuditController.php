<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:admin');
    }

    /**
     * Get audit logs with filtering
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|exists:users,id',
                'action' => 'nullable|string',
                'model_type' => 'nullable|string',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'ip_address' => 'nullable|ip',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = AuditLog::with(['user'])->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->action) {
                $query->where('action', 'like', "%{$request->action}%");
            }

            if ($request->model_type) {
                $query->where('model_type', $request->model_type);
            }

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->ip_address) {
                $query->where('ip_address', $request->ip_address);
            }

            $auditLogs = $query->paginate($request->get('per_page', 50));

            // Add summary statistics
            $summary = [
                'total_logs' => $auditLogs->total(),
                'unique_users' => AuditLog::distinct('user_id')->count('user_id'),
                'most_common_actions' => $this->getMostCommonActions(),
                'recent_activity_count' => AuditLog::where('created_at', '>=', now()->subHours(24))->count()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'logs' => $auditLogs,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific audit log entry
     */
    public function show($id)
    {
        try {
            $auditLog = AuditLog::with(['user'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $auditLog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch audit log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user activity logs
     */
    public function getUserActivity(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            $validator = Validator::make($request->all(), [
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'action_type' => 'nullable|in:login,logout,create,update,delete,view',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = AuditLog::where('user_id', $userId)->orderBy('created_at', 'desc');

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->action_type) {
                $query->where('action', 'like', "%{$request->action_type}%");
            }

            $activities = $query->paginate($request->get('per_page', 50));

            // User activity summary
            $summary = [
                'total_activities' => $activities->total(),
                'last_login' => $this->getLastLogin($userId),
                'most_active_day' => $this->getMostActiveDay($userId),
                'activity_breakdown' => $this->getActivityBreakdown($userId),
                'login_frequency' => $this->getLoginFrequency($userId)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'activities' => $activities,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system events
     */
    public function getSystemEvents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_type' => 'nullable|in:system,security,error,warning,info',
                'severity' => 'nullable|in:low,medium,high,critical',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = AuditLog::where('event_type', 'system')->orderBy('created_at', 'desc');

            if ($request->event_type) {
                $query->where('event_type', $request->event_type);
            }

            if ($request->severity) {
                $query->where('severity', $request->severity);
            }

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $events = $query->paginate($request->get('per_page', 50));

            // System events summary
            $summary = [
                'total_events' => $events->total(),
                'critical_events' => AuditLog::where('severity', 'critical')->count(),
                'recent_errors' => AuditLog::where('event_type', 'error')
                                         ->where('created_at', '>=', now()->subHours(24))
                                         ->count(),
                'system_health' => $this->getSystemHealthStatus()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $events,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get security events
     */
    public function getSecurityEvents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'threat_level' => 'nullable|in:low,medium,high,critical',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'ip_address' => 'nullable|ip',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = AuditLog::where('event_type', 'security')->orderBy('created_at', 'desc');

            if ($request->threat_level) {
                $query->where('threat_level', $request->threat_level);
            }

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->ip_address) {
                $query->where('ip_address', $request->ip_address);
            }

            $securityEvents = $query->paginate($request->get('per_page', 50));

            // Security summary
            $summary = [
                'total_security_events' => $securityEvents->total(),
                'failed_login_attempts' => $this->getFailedLoginAttempts(),
                'suspicious_activities' => $this->getSuspiciousActivities(),
                'blocked_ips' => $this->getBlockedIPs(),
                'security_score' => $this->calculateSecurityScore()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $securityEvents,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch security events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function exportLogs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'format' => 'required|in:csv,json,pdf',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'filters' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = AuditLog::with(['user'])
                           ->whereBetween('created_at', [$request->date_from, $request->date_to]);

            // Apply additional filters
            if ($request->filters) {
                foreach ($request->filters as $key => $value) {
                    if ($value) {
                        $query->where($key, $value);
                    }
                }
            }

            $logs = $query->get();

            $fileName = $this->generateExportFile($logs, $request->format);

            return response()->json([
                'success' => true,
                'message' => 'Audit logs exported successfully',
                'data' => [
                    'file_name' => $fileName,
                    'download_url' => url("storage/exports/{$fileName}"),
                    'record_count' => $logs->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export audit logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getMostCommonActions()
    {
        return AuditLog::select('action', DB::raw('count(*) as count'))
                      ->groupBy('action')
                      ->orderBy('count', 'desc')
                      ->limit(5)
                      ->get();
    }

    private function getLastLogin($userId)
    {
        return AuditLog::where('user_id', $userId)
                      ->where('action', 'login')
                      ->latest()
                      ->first();
    }

    private function getMostActiveDay($userId)
    {
        return AuditLog::where('user_id', $userId)
                      ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                      ->groupBy('date')
                      ->orderBy('count', 'desc')
                      ->first();
    }

    private function getActivityBreakdown($userId)
    {
        return AuditLog::where('user_id', $userId)
                      ->select('action', DB::raw('count(*) as count'))
                      ->groupBy('action')
                      ->orderBy('count', 'desc')
                      ->get();
    }

    private function getLoginFrequency($userId)
    {
        $logins = AuditLog::where('user_id', $userId)
                         ->where('action', 'login')
                         ->where('created_at', '>=', now()->subDays(30))
                         ->count();

        return round($logins / 30, 2); // Average logins per day
    }

    private function getSystemHealthStatus()
    {
        $criticalEvents = AuditLog::where('severity', 'critical')
                                 ->where('created_at', '>=', now()->subHours(24))
                                 ->count();

        if ($criticalEvents > 10) return 'Poor';
        if ($criticalEvents > 5) return 'Fair';
        if ($criticalEvents > 0) return 'Good';
        return 'Excellent';
    }

    private function getFailedLoginAttempts()
    {
        return AuditLog::where('action', 'failed_login')
                      ->where('created_at', '>=', now()->subHours(24))
                      ->count();
    }

    private function getSuspiciousActivities()
    {
        return AuditLog::where('event_type', 'security')
                      ->where('threat_level', 'high')
                      ->where('created_at', '>=', now()->subHours(24))
                      ->count();
    }

    private function getBlockedIPs()
    {
        // Mock implementation - would track actual blocked IPs
        return ['192.168.1.100', '10.0.0.50'];
    }

    private function calculateSecurityScore()
    {
        $failedLogins = $this->getFailedLoginAttempts();
        $suspiciousActivities = $this->getSuspiciousActivities();
        
        $score = 100;
        $score -= min($failedLogins * 2, 30); // Deduct up to 30 points for failed logins
        $score -= min($suspiciousActivities * 5, 50); // Deduct up to 50 points for suspicious activities
        
        return max($score, 0);
    }

    private function generateExportFile($logs, $format)
    {
        $fileName = 'audit_logs_' . now()->format('Y_m_d_H_i_s') . '.' . $format;
        
        // Mock implementation - would generate actual export file
        switch ($format) {
            case 'csv':
                // Generate CSV content
                break;
            case 'json':
                // Generate JSON content
                break;
            case 'pdf':
                // Generate PDF content
                break;
        }
        
        return $fileName;
    }
}
