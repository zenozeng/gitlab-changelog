<?php
use ZenoZeng\GitlabChangelog\GitlabChangelog;

$changelog = new GitlabChangelog();
$changelog->url = "http://gitlab.alibaba-inc.com/";
$changelog->repo = "ata/atatech-kb";
// $changelog->token = "YOUR PRIVATE TOKEN HERE";
$changelog->token = $argv[1];
$changelog->debug = true;

$markdown = $changelog->markdown();

file_put_contents("changelog.md", $markdown);
?>
