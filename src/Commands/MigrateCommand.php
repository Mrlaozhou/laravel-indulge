<?php

namespace Mrlaozhou\Indulge\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migrator;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indulge:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrating indulge database .';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('migrate', [
            '--path' => realpath(config('indulge.migrate.path')),
            '--realpath' => true
        ]);
    }
}