dynamic-dns-update-cdmon
========================
Simple script for dynamic dns update for CDMON service. More info:
https://support.cdmon.com/entries/24118056-API-de-actualización-de-IP-del-DNS-gratis-dinámico

How to use:
========================
Configure your script options
```php
$user = 'yourUsername'; //username
$pass = 'yourPassword'; //password
$cip = false; // force new ip here or set to false to auto
$retry_time = 30; // time to retry, on seconds
$retry_attempts = 3; // number of attempts
```

run from cli or from  your webserver
```php
php dynamicip.php
```

To use mail log
========================
Run: composer ( http://getcomposer.org/ )
```php
php dynamicip.php
```

Uncomment require vendor autoload
```php
require 'vendor/autoload.php';
```

Set true $mail_log var
```php
$mail_log = true;
```

Configure your mail options
```php
$to  = 'to@example.com';
$subject = 'Dinamic dns status';
$from = 'you@example.com';
$mail_log_success = true;
$mail_log_fail = true;
```

Configure your smtp settings
```php
$mail_config['smtp'] = 'smtp.yourdomain.com';
$mail_config['port'] = 25;
$mail_config['username'] = 'smtpUser';
$mail_config['password'] = 'smtpPassword';
```

License
========================
Distributed under the MIT license: http://www.opensource.org/licenses/mit-license.php

Copyright (c) Diego Vilariño: http://www.ensegundoplano.com/ - http://www.sond3.com

