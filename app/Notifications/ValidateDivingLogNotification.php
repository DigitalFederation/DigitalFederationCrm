<?php

namespace App\Notifications;

use Domain\DivingLogs\Models\DivingLog;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class ValidateDivingLogNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected DivingLog|Model $divingLog)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'diving_log' => $this->divingLog,
            'individual_id' => $this->divingLog->individual,
            'message' => __('notifications.diving_log_validated.database'),
            'url' => route('individual.diving-log.show', $this->divingLog->id),
        ];
    }
}
