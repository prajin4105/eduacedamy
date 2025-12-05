<?php

namespace App\Notifications;

use App\Models\InstructorApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInstructorApplication extends Notification
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
        $user = $this->application->user;
        $adminUrl = url('/admin/instructor-applications');
        $showUrl = url('/admin/instructor-applications/' . $this->application->id);

        return (new MailMessage)
            ->subject('New Instructor Application Received')
            ->line('A new instructor application has been submitted.')
            ->line("Applicant: {$user->name} ({$user->email})")
            ->line("Applied at: {$this->application->applied_at->format('F j, Y g:i A')}")
            ->action('View Application', $showUrl)
            ->line('You can review all applications in the admin panel.')
            ->action('View All Applications', $adminUrl);
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
            'user_name' => $this->application->user->name,
            'user_email' => $this->application->user->email,
        ];
    }
}
