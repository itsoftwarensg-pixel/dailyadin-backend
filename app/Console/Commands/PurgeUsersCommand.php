<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;


class PurgeUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:purge
        {ids?* : One or more user IDs to delete}
        {--ids= : Comma-separated list of user IDs to delete}
        {--all : Delete every user in the users table}
        {--force : Permanently remove rows instead of soft-deleting them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Only';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $deleteAll = (bool) $this->option('all');
        $force = (bool) $this->option('force');

        $ids = collect($this->argument('ids'));

        if ($this->option('ids')) {
            $ids = $ids->merge(explode(',', $this->option('ids')));
        }

        $ids = $ids->map(fn ($id) => trim((string) $id))
            ->filter()
            ->unique()
            ->values();

        if (! $deleteAll && $ids->isEmpty()) {
            $this->error('Provide one or more user IDs, --ids=1,2,3, or pass --all.');

            return self::FAILURE;
        }

        $query = $deleteAll ? User::query() : User::query()->whereIn('id', $ids);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No matching users found. Nothing to delete.');

            return self::SUCCESS;
        }

        $label = $deleteAll ? 'ALL users' : "{$count} user(s) ({$ids->implode(', ')})";

        if (! $this->confirm('This will '.($force ? 'PERMANENTLY ' : '')."delete {$label}. Continue?")) {
            $this->info('Aborted.');

            return self::SUCCESS;
        }

        $force ? $query->forceDelete() : $query->delete();

        $this->info("Deleted {$count} user(s).");

        return self::SUCCESS;
    }
}
