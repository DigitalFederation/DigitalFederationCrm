<?php

namespace App\Notifications;

use Domain\DivingLogs\Models\DivingLog;
use Domain\Entities\Models\Entity;
use Domain\Individuals\Models\Individual;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestDivingLogValidationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected DivingLog $divingLog, protected Individual|Entity $validator) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $validatorType = $this->validator instanceof Individual ? 'Individual' : 'Entity';
        $validatorName = $this->validator instanceof Individual ? $this->validator->full_name : $this->validator->name;

        return (new MailMessage)
            ->subject(__('notifications.diving_log_validation_request.subject'))
            ->line(__('notifications.diving_log_validation_request.line_intro'))
            ->line(__('notifications.diving_log_validation_request.line_diver', ['name' => $this->divingLog->user->name]))
            ->line(__('notifications.diving_log_validation_request.line_date', ['date' => $this->divingLog->date->format('Y-m-d')]))
            ->line(__('notifications.diving_log_validation_request.line_location', ['location' => $this->divingLog->diving_location]))
            ->action(__('notifications.diving_log_validation_request.action'), route('individual.diving-log-validation.show', $this->divingLog->id))
            ->line(__('notifications.diving_log_validation_request.line_outro'));
    }

    public function toDatabase($notifiable): array
    {
        $validatorType = $this->validator instanceof Individual ? 'Individual' : 'Entity';
        $validatorName = $this->validator instanceof Individual ? $this->validator->full_name : $this->validator->name;

        return [
            'diving_log' => $this->divingLog,
            'validator' => $this->validator,
            'validator_type' => $validatorType,
            'validator_name' => $validatorName,
            'message' => __('notifications.diving_log_validation_request.database_short'),
            'url' => route('individual.diving-log-validation.show', $this->divingLog->id),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'diving_log_id' => $this->divingLog->id,
            'user_name' => $this->divingLog->user->name,
            'date' => $this->divingLog->date->format('Y-m-d'),
            'location' => $this->divingLog->diving_location,
            'title' => __('notifications.diving_log_validation_request.database_title'),
            'message' => __('notifications.diving_log_validation_request.database_message'),
            'url' => route('individual.diving-log-validation.show', $this->divingLog->id),
        ];
    }
}
