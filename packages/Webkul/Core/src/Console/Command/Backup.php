<?php


namespace Webkul\Core\Console\Command;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a backup of the database and send it to Wassabi.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $str = storage_path();
        $temp_path = $str . '/app/public/temp';
        $exist = is_dir($temp_path);
        // create the temp directory if not exist
        if(!$exist){
            mkdir($temp_path, 0755, true);
        }
        // generate name for the dump file:
        $tz = env('APP_TIMEZONE', 'America/Chicago');
        $time = now($tz)->format('Y-m-d_H-i-s-v');
        $name = $time . '.sql.gz';
        $platform = "/app/.platformsh/bin/platform";

        // execute dump command:
        $command = $platform . " db:dump --gzip -f " .$temp_path . '/' . $name;
        $out = null;
        $code = null;
        exec($command, $out, $code);

        // store the dump:
        // create the db_backup directory if it doesn't exist
        $exist = Storage::disk('wassabi_private')->exists('db_backup');
        if(!$exist){
            Storage::disk('wassabi_private')->makeDirectory('db_backup');
        }
        $file = new File($temp_path . '/' . $name);
        Storage::disk('wassabi_private')->putFileAs('db_backup', $file, $name);

        // delete the temp file
        unlink($temp_path . '/' . $name);
    }
}