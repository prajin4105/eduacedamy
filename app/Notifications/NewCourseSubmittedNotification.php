<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCourseSubmittedNotification extends Notification
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
        $adminUrl = url('/admin/courses');
        $editUrl = url('/admin/courses/' . $this->course->id . '/edit');

        return (new MailMessage)
            ->subject('New Course Submitted for Approval')
            ->line('A new course has been submitted for approval.')
            ->line("Course: {$this->course->title}")
            ->line("Instructor: {$this->course->instructor->name} ({$this->course->instructor->email})")
            ->action('Review Course', $editUrl)
            ->line('You can review all courses in the admin panel.')
            ->action('View All Courses', $adminUrl);
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
            'instructor_name' => $this->course->instructor->name,
            'instructor_email' => $this->course->instructor->email,
        ];
    }
}
