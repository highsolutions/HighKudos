<?php

namespace App\Notifications;

use App\Services\Slack\SlashCommandFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class CountNotification extends Notification
{
    use Queueable;

    public $numbers;

    public function __construct($numbers)
    {
        $this->numbers = $numbers;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $message = (new SlackMessage)
                ->to(config('kudos.kudos_channel'))
                ->content('*Podsumowanie tygodniowe :muscle: :hammer_and_wrench:*');

        $message->attachment(function ($attachment) {
            $attachment
                ->fields([
                    'Ten tydzień' => $this->numbers[0],
                    'Ten kwartał' => $this->numbers[1],
                    'Najwięcej improve\'ów' => $this->numbers[2][0] .' ('. $this->numbers[2][1] .')',
                    'Realizacja planu' => number_format($this->numbers[1] / 276 * 100, 0) .'%',
                ])
                ->markdown(['pretext', 'text', 'fields']);
        });

        return $message;
    }
}
