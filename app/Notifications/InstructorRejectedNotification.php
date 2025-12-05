<?php

namespace App\Notifications;

use App\Models\InstructorApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorRejectedNotification extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(InstructorApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reapplyUrl = url('/become-instructor');

        $message = (new MailMessage)
            ->subject('Instructor Application Status Update')
            ->line('We regret to inform you that your instructor application has been reviewed and was not approved at this time.');

        if ($this->application->rejection_reason) {
            $message->line('Reason: ' . $this->application->rejection_reason);
        }

        $message->line('You can review your application and submit a new one if you wish.')
            ->action('Reapply', $reapplyUrl)
            ->line('Thank you for your interest in becoming an instructor with EduAcademy.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'rejection_reason' => $this->application->rejection_reason,
        ];
    }
}
