# Relasi Tabel Database

## Handle Relasi Tabel

Secara default, Semart Skeleton menggunakan [Request Handler](../../src/Request/RequestHandler.php) untuk meng-handle request dari client.
 Pada [Request Handler](../../src/Request/RequestHandler.php) secara default sudah disematkan fitur untuk meng-handle relasi antara tabel dalam dataase dengan syarat field yang berelasi harus sama dengan field yang dikirim dari request.

## Contoh

Pada entity [User](../../src/Entity/User.php) yang berelasi dengan entity [Group](../../src/Entity/Group.php) pada field `group` maka ketika kita akan merelasikan `User` dengan `Group`,
 request yang kita kirim harus memiliki field `group` dengan isi primary key dari `Group` seperti pada [template user index](../../templates/user/index.html.twig) pada bagian ajax save.
 
Semart Skeleton secara otomatis akan mencari pada `Group` apakah terdapat primary key atau id tersebut dan kemudian merelasikannya pada `User`.
 
Berbeda dengan Eloquent pada Laravel atau Query Builder pada CodeIgniter yang menggunakan field `x_id` untuk merelasikan antara satu tabel dengan tabel lainnya, pada Doctrine, 
 untuk merelasikan satu tabel dengan tabel lainnya menggunakan object entity sehingga menggunakan primary key atau id saja tidak cukup.
 Namun dengan menggunakan Semart Skeleton, Anda dapat merasakan sensasi yang sama, karena Semart Skeleton telah memiliki fitur untuk meng-handle itu. 


[Kembali Ke Index](README.md)
