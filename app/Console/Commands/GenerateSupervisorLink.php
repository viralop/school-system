<?php

namespace App\Console\Commands;

use App\Models\SecureLink;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('supervisor:generate-link 
    {--expires= : Minutes until the link expires (default: never)} 
    {--uses=1 : Maximum number of times the link can be used}
')]
#[Description('Generate a secure registration link for a supervisor')]
class GenerateSupervisorLink extends Command
{
    public function handle(): int
    {
        $expiresMinutes = $this->option('expires') ? (int) $this->option('expires') : null;
        $maxUses = (int) $this->option('uses');

        $result = SecureLink::generate(
            purpose: 'supervisor_registration',
            expiresMinutes: $expiresMinutes,
            maxUses: $maxUses,
        );

        $this->info('Supervisor registration link generated successfully!');
        $this->newLine();
        $this->line("  URL:         <fg=yellow>{$result['url']}</>");
        $this->line("  Expires:     " . ($expiresMinutes ? "{$expiresMinutes} minutes" : 'Never'));
        $this->line("  Max uses:    {$maxUses}");
        $this->line("  Link ID:     {$result['secure_link']->id}");
        $this->newLine();
        $this->warn('  IMPORTANT: This URL is shown only once. Copy it now.');
        $this->warn('  The plain token is NOT stored anywhere in the database.');

        return self::SUCCESS;
    }
}
