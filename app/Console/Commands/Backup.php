<?php

namespace App\Console\Commands;

use App\Http\Controllers\BackupController;
use Illuminate\Console\Command;

class Backup extends Command
{
    protected $signature = 'backup:all {id}';
    protected $description = '';

    /**
     * ScheduleList constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $backup = new BackupController();
        $backup->autoBackup($this->argument('id'));
    }

    /**
     * If it's an artisan command, strip off the PHP
     *
     * @param $command
     * @return string
     */
    protected static function fixupCommand($command)
    {
        $parts = explode(' ', $command);
        if (count($parts) > 2 && $parts[1] === "'artisan'") {
            array_shift($parts);
        }

        return implode(' ', $parts);
    }
}