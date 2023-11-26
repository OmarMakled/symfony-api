### goal 
Always strive for a balance between decoupling and simplicity based on your specific use case.

### console
bin/console make:controller
bin/console debug:router
bin/console debug:container | grep trans

### event

```
App\EventListener\PhotoUploadListener:
    tags:
        - { name: 'kernel.event_listener', event: 'App\EventListener\Event\PhotoUploadEvent', method: 'onPhotoUpload' }
```

```
class PhotoUploadEvent extends Event
{
    public function __construct(public readonly array $photos, public readonly User $user)
    {
    }
}
```

```
class PhotoUploadListener
{
    public function __construct(private readonly PhotoUploader $photoUploader, private readonly UserRepository $userRepository)
    {
    }

    public function onPhotoUpload(PhotoUploadEvent $event): void
    {
    }
}

EventDispatcherInterface $eventDispatcher

$eventDispatcher->dispatch(new PhotoUploadEvent(
    $photoDTO->photos,
    $this->getUser()
), PhotoUploadEvent::class);
```

- create admin that can display all users

sudo chown -R omar:omar src
sudo chown -R 777 src