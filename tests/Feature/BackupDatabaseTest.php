<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupDatabaseTest extends TestCase
{
    public function test_it_copies_the_database_and_removes_old_copies(): void
    {
        $disk = Storage::disk('local');
        $disk->deleteDirectory('backups');
        $disk->put('backups/database-old.sqlite', 'old');
        touch($disk->path('backups/database-old.sqlite'), now()->subDays(30)->getTimestamp());

        $this->artisan('backup:database')->assertSuccessful();

        $files = $disk->files('backups');

        $this->assertCount(1, $files);
        $this->assertStringNotContainsString('old', $files[0]);

        $disk->deleteDirectory('backups');
    }
}
