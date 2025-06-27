# SDK Client Core

Универсальное PHP SDK-ядро для создания API-клиентов, с расширяемой архитектурой, поддержкой авторизации, моков и валидации.
Сделано для использования как основа под конкретные клиенты для работы с API.

---

## ⚙️ Установка

```bash
composer require andy87/sdk-php-client
```

Требования:
> ⚠️ Библиотека ориентирована на PHP 8.0+  
- ext-curl  
- ext-http  

---

## 📦 Структура

### Основной клиент
- **`SdkClient.php`** — базовый класс клиента, объединяющий модули, авторизацию и отправку запросов.
  Используется как родительский класс при реализации конкретного клиента под API партнёра.
---

### Базовые компоненты
- `base/components/Config.php` — конфигурация клиента
- `base/components/Account.php` — учётные данные
- `base/components/Prompt.php` — описание API-запроса
- `base/components/Schema.php` — описание схемы ответа
- `base/components/MockManager.php` — мок-ответы и заглушки

### Интерфейсы
- `AuthorizationInterface`
- `RequestInterface`
- `ResponseInterface`
- `ClientInterface`
- `MockInterface`

### Модули
- `base/modules/*` — абстрактные модули: логгер, транспорт, кэш, мок, тест

### Транспорт
- `transports/CurlTransport.php` — базовый HTTP-клиент на базе cURL

### Утилиты `helpers/`
- `ContentType.php`
- `MethodRegistry.php`
- `Protocol.php`

### Core-ядро
- `core/Modules.php` — менеджер модулей
- `core/Container.php` — DI контейнер
- `core/ClassRegistry.php` — подмена классов
- `core/transport/*` — данные связанные с запросами `Query`, `Request`, `Response`, `Url`

---

## 🚀 Быстрый старт

### Клиент
```php

class CustomClient extends SdkClient
{
    // Для создания клиента достаточно реализовать методы с логикой под конкретного партнёра.
    
    public function authorization( Account $account, bool $isGetFromCache = true ): bool {
        // Реализация авторизации
    }
    
    public function isTokenInvalid( Response $response ): bool {
        // Проверка отсутствия в ответеинформации о невалидном токене
    }
    
    public function reAuthorization( Account $account ): bool {
        // Реализация повторной авторизации если метод `isTokenInvalid` вернул true
    }
}
```

### Конфигурация
```php

use andy87\sdk\client\base\interfaces\ClientInterface;
use app\components\cusom\modules\CacheModule;
use app\components\cusom\prompts\CustomPrompt;
use app\components\cusom\mock\CustomPromptMock;

class CustomConfig extends Config
{
    // Базовые, обязательные, параметры клиента
    protected string $protocol = Protocol::HTTPS; // Протокол ( по умолчанию `https` )
    protected string $host; // Хост API, например `api.example.com`

    // Расширенные, необязательные, параметры
    protected ?string $prefix = null; // Префикс URL, например `/api/v1`
    // на выхде для Prompt::$path = 'example` будет `https://api.example.com/api/v1/example`'

    protected array $headers = []; // Дополнительные заголовки для всех запросов

    // Переопределения используемых классов
    protected array $registryOverrides = [
        ClientInterface::CACHE => CacheModule::class, // Переопределение модуля кэша
    ];

    // Мок-ответы для тестирования ( список моков )
    protected array $mockList = [
        CustomPrompt::class => CustomPromptMock::class
    ];
    
    // можно по необходимым условиям добавить дополнительные Mock-ответы
    // используя метод `updateMockList( MockInterface[] )`

}
```


### Prompt
Prompt объекты, описывают API-запросы и их параметры.
```php
class CustomPrompt extends Prompt
{
    // Базовые, обязательные, параметры запроса.
    protected string $method = Method::GET; // Метод HTTP запроса ( по умолчанию `GET` )
    
    protected string $path = '/api/v1/resource'; // Путь к ресурсу API ( будет добавлен в конец `Config::$protocol`://`Config::$host`/`Config::$prefix`/{path} )

    protected string $schema; // Схема ответа, например `CustomSchema::class` ( будет использоваться для валидации ответа и получения данных )



    // Дополнительные, не обязательные параметры
    protected array $headers = []; // Дополнительные заголовки для запроса ( будут добавлены к `Config::$headers` )

    protected ?string $contentType = СontentType::JSON; // Значение устанавливаемое в заголовок 'Content-Type' ( по умолчанию `null` )

    protected ?string $mock = SomeMock::class; // Если указан класс мок-ответа, он будет использоваться вместо реального запроса ( по умолчанию `null` )



    // Управление логикой     
    public const USE_PREFIX = false; // Отключение использования префикса из конфига ( по умолчанию `true` )

    public const APPLY_QUERY_TO_URL = true; // При `true` данные из query string будут добавлены в URL ( по умолчанию `false` )

    public const DEBUG = true; // Статус использования дебаг режима ( по умолчанию `false` )

    public const AUTH = []; // Массив классов, реализующих интерфейс `AuthorizationInterface`, которые будут применены для добавления данных авторизации.
}
```

### Схема
Схемы описывают структуру ответа API и используются для валидации данных.
Схема может быть многоуровневой, и может включать в себя другие схемы.
```php

class CustomSchema extends Schema
{
    // Карта ассоциаций свойств с их схемами
    public const MAPPING = [ 
        'coordinates' => Coordinates::class, //Схема для поля `coordinates`, которая должна быть реализована в классе `Coordinates`
        'phones' => [Phones::class], // Массив схем для поля `phones`, каждый элемент массива будет реализована как объект класса `Phones`
    ];

    public string $id; // Поле без схемы, значение получается напрямую из ответа API

    public bool $required; // Поле без схемы, значение получается напрямую из ответа API

    /** @var array|sting[]  */
    public array $ids = []; // Поле без схемы, значение получается напрямую из ответа API
    
    
    // Свойства со схемами
    public Coordinates $coordinates; // Схема для поля `coordinates`, которая должна быть реализована в классе `Coordinates`

    /** @var array|Phones[]  */
    public array $phones = []; // Массив схем для поля `phones`, каждый элемент массива будет реализована как объект класса `Phones`

}
```

---

## 🧩 Расширяемость

- Все модули, компоненты и схемы подменяемы через `ClassRegistry` или DI.
- Логика транспорта, кэша, логирования и мока абстрагирована в интерфейсы и базовые классы.

---

## ✅ Тесты

Смотри `tests/SdkClientTest.php` как пример.

---

## 📄 Лицензия

MIT

---

## 👤 Автор

[and_y87](https://github.com/andy87)