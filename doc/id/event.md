# Event System

## Pengantar

Event system digunakan untuk melakukan modifikasi/memanipulasi alur program tanpa harus merubah core program yaitu dengan menggunakan event listener atau event subscriber. 

## Daftar Event

Pada Semart Skeleton terdapat 4 event yang dapat digunakan untuk memanipulasi alur program sebagai berikut:

- `app.request`: Event ini dipicu sesaat setelah controller menerima request dari client jika Anda menggunakan [Request Handler](../src/Request/RequestHandler.php)  

- `app.pre_validation`: Event ini dipicu sesaat sebelum validasi dijalankan jika Anda menggunakan [Request Handler](../src/Request/RequestHandler.php)

- `app.pagination`: Event ini dipicu sebelum query untuk pagination dijalankan sehingga Anda dapat memanipulasi query yang akan dijalankan melalui [Query Builder](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html) 

- `app.pre_commit`: Event ini dipicu sesaat sebelum data disimpan di database dan dipicu jika Anda menggunakan method `commit()` atau `remove()` pada controller

Disamping itu juga terdapat event lain yang berasal dari Framework Symfony yang dapat dibaca pada [link berikut](https://symfony.com/doc/current/reference/events.html).


[Kembali Ke Index](README.md)
