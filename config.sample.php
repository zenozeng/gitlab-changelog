<?php
define("URL", "http://gitlab.example.com/");
define("TOKEN", "YOUR PRIVATE TOKEN");
define("REPO", "OWNER/REPONAME");
define("TARGET", "./CHANGELOG");
define("IGNORE", serialize(array(
    "todo",
    "long running task",
    "team",
    "next release"
)));
define("TAGS", serialize(array(
    "bug" => "Fixed",
    "enhancement" => "Improved",
    "feature" => "Added"
)));
define("DEFAULT_TAG", "Fixed");

?>
