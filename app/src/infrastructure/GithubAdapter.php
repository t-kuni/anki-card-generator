<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


class GithubAdapter
{
    public function test() {
        $client = new \Github\Client();
        $issues = $client->api('issue')->all('KnpLabs', 'php-github-api', array('state' => 'open'));
        $issue = $client->api('issue')->show('KnpLabs', 'php-github-api', $issues[0]['number']);
        var_dump($issue);
    }
}