# User Context

## Pengantar

Dalam banyak kasus, aplikasi kita diharuskan dapat menampilkan data berdasarkan user yang sedang log in pada aplikasi kita atau biasa dikenal dengan nama **user context**. Semart Skeleton sebagai sebuah skeleton mengakomodir hal tersebut.

## Setting Controller

Tambahkan pada controller, pada annotation `@Permission()` tambahkan field `ownership=true`. Sebagai contoh, bila annotation `@Permission(menu="SETTING")` maka tinggal tambahkan jadi `@Permission(menu="SETTING", ownership=true)`

## Setting List

Untuk memfilter hasil query berdasarkan user context, Anda perlu memodifikasi query yang dihasilkan oleh Semart Skeleton. Anda dapat menggunakan event `app.pagination` dan membuat event subscriber.


Untuk cara membuat membuat event subscriber, Anda dapat membacanya pada [dokumentasi Symfony](https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber)

[Kembali Ke Index](README.md)
