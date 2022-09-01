<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyCreatedNotification extends Notification
{
    use Queueable;

    public Company $company;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name = $notifiable->name ?: 'Sir/Ma';
        return (new MailMessage)
            ->line('Dear '. $name)
            ->line('Your company profile has been created successfully')
            ->line('Company Name: '. $this->company->name ?: 'N/A')
            ->line('Company Email: '. $this->company->email ?: 'N/A')
            ->line('Company website: '. $this->company->website ?: 'N/A')
            ->line('Kindly contact the admin for your login details');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
