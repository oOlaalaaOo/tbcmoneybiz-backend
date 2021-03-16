<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\MembershipModule\Services\MembershipService;

class DailyMembershipInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:daily-membership-interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add daily membership interest';

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
     * @return int
     */
    public function handle()
    {
        \Log::info('ran DailyMembershipInterest command');

        MembershipService::addInterest();
    }
}
