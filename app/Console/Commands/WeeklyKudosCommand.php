<?php

namespace App\Console\Commands;

use App\Models\Kudos;
use App\Models\User;
use App\Notifications\CountNotification;
use App\Notifications\KudosNotification;
use App\Notifications\SummaryNotification;
use Illuminate\Console\Command;

class WeeklyKudosCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'improv:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send amount of how many improvs we have already';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $halloffame = [];
        User::get()->each(function ($user) use (&$halloffame) {
            $given = $user->kudosGiven()->thisWeek()->get();
            $halloffame[$user->formatForSlack()] = $given->count();

            if($given->count() > 0) {
            	$user->notify(new SummaryNotification($given));
            	$this->line('Message was sent to: @'. $user->username);
            } else {
            	$this->line('Message was not sent to: @'. $user->username .' because of zero improvs.');
            }
        });

        $allWeek = Kudos::thisWeek()->count();
        $allQuarter = Kudos::thisWeek()->count();

        $record = collect($halloffame)
            ->sort()
            ->reverse()
            ->take(1)
            ->mapWithKeys(function ($value, $key) {
                return [$key, $value];
            })->all();

        User::first()->notify(new CountNotification([$allWeek, $allQuarter, $record]));

        $this->line('Finished sending weekly summary of improvs.');
    }

}
