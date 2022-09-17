# phone-rounish
the simple php method to check phone number roundish amount

## example
```php
<?php

require_once __DIR__."/check_phone_roundish.php";

$numbers = [
    "9900153555",
    "9129151718",
    "9301233210",
    "9381238543",
    "9030001020",
    "9159165060",
                ];
foreach($numbers as $number) {
    echo $number . " : " . check_phone_roundish($number,3)['score'] . "\n";
}


```
