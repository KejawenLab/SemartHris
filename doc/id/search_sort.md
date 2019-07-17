# Pencarian dan Sorting

## Annotation `@Searchable`

Untuk melakukan pencarian sangatlah mudah, Anda cukup menggunakan annotation `@Searchable` sebagai berikut:

```php
@Searchable({"field1", "field2"})
```

Dengan `field1` dan `field2` adalah nama property pada entity yang diberi annotation `@Searchable` tersebut.


Selanjutnya, untuk mengetahui bahwa sebuah request berisi **query string**, Semart Skeleton mendeteksi melalui URL yang dikirim yaitu dengan menyertakan GET parameter `q` para URLnya.


Sebagai contoh, Anda mengirimkan request URL `http://localhost:8000/admin/groups/?q=admin`, maka Semart Skeleton akan memperfilter secara **case insensitive** menggunakan `LIKE = '%admin%'` pada field yang ada pada `@Searchable`. 

## Annotation `@Sortable`

Untuk melakukan pencarian sangatlah mudah, Anda cukup menggunakan annotation `@Sortable` sebagai berikut:

```php
@Sortable({"field1", "field2"})
```

Dengan `field1` dan `field2` adalah nama property pada entity yang diberi annotation `@Sortable` tersebut.

Untuk melihat contoh dari penggunaan `@Searchable` dan `@Sortable`, Anda dapat melihatnya pada entity [Group](../src/Entity/Group.php)


Selanjutnya, untuk mengetahui bahwa sebuah request berisi **query string**, Semart Skeleton mendeteksi melalui URL yang dikirim yaitu dengan menyertakan GET parameter `s` yang berisi field yang akan disort dan `d` untuk direksi sort-nya para URLnya.


Sebagai contoh, Anda mengirimkan request URL `http://localhost:8000/admin/groups/?s=name&d=a`, maka Semart Skeleton akan mengurutkan berdasarkan field `name` secara `asc`. Anda juga dapat mengganti `a` dengan `d` untuk mengurutkan secara `desc`.


## Pencarian dan Pengurutan pada Relasi

Semart Skeleton support relasi untuk pencarian maupun pengurutan, sebagai contoh Anda punya field `parent` yang berelasi dengan dirinya sendiri (self reference) maka annotation-nya adalah sebagai berikut:

```php
@Sortable({"parent.name", "code", "name"})
```

Seperti pada entity [Menu](../../src/Entity/Menu.php)


## Implementasi Pengurutan pada Template

Untuk menggunakan fitur pengurutan pada template, Anda cukup menambahkan `<i data-sort="no" data-sort-field="username" class="fa fa-sort sortable pull-right"></i>` pada header table seperti pada file `index.html.twig` yang di-generate oleh Semart Skeleton.


[Kembali Ke Index](README.md)
