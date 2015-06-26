<?php
/**
 * Created by PhpStorm.
 * User: iskus
 * Date: 12.06.15
 * Time: 17:48
 */

namespace app\controller;


use core\Controller;
use core\database\Db;
use core\Model;
use libs\ParserYopta;

// CREATE TABLE `info_cards` (
// `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
// `title` varchar(254) NOT NULL,
// `edrpou` varchar(254) NOT NULL,
// `email` varchar(254) NOT NULL,
// `phone` varchar(254) NOT NULL,
// `adress` varchar(254) NOT NULL,
// `reg_adress` varchar(254) NOT NULL,
// `face` varchar(254) NOT NULL,
// `created` varchar(254) NOT NULL,
// PRIMARY KEY (`id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8

class Test extends Controller {
    public $fields,
            $model;

    public function __construct() {

        ini_set('max_execution_time', '0');
        error_reporting(E_ALL);

        $this->fields = [
            'ЄДРПОУ:' => 'edrpou',
            'Адреса електронної пошти:' => 'email',
            'Номер факсу (телефаксу):' => 'phone',
            'Поштова адреса:' => 'adress',
            'Юридична адреса:' => 'reg_adress',
            'Відповідальна особа:' => 'face',
            'Дата заповнення:' => 'created',
            'title' => 'title',
        ];

        $this->model = new Model();
    }

    public function index() {

        //$this->uniqueLinks();
         $this->getInfo();
    }
/**  **/
    public function getInfo() {

        for ($i = 100; $i <= 10100; $i += 100) {
            $this->model->setDbTable('links');
            $links = $this->model->getEntitys([], $i, 100);
            $this->model->setDbTable('info_cards');
            $objects = [];
            foreach ($links as $link) {
                $parser = new ParserYopta($link->link);
                $titleBlock = $parser->pq->find('div#header > div.name');
                $title = pq($titleBlock)->find('div.left')->text() . ' | '
                    . pq($titleBlock)->find('div.actual')->text();
                $obj = new \stdClass();
                $obj->title = $title;
                $rows = $parser->pq->find('div#container > div.row');
                foreach ($rows as $row) {
                    $prop = $this->fields[pq($row)->find('div.left')->text()];
                    $value = pq($row)->find('div.right')->text();
                    $obj->$prop = $value;
                    //echo $prop . '  ' . $value . '<br/>';
                }
                //$objects[] = $obj;
                //echo '<br/>';
                $this->model->addEntity($obj);
                echo '-- incert<br/>';
            }
            //var_dump(count($objects));die;
            //$this->model->addEntitys($objects);
                echo '<br/>INSERT +100<br/>';

        }
    }

    public function uniqueLinks() {
        for ($i = 0; $i < 10; $i++) {
            $url = 'http://email.court.gov.ua/search?utf8=%E2%9C%93&term=' . $i . '&count_page=10';

            $parser = new ParserYopta($url);
            $elements = $parser->pq->find('div.name > a');
            $this->model->setDbTable('links');

            $links = ['replace' => true];
            echo count($elements);
            foreach ($elements as $key => $element) {
                $obj = new \stdClass();
                $obj->link = "http://email.court.gov.ua" . pq($element)->attr('href');
                $links[] = $obj;
                if (($key >= 100 && ($key % 100 == 0)) || (count($elements) - 1) == $key) {
                    $this->model->addEntitys($links);
                    $links = ['replace' => true];
                    echo "1";
                }
//                $obj->replace = true;
//                $this->model->addEntity($obj);

                //echo pq($element)->attr('href');
//            $title = pq($element)->find('div.title > a');
//            $title = pq($title)->text();
//            $descr = pq($element)->find('div.text > span');
//            $descr = pq($descr)->text();
//            $link_text = pq($element)->find('div.info:first > span');
//            $link_text = pq($link_text)->text();
//            $link_text = explode('•', $link_text);
//            $link = trim($link_text[0]);
//            $info[] = array('title' => $title, 'descr' => $descr, 'link' => $link);
            }
        }
    }
}