<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class CourseContentChanged extends Notification
{
    use Queueable;

    protected $courseTitle;

    public function __construct($courseTitle)
    {
        $this->courseTitle = $courseTitle;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' too if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Course Updated',
            'message' => "Your course '{$this->courseTitle}' has been updated. Please review your test questions.",
            'url' => url("/instructor/tests"), // Change if needed
        ];
    }
}
