<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use Illuminate\Console\Command;

class RevokeApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:revoke-token {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke an API token for third-party services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tokenName = $this->argument('name');

        // Assuming you're using a model, delete the token
        $result = ApiToken::where('name', $tokenName)->delete();

        if ($result) {
            $this->info("Token '{$tokenName}' revoked successfully.");
        } else {
            $this->error("Token '{$tokenName}' not found.");
        }
    }
}
