<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SqlCreateViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:create-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all the views needed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Cache::clear();
    }
}
