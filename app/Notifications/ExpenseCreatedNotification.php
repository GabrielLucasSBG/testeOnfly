<?php

namespace App\Notifications;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $expense;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $dateString = $this->expense->date;
        $date = Carbon::createFromFormat('Y-m-d', $dateString);
        $formattedDate = $date->format('d/m/Y');

        return (new MailMessage)
            ->subject("Despesa cadastrada")
            ->line('Uma nova despesa foi criada no sistema. Aqui estão os detalhes:')
            ->line('ID: ' . $this->expense->id)
            ->line('Data: ' . $formattedDate)
            ->line('Descrição: ' . $this->expense->description)
            ->line('Valor: R$ ' . number_format($this->expense->value, 2, ',', '.'))
            ->line('Obrigado por usar nossa api!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
