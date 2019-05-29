<?php
require_once __DIR__ . '/../vendor/autoload.php';

use benancetin\Curly\Curly;

/**
 * Create an issue
 */
$curly = new benancetin\Curly\Curly();

$data = array(
    "project" => array("id" =>"0-0"),
    "summary" => "With curly everything is easy!",
    "description" => "Let'\''s create a new issue using Curly."
);
$link = "/api/issues";

// you may use $curly-setJson($json) if you are sending json instead of array
$curly->setArray($data);
$response = $curly->execute("post", $link);
echo $response."<br/>";
// or you may get status codes and warnings by $curly->getWarnings();
$curly->printWarnings();

/**
 * List issues
 */
$curly = new benancetin\Curly\Curly();

$link = "/api/issues?fields=\$type,id,summary";

$response = $curly->execute("get", $link);
echo $response."<br/>";
$curly->printWarnings();

/**
 * Update an issue
 */
$curly = new benancetin\Curly\Curly();

$data = array(
"summary" => "Curly is simple!",
"description" => "Yooo"
);

// we are updating the issue whose id is 2-4
$link = "/api/issues/2-4?fields=summary";

// NOTICE: we are using "post" here not "update"
$curly->setArray($data);
$response = $curly->execute("get", $link);
echo $response."<br/>";
$curly->printWarnings();

/**
 * Delete an issue
 */
$curly = new benancetin\Curly\Curly();
// let's delete the issue whose id is 2-5
$link = "/api/issues/2-5";
$response = $curly->execute("delete", $link);
echo $response."<br/>";
$curly->printWarnings();

/*
Sample $response = $curly->execute("update,$link); output

[{"summary":"Superb Curly lets you create issues!","id":"2-4","$type":"jetbrains.charisma.persistent.Issue"},{"summary":"Super duper Curly lets you create issues!","id":"2-3","$type":"jetbrains.charisma.persistent.Issue"},{"summary":"OMG Curly lets you create issues!","id":"2-2","$type":"jetbrains.charisma.persistent.Issue"}]

Sample $curly->printWarnings() Output:
($response = $curly->execute("update,$link);)

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
*/
