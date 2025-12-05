<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseApprovedNotification extends Notification
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
        $courseUrl = url('/courses/' . $this->course->slug);
        $instructorUrl = url('/instructor/courses');

        return (new MailMessage)
            ->subject('Course Approved: ' . $this->course->title)
            ->greeting('Congratulations!')
            ->line('Your course "' . $this->course->title . '" has been approved by the admin.')
            ->line('The course is now published and visible to students.')
            ->action('View Course', $courseUrl)
            ->action('Manage Courses', $instructorUrl)
            ->line('Thank you for contributing to EduAcademy!');
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
            'message' => 'Your course has been approved.',
        ];
    }
}
