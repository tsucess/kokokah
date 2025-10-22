<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING WALLET SYSTEM\n";
echo "==================================================\n\n";

try {
    // 1. Create UserReward model if missing
    echo "🏆 Checking UserReward model...\n";
    if (!class_exists('App\Models\UserReward')) {
        echo "  ⚠️ UserReward model missing, creating it...\n";
        
        // Create the model file
        $userRewardModel = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "type",
        "amount",
        "description",
        "metadata",
        "earned_at"
    ];

    protected $casts = [
        "amount" => "decimal:2",
        "metadata" => "array",
        "earned_at" => "datetime"
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static methods for giving rewards
    public static function giveLoginReward(User $user)
    {
        // Check if already claimed today
        $today = now()->startOfDay();
        $existingReward = static::where("user_id", $user->id)
            ->where("type", "daily_login")
            ->where("earned_at", ">=", $today)
            ->first();

        if ($existingReward) {
            return null; // Already claimed
        }

        $amount = 10.00; // Daily login reward amount
        
        // Create reward record
        $reward = static::create([
            "user_id" => $user->id,
            "type" => "daily_login",
            "amount" => $amount,
            "description" => "Daily login reward",
            "earned_at" => now()
        ]);

        // Add to wallet
        $wallet = $user->getOrCreateWallet();
        $wallet->deposit($amount, "REWARD-" . $reward->id, "Daily login reward");

        return $reward;
    }

    public static function giveStudyReward(User $user, int $studyMinutes)
    {
        if ($studyMinutes < 30) {
            return null; // Minimum 30 minutes for reward
        }

        $amount = floor($studyMinutes / 30) * 5; // 5 NGN per 30 minutes
        
        $reward = static::create([
            "user_id" => $user->id,
            "type" => "study_time",
            "amount" => $amount,
            "description" => "Study time reward for {$studyMinutes} minutes",
            "metadata" => ["study_minutes" => $studyMinutes],
            "earned_at" => now()
        ]);

        $wallet = $user->getOrCreateWallet();
        $wallet->deposit($amount, "REWARD-" . $reward->id, "Study time reward");

        return $reward;
    }

    public static function giveCourseCompletionReward(User $user, $course)
    {
        $amount = 50.00; // Course completion reward
        
        $reward = static::create([
            "user_id" => $user->id,
            "type" => "course_completion",
            "amount" => $amount,
            "description" => "Course completion reward: " . $course->title,
            "metadata" => ["course_id" => $course->id],
            "earned_at" => now()
        ]);

        $wallet = $user->getOrCreateWallet();
        $wallet->deposit($amount, "REWARD-" . $reward->id, "Course completion reward");

        return $reward;
    }
}';
        
        file_put_contents(app_path('Models/UserReward.php'), $userRewardModel);
        echo "  ✅ Created UserReward model\n";
    } else {
        echo "  ✅ UserReward model already exists\n";
    }

    // 2. Create user_rewards table if missing
    echo "🏆 Checking user_rewards table...\n";
    if (!Schema::hasTable('user_rewards')) {
        Schema::create('user_rewards', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['daily_login', 'study_time', 'course_completion', 'quiz_completion', 'achievement']);
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->timestamp('earned_at');
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'earned_at']);
        });
        echo "  ✅ Created user_rewards table\n";
    } else {
        echo "  ✅ user_rewards table already exists\n";
    }

    // 3. Add missing methods to WalletService
    echo "💰 Adding missing methods to WalletService...\n";
    
    $walletServicePath = app_path('Services/WalletService.php');
    $walletServiceContent = file_get_contents($walletServicePath);
    
    // Check if methods exist
    $methodsToAdd = [];
    
    if (!str_contains($walletServiceContent, 'getWalletStats')) {
        $methodsToAdd[] = '
    /**
     * Get wallet statistics
     */
    public function getWalletStats(User $user)
    {
        $wallet = $user->getOrCreateWallet();
        
        return [
            "balance" => $wallet->balance,
            "currency" => $wallet->currency ?? "NGN",
            "total_deposits" => $wallet->getTotalCredits(),
            "total_withdrawals" => $wallet->getTotalDebits(),
            "total_transactions" => $wallet->transactions()->count(),
            "last_transaction" => $wallet->transactions()->latest()->first()
        ];
    }';
    }
    
    if (!str_contains($walletServiceContent, 'getTransactionHistory')) {
        $methodsToAdd[] = '
    /**
     * Get transaction history
     */
    public function getTransactionHistory(User $user, int $limit = 50)
    {
        $wallet = $user->getOrCreateWallet();
        
        return $wallet->transactions()
            ->with(["course", "relatedUser"])
            ->orderBy("created_at", "desc")
            ->limit($limit)
            ->get();
    }';
    }
    
    if (!str_contains($walletServiceContent, 'getRewardHistory')) {
        $methodsToAdd[] = '
    /**
     * Get reward history
     */
    public function getRewardHistory(User $user, int $limit = 20)
    {
        return $user->rewards()
            ->orderBy("earned_at", "desc")
            ->limit($limit)
            ->get();
    }';
    }
    
    if (!str_contains($walletServiceContent, 'getLoginStreak')) {
        $methodsToAdd[] = '
    /**
     * Get login streak
     */
    public function getLoginStreak(User $user)
    {
        $rewards = $user->rewards()
            ->where("type", "daily_login")
            ->orderBy("earned_at", "desc")
            ->get();
        
        $streak = 0;
        $currentDate = now()->startOfDay();
        
        foreach ($rewards as $reward) {
            $rewardDate = $reward->earned_at->startOfDay();
            
            if ($rewardDate->eq($currentDate) || $rewardDate->eq($currentDate->subDay())) {
                $streak++;
                $currentDate = $rewardDate;
            } else {
                break;
            }
        }
        
        return $streak;
    }';
    }
    
    if (!str_contains($walletServiceContent, 'getTotalRewardEarnings')) {
        $methodsToAdd[] = '
    /**
     * Get total reward earnings
     */
    public function getTotalRewardEarnings(User $user)
    {
        return $user->rewards()->sum("amount");
    }';
    }
    
    if (!str_contains($walletServiceContent, 'validateTransfer')) {
        $methodsToAdd[] = '
    /**
     * Validate transfer
     */
    public function validateTransfer(User $sender, User $recipient, float $amount)
    {
        $errors = [];
        
        if ($sender->id === $recipient->id) {
            $errors[] = "Cannot transfer to yourself";
        }
        
        if ($amount <= 0) {
            $errors[] = "Amount must be greater than 0";
        }
        
        $wallet = $sender->getOrCreateWallet();
        if ($wallet->balance < $amount) {
            $errors[] = "Insufficient balance";
        }
        
        return [
            "valid" => empty($errors),
            "errors" => $errors
        ];
    }';
    }
    
    if (!str_contains($walletServiceContent, 'canAffordCourse')) {
        $methodsToAdd[] = '
    /**
     * Check if user can afford course
     */
    public function canAffordCourse(User $user, $course, string $couponCode = null)
    {
        $wallet = $user->getOrCreateWallet();
        $price = $course->price;
        $discount = 0;
        
        // Apply coupon if provided
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where("code", $couponCode)->where("status", "active")->first();
            if ($coupon) {
                $discount = $coupon->discount_amount ?? 0;
                $price = max(0, $price - $discount);
            }
        }
        
        return [
            "can_afford" => $wallet->balance >= $price,
            "balance" => $wallet->balance,
            "course_price" => $course->price,
            "final_price" => $price,
            "discount" => $discount,
            "shortfall" => max(0, $price - $wallet->balance)
        ];
    }';
    }
    
    if (!empty($methodsToAdd)) {
        // Add methods before the closing brace
        $newContent = str_replace(
            '}' . PHP_EOL . '?>', 
            implode('', $methodsToAdd) . PHP_EOL . '}' . PHP_EOL . '?>', 
            $walletServiceContent
        );
        
        file_put_contents($walletServicePath, $newContent);
        echo "  ✅ Added " . count($methodsToAdd) . " missing methods to WalletService\n";
    } else {
        echo "  ✅ All methods already exist in WalletService\n";
    }

    // 4. Add missing methods to Wallet model
    echo "💳 Adding missing methods to Wallet model...\n";
    
    $walletModelPath = app_path('Models/Wallet.php');
    $walletModelContent = file_get_contents($walletModelPath);
    
    if (!str_contains($walletModelContent, 'getTotalDebits')) {
        $newMethod = '
    public function getTotalDebits()
    {
        return $this->transactions()->where("type", "debit")->where("status", "success")->sum("amount");
    }';
        
        $newContent = str_replace(
            '}' . PHP_EOL . '?>', 
            $newMethod . PHP_EOL . '}' . PHP_EOL . '?>', 
            $walletModelContent
        );
        
        file_put_contents($walletModelPath, $newContent);
        echo "  ✅ Added getTotalDebits method to Wallet model\n";
    } else {
        echo "  ✅ getTotalDebits method already exists in Wallet model\n";
    }

    echo "\n🎉 Wallet system fixes completed!\n";
    echo "✅ UserReward model created/verified\n";
    echo "✅ user_rewards table created/verified\n";
    echo "✅ WalletService methods added\n";
    echo "✅ Wallet model methods added\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
