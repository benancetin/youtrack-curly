# benancetin/youtrack-curly
youtrack-curly is simple, easy to use youtrack api that uses curl library. You may insert/delete/update/list fields in few seconds.

## Installing benancetin/youtrack-curly
```bash
composer require benancetin/youtrack-curly
```
Then you just fill values in "curly.php" file in "config" directory.
Note: You should already have installed "curl" library to use the api.

## Using benancetin/youtrack-curly
```bash
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use benancetin\Curly\Curly;

$curly = new benancetin\Curly\Curly();

$data = array(
    "project" => array("id" =>"0-0"),
    "summary" => "With curly everything is easy!",
    "description" => "Let'\''s create a new issue using Curly."
);
$link = "/api/issues";

// you may use $curly-setJson($json) if you are sending json instead of array
$curly->setArray($data);
$response = $curly->execute("post",$link);
echo $response."<br/>";
// or you may get status codes and warnings by $curly->getWarnings();
$curly->printWarnings(); 

```
For detailed usage please check "CurlyTest.php" in "tests" directory.

## Sample Output
Sample $response = $curly->execute("update,$link); output
```bash

[{"summary":"Superb Curly lets you create issues!","id":"2-4","$type":"jetbrains.charisma.persistent.Issue"},{"summary":"Super duper Curly lets you create issues!","id":"2-3","$type":"jetbrains.charisma.persistent.Issue"},{"summary":"OMG Curly lets you create issues!","id":"2-2","$type":"jetbrains.charisma.persistent.Issue"}]
```
Sample $curly->printWarnings() Output:
($response = $curly->execute("update,$link);)

```bash
Array
(
    [0] => Array
        (
            [xxx] => You requested -update- method, for update you need to use -post-
        )

    [1] => Array
        (
            [200] => OK
        )
)
```
