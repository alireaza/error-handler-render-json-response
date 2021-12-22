# Error Handler Render Json Response


## Install

Via Composer
```bash
$ composer require alireaza/error-handler-render-json-response
```


## Usage

```php
use AliReaza\ErrorHandler\ErrorHandler;
use AliReaza\ErrorHandler\Render\JsonResponse as RenderErrorHandler;

$error_handler = new ErrorHandler();
$error_handler->register(true, true);
$error_handler->setRender(RenderErrorHandler::class);
```


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.