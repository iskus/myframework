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

class Test extends Controller {

    public function index() {

        $fields = [
            'ЄДРПОУ:' => 'edrpou',
            'Адреса електронної пошти:' => 'email',
            'Номер факсу (телефаксу):' => 'phone',
            'Поштова адреса:' => 'adress',
            'Юридична адреса:' => 'reg_adress',
            'Відповідальна особа:' => 'face',
            'Дата заповнення:' => 'created',
            'title' => 'title',
        ];

        ini_set('max_execution_time', '0');
        error_reporting(E_ALL);

//        $this->uniqueLinks();

        for ($i = 0; $i <= 1000; $i += 100) {
            $model = new Model();
            $model->setDbTable('links');
            $links = $model->getEntitys([], $i, 100);
            $model->setDbTable('info_cards');

            foreach ($links as $link) {
                $parser = new ParserYopta($link->link);
                $titleBlock = $parser->pq->find('div#header > div.name');
                $title = pq($titleBlock)->find('div.left')->text() . ' | '
                    . pq($titleBlock)->find('div.actual')->text();
                $obj = new \stdClass();
                $obj->title = $title;
                $rows = $parser->pq->find('div#container > div.row');
                foreach ($rows as $row) {
                    $prop = $fields[pq($row)->find('div.left')->text()];
                    $value = pq($row)->find('div.right')->text();
                    $obj->$prop = $value;
                    //echo $prop . '  ' . $value . '<br/>';
                }
                var_dump($obj);
                //echo '<br/>';
                $model->addEntity($obj);
            }
           
        }



    }

    public function prepareInfo() {

    }

    public function uniqueLinks() {
        for ($i = 0; $i < 10; $i++) {
            $url = 'http://email.court.gov.ua/search?utf8=%E2%9C%93&term=' . $i . '&count_page=10';

            $parser = new ParserYopta($url);
            $elements = $parser->pq->find('div.name > a');

            foreach ($elements as $element) {
                $model = new Model();
                $model->setDbTable('links');
                $obj = new \stdClass();
                $obj->link = "http://email.court.gov.ua" . pq($element)->attr('href');
                $obj->replace = true;
                $model->addEntity($obj);
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