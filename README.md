# gitlab-changelog

A php script to generate changelog via gitlab api v3

## Usage

composer.json:

```json
{
    "require": {
        "zenozeng/gitlab-changelog": "0.1.0"
    }
}
```

index.php:

```php
<?php
require "vendor/autoload.php";

use GitlabChangelog\GitlabChangelog;

$changelog = new GitlabChangelog();
$changelog->url = "http://gitlab.alibaba-inc.com/";
$changelog->repo = "ata/atatech-kb";
$changelog->token = "YOUR PRIVATE TOKEN";

$changelog->milestoneFilter = function($milestone) {
    $ignore = array("todo", "long running task", "team", "next release");
    return !in_array($milestone->title, $ignore);
};
$changelog->getLabels = function($issue) {
    $label = "Fixed";
    $map = array(
        "bug" => "Fixed",
        "enhancement" => "Improved",
        "feature" => "Added"
    );
    foreach($map as $k => $v) {
        if(strripos(implode(',', $issue->labels), $k) !== FALSE) {
            $label = $v;
            break;
        }
    }
    return array($label);
};

$changelog->debug = true;

$markdown = $changelog->markdown();

file_put_contents("changelog.md", $markdown);
?>
```

## 关于 PSR-4

http://culttt.com/2014/05/07/create-psr-4-php-package/
