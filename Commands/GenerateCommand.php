<?php

namespace Lysice\Region\Commands;

use Illuminate\Console\Command;

/**
 * @author lysice
 * Class GenerateCommand
 * @package Lysice\Commands
 */
class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'region:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate the region data to database';

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
        app('region')->region();
    }
}
