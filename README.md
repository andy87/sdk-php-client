
# SDK для создания API клиентов

## Description
SKD для создания клиентов отправляющих HTTP запросы к API.

## Установка

```bash
composer require andy87/sdk-php-client
```

## Usage

```php

class AvitoAccount extends \andy87\sdk\client\base\BaseAccount{

    public string $clientId;
    
    public string $clientSecret;
}

```

```php
class AvitoClient extends \andy87\sdk\client\SdkClient
{
    public function errorHandler() {
        
    }
}
```

#### Structure
```


```