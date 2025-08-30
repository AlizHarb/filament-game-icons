<?php

namespace Alizharb\FilamentGameIcons\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * SyncGameIconsCommand Command
 *
 * Keeps your GameIcons enum synchronized with the installed
 * codeat3/blade-game-icons package by adding missing cases.
 *
 * @usage
 *   php artisan sync:game-icons-enum
 *   php artisan sync:game-icons-enum --dry-run
 */
class SyncGameIconsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:game-icons-enum 
        {--path=app/Enums/GameIcons.php : Path to the enum file}
        {--dry-run : Show missing icons without modifying the file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '🔄 Synchronize GameIcons enum with the blade-game-icons package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->newLine();
        $this->components->info('🚀 Starting Game Icons Synchronization');

        $packagePath = base_path('vendor/codeat3/blade-game-icons/resources/svg');
        $enumPath = base_path($this->option('path'));

        // Validate requirements
        if (! File::exists($packagePath)) {
            $this->components->error('❌ The codeat3/blade-game-icons package is not installed.');
            $this->warn('💡 Run: composer require codeat3/blade-game-icons');
            return self::FAILURE;
        }

        if (! File::exists($enumPath)) {
            $this->components->error("❌ Enum file not found at: {$this->option('path')}");
            $this->warn('💡 Run your generator once to create it first.');
            return self::FAILURE;
        }

        // Collect icons from SVGs
        $svgIcons = collect(File::files($packagePath))
            ->map(fn ($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->sort()
            ->values();

        $this->components->info("📂 Found {$svgIcons->count()} SVG icons");

        // Collect icons from enum
        $enumContent = File::get($enumPath);
        preg_match_all("/case\s+\w+\s+=\s+'([^']+)'/", $enumContent, $matches);

        $enumIcons = collect($matches[1])->sort()->values();
        $this->components->info("📜 Found {$enumIcons->count()} icons in enum");

        // Detect missing icons
        $missingIcons = $svgIcons->diff($enumIcons);

        if ($missingIcons->isEmpty()) {
            $this->newLine();
            $this->components->success('✨ All icons are already synchronized!');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->components->warn("⚠️  Found {$missingIcons->count()} missing icons:");

        foreach ($missingIcons->take(10) as $icon) {
            $this->line("  • {$icon}");
        }
        if ($missingIcons->count() > 10) {
            $this->line("  ... and " . ($missingIcons->count() - 10) . " more");
        }

        // Dry-run mode
        if ($this->option('dry-run')) {
            $this->newLine();
            $this->components->info('🧐 Dry-run mode — no changes made.');
            return self::SUCCESS;
        }

        // Confirm
        if (! $this->confirm('👉 Do you want to add these icons to the enum?')) {
            $this->components->warning('❌ Operation cancelled by user.');
            return self::SUCCESS;
        }

        // Add missing cases
        $newCases = $missingIcons
            ->map(fn ($icon) => $this->generateEnumCase($icon))
            ->join("\n    ");

        $enumContent = $this->insertCasesIntoEnum($enumContent, $newCases);

        File::put($enumPath, $enumContent);

        $this->newLine();
        $this->components->success("✅ Synchronization complete! Added {$missingIcons->count()} new icons.");
        return self::SUCCESS;
    }

    /**
     * Generate enum case string from an icon name.
     */
    private function generateEnumCase(string $iconName): string
    {
        $caseName = Str::of($iconName)
            ->kebab()
            ->split('/-/')
            ->map(fn ($part) => Str::ucfirst($part))
            ->join('');

        return "case {$caseName} = 'gameicon-{$iconName}';";
    }

    /**
     * Insert new enum cases into the existing file.
     */
    private function insertCasesIntoEnum(string $enumContent, string $newCases): string
    {
        // Try to insert before the first docblock or method
        $pattern = '/(\s+case\s+\w+\s+=\s+\'[^\']+\';\s*)(\s*\/\*\*|\s*public\s+function)/s';

        if (preg_match($pattern, $enumContent)) {
            return preg_replace(
                $pattern,
                "$1    {$newCases}\n$2",
                $enumContent
            );
        }

        // Fallback: append before the closing brace
        return str_replace("\n}", "\n    {$newCases}\n}", $enumContent);
    }
}
