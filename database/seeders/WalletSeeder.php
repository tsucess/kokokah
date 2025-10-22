<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class WalletSeeder extends Seeder
{
    public function run()
    {
        echo "🏦 Creating wallets for users without wallets...\n";
        
        // Get users without wallets
        $usersWithoutWallets = User::whereDoesntHave('wallet')->get();
        
        $walletsCreated = 0;
        foreach ($usersWithoutWallets as $user) {
            // Create wallet with random starting balance for demo purposes
            $balance = 0;
            
            // Give some users random demo balances
            if ($user->role === 'student' && rand(1, 4) === 1) {
                $balance = rand(500, 5000); // Random balance between ₦500 - ₦5000
            } elseif ($user->role === 'instructor') {
                $balance = rand(2000, 10000); // Instructors get higher balances
            } elseif ($user->role === 'admin') {
                $balance = rand(5000, 20000); // Admins get highest balances
            }
            
            Wallet::create([
                'user_id' => $user->id,
                'balance' => $balance,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $walletsCreated++;
        }
        
        echo "✅ Created {$walletsCreated} wallets\n";
        echo "💰 Total users with wallets: " . User::whereHas('wallet')->count() . "\n";
        
        // Show wallet statistics
        $totalBalance = Wallet::sum('balance');
        $avgBalance = Wallet::avg('balance');
        $maxBalance = Wallet::max('balance');
        
        echo "📊 Wallet Statistics:\n";
        echo "   💵 Total Balance: ₦" . number_format($totalBalance, 2) . "\n";
        echo "   📈 Average Balance: ₦" . number_format($avgBalance, 2) . "\n";
        echo "   🏆 Highest Balance: ₦" . number_format($maxBalance, 2) . "\n";
    }
}
