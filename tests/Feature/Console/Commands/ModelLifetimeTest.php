<?php

namespace Paulund\EloquentLifetime\Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Workbench\App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Config::set('eloquent-lifetime', [
        'models' => [
            'folder' => 'workbench/app/Models',
        ],
    ]);
});

it('can run the model lifetime command', function (): void {
    $this->artisan('model:lifetime')
        ->expectsOutput('Model lifetime command ran successfully.')
        ->assertExitCode(0);
});

it('will delete records after the lifetime', function (): void {
    User::factory()->create([
        'created_at' => now()->subDays(31),
    ]);

    $this->artisan('model:lifetime')
        ->expectsOutput('Model lifetime command ran successfully.')
        ->assertExitCode(0);

    $this->assertEmpty(User::all());
});

it('will not delete records before the lifetime', function (): void {
    User::factory()->create([
        'created_at' => now()->subDays(29),
    ]);

    $this->artisan('model:lifetime')
        ->expectsOutput('Model lifetime command ran successfully.')
        ->assertExitCode(0);

    $this->assertNotEmpty(User::all());
});
