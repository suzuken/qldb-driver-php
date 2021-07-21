# suzuken/qldb-driver-php

## Requirements

* PHP 7.4 or later

## Examples

```php
$qldbSession = new QLDBSessionClient([
    'region' => 'ap-northeast-1',
    'profile' => 'default',
    'version' => '2019-07-11',
]);
$driver = new QLDBDriver("test", $qldbSession);
$driver->execute(...);
```

## Acknowledgement

This implementation based on [Amazon QLDB Go Driver](https://github.com/awslabs/amazon-qldb-driver-go).