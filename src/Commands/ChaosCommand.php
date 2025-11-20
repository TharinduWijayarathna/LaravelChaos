<?php

namespace LaravelChaos\Testing\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;

class ChaosCommand extends Command
{
    public $signature = 'chaos:process';

    public $description = 'Deletes a random file at a random time based on configured interval.';

    public function handle(): int
    {
        if (! config('chaos.enabled', false)) {
            $this->info('Chaos is disabled.');
            return self::SUCCESS;
        }

        $nextRun = Cache::get('chaos.next_run');

        if (! $nextRun) {
            // First run or cache cleared, schedule for sometime in the next week
            $this->scheduleNextRun();
            $this->info('Chaos scheduled for next run.');
            return self::SUCCESS;
        }

        if (now()->lt($nextRun)) {
            $this->info('Not time for chaos yet.');
            return self::SUCCESS;
        }

        $this->unleashChaos();
        $this->scheduleNextRun();

        return self::SUCCESS;
    }

    protected function unleashChaos(): void
    {
        $paths = config('chaos.paths', []);
        $extensions = config('chaos.extensions', []);
        $disk = config('chaos.disk', 'local');

        if (empty($paths)) {
            $this->warn('No paths configured for chaos.');
            return;
        }

        $files = [];

        // If using local filesystem and absolute paths are provided
        if ($disk === 'local') {
             foreach ($paths as $path) {
                if (File::isDirectory($path)) {
                    $finder = Finder::create()->in($path)->files();
                    if (! empty($extensions)) {
                        foreach ($extensions as $ext) {
                            $finder->name("*.$ext");
                        }
                    }
                    
                    foreach ($finder as $file) {
                        $files[] = $file->getRealPath();
                    }
                }
            }
            
            if (empty($files)) {
                $this->info('No files found to destroy.');
                return;
            }

            $randomFile = $files[array_rand($files)];
            
            try {
                File::delete($randomFile);
                Log::warning("Chaos unleashed: Deleted file {$randomFile}");
                $this->info("Chaos unleashed: Deleted file {$randomFile}");
            } catch (\Exception $e) {
                Log::error("Chaos failed to delete file {$randomFile}: " . $e->getMessage());
            }

        } else {
             // Support for other disks if needed, but for now focusing on local as per typical use case
             // or using Storage facade if paths are relative to disk root.
             // Assuming paths are absolute for 'local' usage in this context as per implementation plan example.
             $this->warn('Only local disk usage with absolute paths is fully implemented in this version.');
        }
    }

    protected function scheduleNextRun(): void
    {
        $interval = config('chaos.interval', 'week');
        
        // Calculate random time within the interval
        $nextRun = match($interval) {
            'minute' => now()->addMinutes(rand(1, 1)),
            'hour' => now()->addMinutes(rand(1, 60)),
            'day' => now()->addMinutes(rand(1, 1440)), // 24 hours
            'week' => now()->addMinutes(rand(1, 10080)), // 7 days
            'month' => now()->addMinutes(rand(1, 43200)), // 30 days
            default => now()->addMinutes(rand(1, 10080)), // default to week
        };
        
        Cache::put('chaos.next_run', $nextRun);
        
        $this->info("Next chaos scheduled for: " . $nextRun->toDateTimeString());
    }
}
