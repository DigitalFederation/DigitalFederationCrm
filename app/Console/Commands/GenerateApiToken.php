<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-token {name} {permissions*}'; // permissions separated by space
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new API token for third-party services ';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tokenName = $this->argument('name');
        $permissions = $this->argument('permissions'); // Array of permissions
        $tokenValue = Str::random(80); // Generate a random token

        // Here you can store the token in your database or configuration
        $apiToken = new ApiToken([
            'name' => $tokenName,
            'token' => ApiToken::hashToken($tokenValue), // Store a hash of the token
            'permissions' => json_encode($permissions), // Store permissions as JSON
        ]);
        $apiToken->save();

        $this->info("API Token for '{$tokenName}': $tokenValue");
        $this->info('Permissions: ' . implode(', ', $permissions));

    }
}
