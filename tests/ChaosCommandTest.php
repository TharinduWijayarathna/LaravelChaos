<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use function Pest\Laravel\artisan;

it('does not delete files if disabled', function () {
    Config::set('chaos.enabled', false);
    
    artisan('chaos:process')
        ->expectsOutput('Chaos is disabled.')
        ->assertExitCode(0);
});

it('schedules next run if not scheduled', function () {
    Config::set('chaos.enabled', true);
    Cache::shouldReceive('get')->with('chaos.next_run')->andReturn(null);
    Cache::shouldReceive('put')->once();

    artisan('chaos:process')
        ->expectsOutput('Chaos scheduled for next run.')
        ->assertExitCode(0);
});

it('does not delete files if not time yet', function () {
    Config::set('chaos.enabled', true);
    Cache::shouldReceive('get')->with('chaos.next_run')->andReturn(now()->addHour());

    artisan('chaos:process')
        ->expectsOutput('Not time for chaos yet.')
        ->assertExitCode(0);
});

it('deletes a file when time is right', function () {
    Config::set('chaos.enabled', true);
    Config::set('chaos.disk', 'local');
    
    // Create a temp directory and file
    $tempDir = __DIR__ . '/temp';
    if (!File::exists($tempDir)) {
        File::makeDirectory($tempDir);
    }
    $file = $tempDir . '/test.txt';
    File::put($file, 'content');
    
    Config::set('chaos.paths', [$tempDir]);
    
    Cache::shouldReceive('get')->with('chaos.next_run')->andReturn(now()->subHour());
    Cache::shouldReceive('put')->once(); // For scheduling next run

    artisan('chaos:process')
        ->expectsOutput('Chaos unleashed: Deleted file ' . $file)
        ->assertExitCode(0);

    expect(File::exists($file))->toBeFalse();
    
    // Cleanup
    File::deleteDirectory($tempDir);
});
