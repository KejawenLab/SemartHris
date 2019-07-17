# Hak Akses

## Konsep Dasar

Konsep awal security pada **Semart Skeleton** adalah bagaimana agar user dapat mengatur sendiri hak aksesnya tanpa terikat dengan developer. Untuk itu,
kami sengaja tidak menggunakan fitur **Role Hierarchy** pada Symfony dan membuat sendiri konsep security menggunakan **User**, **Group**, **Menu** dan **Role** (bukan **Role** pada Symfony),
sehingga user nantinya dapat mengubah hak akses hanya dengan mencentang list **Role** pada **Menu** dari **Group** tertentu.

Konsep ini sangat umum menurut saya dan lebih mudah dipahami ketimbang harus menggunakan **Role Hierarchy**, **Access Control List** dan segala macam yang seringkali hanya dapat dipahami oleh developer.

## Mengatur Hak Akses

Untuk mengatur hak akses, dapat dilakukan melakui menu **Administrator > Group** kemudian mengeklik pilihan **Hak Akses** pada Group yang akan diubah sehingga akan tampak seperti gambar berikut:

![Role List](../assets/imgs/roles.png "Role List")

Anda cukup menekan pada bagian hak akses untuk mengatur apa saya yang dapat diakses oleh Group tersebut.

## Annotation `@Permission`

Untuk memudahkan developer, saya membuat custom annotation `@Permission` yang dapat digunakan pada class controller untuk mengatur hak akses dari User atau Group. Penggunaan annotation `@Permission` adalah sebagai berikut:

- Pada bagian atas dari definisi class controller, ditambahkan annotation `@Permission` dengan atribute `menu` sebagai berikut:

```php
@Permission(menu="KODEMENU")
```

Dengan ketentuan, `KODEMENU` adalah kode pada tabel menu.

- Pada bagian atas dari definisi method controller, ditambahkan annotation `@Permission` dengan atribute `actions` sebagai berikut:

```php
@Permission(actions=Permission::VIEW)

//Atau bisa juga menggunakan multiple hak akses sebagai berikut

@Permission(actions={Permission::ADD, Permission::EDIT})
```

Untuk melihat contoh penggunaannya, Anda membuka file [Group Controller](../../src/Controller/Admin/GroupController.php)

Sangat mudah sekali, bukan?

[Kembali Ke Index](README.md)
