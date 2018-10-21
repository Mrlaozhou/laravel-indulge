<?php

namespace Mrlaozhou\Indulge\Commands;

use Illuminate\Console\Command;

class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indulge:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback indulge database migration';

    public function handle ()
    {
        $this->call( 'migrate:rollback', [
            '--path'        =>  realpath( config('indulge.migrate.path') ),
            '--realpath'    =>  true,
        ] );
    }
}