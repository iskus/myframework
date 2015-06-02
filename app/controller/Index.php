<?php
	namespace app\controller;
	use app\model\Product;
	use app\model\Store;
	use core\Controller;


	class Index extends Controller {

		public function index() {
			$aboutIp = \core\sources\UsefulData::occurrence('109.86.108.185');
			$this->view->createContent();
		}

	}
