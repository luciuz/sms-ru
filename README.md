# luciuz/sms-ru

Sending sms via sms.ru

### Examples
Init
```php
$smsRu = new SmsRu('API-ID-TOKEN');
```

Sending single sms
```php
// with From
$result = $smsRu->setFrom('MYCOMPANY')
    ->send(new Sms('+79123456789', 'Some text'));
    
// without From
$result = $smsRu->send(new Sms('+79123456789', 'Some text'));
```

Sending multiple sms
```php
$smsBundle = [
    new Sms('+79123456789', 'Some text'),
    new Sms('+79123456790', 'Some message'),
    new Sms('+79123456791', 'Some notification')
];
```

```php
// with From
$result = $smsRu->setFrom('MYCOMPANY')
    ->sendMulti($smsBundle);
    
// without From
$result = $smsRu->sendMulti($smsBundle);
```
