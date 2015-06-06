<?php
	namespace app\controller;
	use core\Controller;
    use core\sources\UsefulData;


    class Index extends Controller {

		public function index() {
			$aboutIp = UsefulData::occurrence(UsefulData::getRequest('REMOTE_ADDR', 'server'));
			$this->view->createContent();
		}

	}
