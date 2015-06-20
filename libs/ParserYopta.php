<?php
/**
 * Created by PhpStorm.
 * User: iskus
 * Date: 12.06.15
 * Time: 17:47
 */

namespace libs;

require_once("phpQuery/phpQuery/phpQuery.php");

class ParserYopta
{
    public $url;
    public $page;
    public $pq;

    public function __construct($url)
    {
        $this->setPage($url);
    }

    public function setPage($url = false) {
        if (!$url) return false;
        $this->url = $url;
        $this->page = $this->getPage();
        $this->pq = \phpQuery::newDocument($this->page);
    }

    protected function getPage()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
}