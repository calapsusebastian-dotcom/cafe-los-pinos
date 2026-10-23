<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Signature('backup:database {--keep=14 : Number of days to keep old backups}')]
#[Description('Copy the SQLite database into storage/app/private/backups and prune old copies')]
class BackupDatabase extends Command
{
    public function handle(): int
    {
        if (DB::getDriverName() !== 'sqlite') {
            $this->error('Solo se pueden respaldar bases de datos SQLite con este comando.');

            return self::FAILURE;
        }

        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');

        $path = $disk->path('backups/database-'.now()->format('Y-m-d_His').'.sqlite');

        DB::statement('VACUUM INTO ?', [$path]);

        $this->info("Copia creada: {$path}");

        $this->pruneOldBackups($disk, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    private function pruneOldBackups(Filesystem $disk, int $days): void
    {
        $limit = now()->subDays($days)->getTimestamp();

        foreach ($disk->files('backups') as $file) {
            if ($disk->lastModified($file) < $limit) {
                $disk->delete($file);
            }
        }
    }
}
