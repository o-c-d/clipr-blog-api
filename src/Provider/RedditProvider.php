<?php

namespace App\Provider;

class RedditProvider 
{
    public function getPostDataFromUri(string $uri): array
    {
        $jsonUri = $this->makeJsonUri($uri);
        $jsonString = $this->downloadFile($jsonUri);
        $jsonData = $this->getJsonData($jsonString);
        return $this->extractPostData($jsonData);
    }

    private function makeJsonUri(string $uri): string
    {
        $uriParts = parse_url(urldecode($uri));
        $scheme   = isset($uriParts['scheme']) ? $uriParts['scheme'] . '://' : '';
        $host     = isset($uriParts['host']) ? $uriParts['host'] : '';
        $port     = isset($uriParts['port']) ? ':' . $uriParts['port'] : '';
        $path     = isset($uriParts['path']) ? $uriParts['path'] : '';
        $query    = isset($uriParts['query']) ? '?' . $uriParts['query'] : '';
        $fragment = isset($uriParts['fragment']) ? '#' . $uriParts['fragment'] : '';
        // $path    .= '';
        return "$scheme$host$port$path.json$query$fragment";

    }

    private function downloadFile(string $jsonUri): string
    {
        $jsonString = file_get_contents($jsonUri);
        return $jsonString;
    }

    private function getJsonData(string $jsonString): array
    {
        $jsonData = json_decode($jsonString, true);
        return $jsonData;
    }

    private function extractPostData(array $jsonData): array
    {
        $postData = [];
        if(isset($jsonData[0]['data']['children'][0]['data'])) {
            $data = $jsonData[0]['data']['children'][0]['data'];
            if(isset($data['title'])) {
                $postData['title'] = $data['title'];
            }
            if (isset($data['subreddit_name_prefixed'])) {
                $postData['description'] = $data['subreddit_name_prefixed'];
            }
            if(isset($data['selftext'])) {
                $postData['body'] = $data['selftext'];
            }
            if(isset($data['post_hint']) && isset($data['url_overridden_by_dest'])) {
                if("image"===$data['post_hint']) {
                    $postData['body'] = sprintf('<img src="%s" alt="%s" />', $data['url_overridden_by_dest'], $data['post_hint'].': '.$data['url_overridden_by_dest']);
                }
                if("link"===$data['post_hint'] && isset($data['domain'])) {
                    $postData['body'] = sprintf('<a href="%s">%s</a>', $data['url_overridden_by_dest'], $data['domain']);
                }
            }
            if(empty($postData['title'])) {
                $postData['title'] = "Title not found";
            }
            if(empty($postData['body'])) {
                $postData['body'] = "Text not found";
            }
        }
        return $postData;
    }

}

