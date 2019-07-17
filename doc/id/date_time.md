# Handle Object `\DateTime`

## Pengantar

Object `\DateTime` biasanya digunakan untuk menandai tanggal atau waktu pada Doctrine. 
 Pada Doctrine terdapat beberapa tipe field yang menggunakan object `\DateTime` antara lain `date`, `datetime` dan `time`.

Secara default, Semart Skeleton tidak mengetahui apakah request yang dikirimkan berupa `string` tanggal atau bukan, sehingga Developer perlu menanganinya secara khusus.
 
## Handle Object `\DateTime`

Untuk menghandle object `\DateTime` pada Semart Skeleton sangatlah mudah, Developer cukup membuat [event subscriber](https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber) untuk [event](../../doc/id/event.md) `app.request` sebagai berikut:

```php
<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Request\RequestEvent;
use KejawenLab\Semart\Skeleton\Entity\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DateTimeSubscriber implements EventSubscriberInterface
{
    public function filterRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $object = $event->getObject();
        if (!$object instanceof Event) {
            return;
        }

        $object->setEventDate(\DateTime::createFromFormat('d-m-Y', $request->request->get('eventDate', '')));

        $request->request->remove('eventDate');
    }

    public static function getSubscribedEvents()
    {
        return [
            Application::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}

``` 

Cukup seperti di atas maka field `eventDate` dapat diubah menjadi object `\DateTime` dan kemudian dihapus dari object `Request` agar [Request Handler](../../src/Request/RequestHandler.php) tidak lagi meng-handle field `eventDate` tersebut.

Cara di atas dapat dipakai untuk kasus yang sama yang memerlukan manipulasi request.

[Kembali Ke Index](README.md)
