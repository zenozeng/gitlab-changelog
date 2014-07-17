<?php
include 'config.php';

function get($arg) {
    $url = URL . 'api/v3/' . $arg;
    echo 'Fetching ' . $url . "\n";
    if(strripos($url, '?') !== FALSE) {
        $url .= '&';
    } else {
        $url .= '?';
    }
    $url .= 'private_token=' . TOKEN;
    return json_decode(file_get_contents($url));
}

$repo = array_pop(array_filter(get('projects'), function($repo) {
    return $repo->path_with_namespace === REPO;
}));

$page = 1;
$per_page = 100;
$issues = [];
while(true) {
    $next = get('projects/' . $repo->id . '/issues?page=' . $page . '&per_page=' . $per_page);
    $count = count($next);
    $issues = array_merge($issues, $next);
    $page++;
    if($count < $per_page) {
        break;
    }
}
$issues = array_reverse(array_filter($issues, function($issue) {
    return $issue->state === "closed" && isset($issue->milestone);
}));

$milestones = array_reverse(get('projects/'. $repo->id . '/milestones'));

$markdown = array_map(function($milestone) use ($issues, $milestones, $repo) {

    $milestone_issues = array_filter($issues, function($issue) use ($milestone) {
        return $issue->milestone->id == $milestone->id;
    });

    if(count($milestone_issues) === 0) return "";
    if(in_array($milestone->title, unserialize(IGNORE))) return "";

    $text = array_map(function($issue) use ($repo) {

        $status = DEFAULT_TAG;
        foreach(unserialize(TAGS) as $k => $v) {
            if(strripos(join($issue->labels, ","), $k) !== FALSE) {
                $status = $v;
                break;
            }
        }
        $str = "- `$status` [#$issue->id] ";
        $str .= "(" . URL . $repo->path_with_namespace . "/issues/" . $issue->id . ") ";
        $str .= $issue->title;
        return $str;
    }, $milestone_issues);

    $text = "## " . $milestone->title . " - _" . $milestone->created_at . "_\n" . join($text, "\n") . "\n\n";
    return $text;
}, $milestones);
$markdown = "# Changelog\n\n" . join($markdown, "");
file_put_contents(TARGET, $markdown);
echo 'file_put_contents: ' . TARGET;
?>