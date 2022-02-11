<?php

namespace Modules\FileLinkModule\Notifications;

use App\Models\UserSetting;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Modules\FileLinkModule\Entities\FileLink;

class GuestFileNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $link;
    protected $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(FileLink $link, $name)
    {
        $this->link    = $link;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        //  Check if the user should get an email notification
        $email = UserSetting::whereUserId($notifiable->user_id)->whereHas('UserSettingType', function($q)
        {
            $q->where('name', 'Receive Email notifications');
        })->first()->value;

        if($email)
        {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('File Uploaded to File Link: '.$this->link->link_name)
                    ->greeting('Hello,')
                    ->line('A file has been uploaded to the File Link - '.$this->link->link_name.'. by '.$this->name)
                    ->action('Click to View File', route('FileLinkModule.show', $this->link->link_id));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => 'New file uploaded to File Link '.$this->link->link_name,
            'data'    => [
                'link' => $this->link->toArray(),
                'name' => $this->name,
            ],
        ];
    }
}
