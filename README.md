dynamic-dns-update-cdmon
========================

Simple script for dynamic dns update for CDMON service

More info:
https://support.cdmon.com/entries/24118056-API-de-actualización-de-IP-del-DNS-gratis-dinámico

Use:

php dynamicip.php

To use mail log
- run "composer install" ( http://getcomposer.org/ )
- uncomment " require 'vendor/autoload.php'; " "
- set " $mail_log = true; "
- and config smtp settings
