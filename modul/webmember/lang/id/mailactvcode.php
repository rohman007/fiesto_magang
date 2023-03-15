<?php
$subject = 'Kode Pengaktifan Website';
$message = '
Selamat Datang!

Terima kasih atas kesediaannya mendaftarkan diri di '.$_SERVER['HTTP_HOST'].'.

Untuk mengaktifkan account anda, silakan kunjungi
'.$url_activate_user.' lalu masukkan kode '.$activationcode.'
di kotak yang tersedia.

Terima kasih!

==============
'.$config_site_judul.'
'.$_SERVER['HTTP_HOST'];

$subject_admin = 'Pendaftaran Anggota Baru';
$message_admin = '
Ada pendaftaran anggota baru.
Nama : '.$postnama.'.
Email : '.$postemail.'
username : '.$postuser.'

Terima kasih!

==============
';

?>
