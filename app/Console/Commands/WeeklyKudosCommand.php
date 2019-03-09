<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SummaryNotification;
use Illuminate\Console\Command;

class WeeklyKudosCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'kudos:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send to all users kudos from whole week';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        User::get()->each(function ($user) {
            $kudos = $user->kudosReceived()->thisWeek()->get();
            $user->notify(new SummaryNotification($kudos));
            $this->line('Message was sent to: @'. $user->username);
        });

        $this->line('Finished sending weekly summary of kudos.');
    }

}
