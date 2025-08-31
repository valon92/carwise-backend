<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, string $oldStatus, string $newStatus)
    {
        $this->report = $report;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->getStatusText($this->newStatus);
        
        return (new MailMessage)
            ->subject('Statusi i raportit u ndryshua')
            ->greeting('Përshëndetje ' . $notifiable->name . '!')
            ->line('Statusi i raportit "' . $this->report->title . '" u ndryshua nga ' . $this->getStatusText($this->oldStatus) . ' në ' . $statusText . '.')
            ->action('Shiko Raportin', route('reports.show', $this->report))
            ->line('Faleminderit për përdorimin e CarWise AI!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'report_title' => $this->report->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_text' => $this->getStatusText($this->oldStatus),
            'new_status_text' => $this->getStatusText($this->newStatus),
            'vehicle_name' => $this->report->vehicle ? $this->report->vehicle->brand . ' ' . $this->report->vehicle->model : 'N/A',
            'message' => 'Statusi i raportit "' . $this->report->title . '" u ndryshua nga ' . $this->getStatusText($this->oldStatus) . ' në ' . $this->getStatusText($this->newStatus) . '.',
            'type' => 'report_status_changed',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'report_id' => $this->report->id,
            'report_title' => $this->report->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_text' => $this->getStatusText($this->oldStatus),
            'new_status_text' => $this->getStatusText($this->newStatus),
            'vehicle_name' => $this->report->vehicle ? $this->report->vehicle->brand . ' ' . $this->report->vehicle->model : 'N/A',
            'message' => 'Statusi i raportit "' . $this->report->title . '" u ndryshua nga ' . $this->getStatusText($this->oldStatus) . ' në ' . $this->getStatusText($this->newStatus) . '.',
            'type' => 'report_status_changed',
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get status text in Albanian.
     */
    private function getStatusText(string $status): string
    {
        return match($status) {
            'pending' => 'Në pritje',
            'in_progress' => 'Në progres',
            'completed' => 'Përfunduar',
            'cancelled' => 'Anuluar',
            default => 'E panjohur'
        };
    }
}
