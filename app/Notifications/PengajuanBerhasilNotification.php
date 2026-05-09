<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

/**
 * Notification untuk pengajuan berhasil.
 *
 * Fitur:
 * 1. Bisa dikirim ke database dan broadcast (realtime).
 * 2. Data dikirim sebagai array fleksibel.
 * 3. Bisa digunakan untuk Rumah atau Warga (notifiable).
 */
class PengajuanBerhasilNotification extends Notification
{
    use Queueable;

    /**
     * Data notification
     * Contoh:
     * [
     *   'no_pengajuan' => 'PGJ001',
     *   'perihal'      => 'Perubahan Nama',
     *   'message'      => 'Pengajuan Anda berhasil'
     * ]
     */
    protected array $data;

    /**
     * Constructor
     *
     * @param array $data Data notifikasi (no_pengajuan, perihal, message, dll.)
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        // Pastikan ada message default jika tidak dikirim
        if (!isset($this->data['message'])) {
            $no = $this->data['no_pengajuan'] ?? '-';
            $perihal = $this->data['perihal'] ?? '-';
            $nama = $this->data['nama'] ?? 'Warga';

            $this->data['message'] =
                "Pengajuan perubahan {$perihal} atas nama {$nama} berhasil dikirim dengan nomor {$no}.";
        }
    }

    /**
     * Tentukan channel notifikasi: database + broadcast
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Simpan notifikasi ke tabel database
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return $this->data;
    }

    /**
     * Kirim notifikasi realtime ke broadcast channel
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->data);
    }
}
