<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseRejectedNotification extends Notification
{
    use Queueable;

    protected $course;

    /**
     * Create a new notification instance.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
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
        $editUrl = url('/instructor/courses/' . $this->course->id . '/edit');

        $message = (new MailMessage)
            ->subject('Course Review Update: ' . $this->course->title)
            ->line('We regret to inform you that your course "' . $this->course->title . '" has been reviewed and was not approved at this time.');

        if ($this->course->approval_reason) {
            $message->line('Reason: ' . $this->course->approval_reason);
        }

        $message->line('You can review your course and make necessary changes, then resubmit for approval.')
            ->action('Edit Course', $editUrl)
            ->line('Thank you for your understanding.');

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
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'approval_reason' => $this->course->approval_reason,
            'message' => 'Your course has been rejected.',
        ];
    }
}
