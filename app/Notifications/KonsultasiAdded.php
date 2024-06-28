<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KonsultasiAdded extends Notification
{
    use Queueable;

    protected $konsultasi;

    public function __construct($konsultasi)
    {
        $this->konsultasi = $konsultasi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'remaja_id' => $this->konsultasi->remaja_id,
            'remaja_nama' => $this->konsultasi->remaja->user->nama,
            'perihal' => $this->konsultasi->perihal,
            'deskripsi' => $this->konsultasi->deskripsi,
        ];
    }
}

