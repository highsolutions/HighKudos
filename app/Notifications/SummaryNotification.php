<?php

namespace App\Notifications;

use App\Services\Slack\SlashCommandFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SummaryNotification extends Notification
{
    use Queueable;

    public $kudos;

    public function __construct($kudos)
    {
        $this->kudos = $kudos;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $message = (new SlackMessage)
                ->success()
                ->to($notifiable->slack_id)
                ->content('*Oto Twoje podsumowanie karteczek za ostatni tydzień! :muscle: :parrot: :wink:*');

        $this->kudos->each(function ($kudo) use ($message) {
            $message->attachment(function ($attachment) use ($kudo) {
                $attachment
                    ->pretext(SlashCommandFormatter::prepare($kudo, 'summary'))
                    ->fields([
                        'Od' => $kudo->sender->formatForSlack(),
                        'Data' => $kudo->created_at->format('Y-m-d H:i'),
                    ])
                    ->markdown(['pretext', 'text', 'fields']);
            });
        });

        return $message;
    }
}
