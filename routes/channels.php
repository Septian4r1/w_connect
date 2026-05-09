<?php

Broadcast::channel('App.Models.Warga.Rumah.{rumahId}', function ($user, $rumahId) {
    // Di sini $user sebenarnya bisa null atau instance dummy,
    // yang penting $rumahId dari session dicek
    return session('rumah_id') == $rumahId;
});
