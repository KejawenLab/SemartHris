# Handle Upload

## Konfigurasi

Untuk dapat menggunakan [`UploadHandler`](../../src/Upload/UploadHandler.php) terlebih dahulu kita harus memastikan konfigurasi `UPLOAD_DIR` pada menu `Setting` sudah terisi.
 Secara default, nilai `UPLOAD_DIR` adalah `uploads`.

## Menggunanakan [`FileUploadController`](../../src/Controller/Admin/FileUploadController.php)

Semart Skeleton telah menyediakan `FileUploadController` yang dapat digunakan untuk upload namun hanya untuk user yang telah login.
 Untuk menggunakan controller tersebut, berikut adalah caranya:
 
* Buat HTML seperti dibawah:
 
```twig
<input type="hidden" id="profileImage" value="">
<div class="avatar-upload">
    <div class="avatar-edit">
        <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" />
        <label for="imageUpload"></label>
    </div>
    <div class="avatar-preview">
        <div id="imagePreview" style="background-image: url({% if user.profileImage %}{{ path('get_files', {path: user.profileImage}) }}{% else %}{{ asset('img/logo.gif') }}{% endif %});">
        </div>
    </div>
</div>
``` 

* Handle DOM dengan menggunakan Javascript sebagai berikut:

```javascript
$(document).on('change', '#imageUpload', function (e) {
    let formData = new FormData();
    let file = $(this)[0].files[0];

    formData.append('profileImage', file);
    formData.append('_csrf_token', localStorage.getItem('csrf_token'));

    $.ajax({
        url: Routing.generate('files_upload'),
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            localStorage.setItem('csrf_token', response._csrf_token);

            $('#profileImage').val(response.files['profileImage']);
            $('#imagePreview').css('background-image', 'url(' + Routing.generate('get_files', {path: response.files['profileImage']}) + ')');
        }
    });
});
```

Semudah itu menggunakan `FileUploadController`.


## Menggunakan [`UploadHandler`](../../src/Upload/UploadHandler.php)

Anda dapat juga membuat upload handler sendiri menggunakan class [`UploadHandler`](../../src/Upload/UploadHandler.php). Anda dapat mencontoh 
 cara menghandle [`\DateTime` Object](date_time.md) untuk membuat subscriber untuk menghandle upload file.

```php
<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Request\RequestEvent;
use KejawenLab\Semart\Skeleton\Upload\UploadHandler;
use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UploadProfileSubscriber implements EventSubscriberInterface
{
    private $uploadHandler;
    
    public function __construct(UploadHandler $uploadHandler) 
    {
        $this->uploadHandler = $uploadHandler;
    }
    
    public function filterRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $object = $event->getObject();
        if (!$object instanceof User) {
            return;
        }
        
        if ($filUpload = $request->files->get('profileImage')) {
            $fileName = $this->uploadHandler->handle($filUpload);
            $object->setProfileImage($fileName);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            Application::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}

```

Bagaimana mudah sekali, bukan?

[Kembali Ke Index](README.md)
