<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProcessDailyReward
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request first
        $response = $next($request);

        // Check if user is authenticated and process daily reward
        if ($request->user()) {
            $this->processDailyReward($request->user());
        }

        return $response;
    }

    /**
     * Process daily login reward for the user
     */
    private function processDailyReward($user)
    {
        try {
            // Check if daily rewards are enabled
            $enableDailyRewards = \App\Models\Setting::get('enable_daily_rewards', true);
            if (!$enableDailyRewards) {
                return;
            }

            // Check if user already got reward today
            $todayReward = \App\Models\UserReward::where('user_id', $user->id)
                                               ->where('reward_type', 'daily_login')
                                               ->whereDate('date', today())
                                               ->exists();

            if ($todayReward) {
                return; // Already rewarded today
            }

            // Update user's last login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip()
            ]);

            // Give daily login reward
            \App\Models\UserReward::giveLoginReward($user);

        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::error('Failed to process daily reward: ' . $e->getMessage());
        }
    }
}
