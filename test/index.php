<?php
namespace GitlabChangelog;

require __DIR__ . "/../src/GitlabChangelog.php";

$changelog = new GitlabChangelog();
$changelog->url = "http://gitlab.alibaba-inc.com/";
$changelog->repo = "ata/atatech-kb";
// $changelog->token = "YOUR PRIVATE TOKEN HERE";
$changelog->token = $argv[1];

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
