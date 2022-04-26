<?php

namespace Modules\FileLinkModule\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\FileLinkModule\Entities\FileLinkFile;

class FixPublicLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature   = 'file_link_module:fix-public-link';
    protected $description = 'Update all files uploaded to the File Links to be publicly accessable';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running Fix Public Link job');
        Log::info('Running Fix Public Link Job');

        $linkFiles = FileLinkFile::with('FileUploads')->get();

        foreach($linkFiles as $file)
        {
            $upload = $file->FileUploads;
            $upload->public = true;
            $upload->save();

            Log::debug('Modified File ID '.$upload->file_id);
        }

        $this->info('Job Completed');
        Log::info('Fix Public Link Job completed');
    }
}
