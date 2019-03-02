<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link\nfrom "public_html/uploads" to "storage/app/public"\nfrom "public_html/images" to "storage/app/images"\nfrom "public_html/admin" to "storage/app/admin"';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->link(storage_path('app/fonts'), public_path('fonts'));
        $this->link(storage_path('app/images'), public_path('images'));
        $this->link(storage_path('app/public'), public_path('uploads'));
        $this->link(public_path(), base_path('public_html'));
    }

    protected function link($from, $to)
    {
        if (file_exists($to)) {
            return $this->error('The "'. $to .'" directory already exists.');
        }

        $this->laravel->make('files')->link(
            $from, $to
        );

        $this->info('The "'. $to .'" directory has been linked.');

    }
}
