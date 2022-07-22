# EventLoader Module for ProcessWire CMS/CMF

`EventLoader` module allow you to load events from a event files.


## Requirements

* ProcessWire `3.0` or newer
* PHP `7.0` or newer

## Installation

Install the module from the [modules directory](https://modules.processwire.com/modules/eventloader/):

Via `Composer`:

```
composer require trk/processwire-event-loader
```

Via `git clone`:

```
cd your-processwire-project-folder/
cd site/modules/
git clone https://github.com/trk/EventLoader.git
```


- Loading events from `site/ready.php` file

```php
<?php

namespace ProcessWire;

// load event files starts with `ready.` prefix
EventLoader::load(__DIR__ . '/templates', 'ready.');

```

- Event file: `site/templates/configs/events/ready.hello-world.php`

```php
<?php

namespace ProcessWire;

return [
    'events' => [
        'Page::hello' => [
            'type' => 'method',
            'fn' => function (HookEvent $e) {
                $message = is_string($e->arguments(0)) ? $e->arguments(0) : '';
                $e->return = $message;
            }
        ]
    ]
];
```

- Usage

```php
<?php
echo $page->hello('World');
```