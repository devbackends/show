<?php

namespace Mega\Phonelogin\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldCodesCommand extends Command
{
    protected $signature = 'phonelogin:clear-codes';

    protected $description = 'This command clears the sent verification code from table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {
        DB::table('otp_logs')->where('created_at', '<=', Carbon::yesterday())->delete();
    }

}