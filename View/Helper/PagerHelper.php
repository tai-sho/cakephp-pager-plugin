<?php
/**
 * PagerHelper
 * @author ShoheiTai
 */
class PagerHelper extends AppHelper {

	public $helpers = array('Html');

	public $settings = array();

	public $options = array();

	public function beforeRender($viewFile) {
		$this->options['url'] = array_merge($this->request->params['pass'], $this->request->params['named']);
		if (!empty($this->request->query)) {
			$this->options['url']['?'] = $this->request->query;
		}
		parent::beforeRender($viewFile);
	}

	public function current() {
		return $this->settings['page'];
	}

	public function first($title = '<< First') {

	}

	public function last($title = 'Last >>') {

	}

	public function prev($title = '<< Previous') {

	}

	public function next($title = 'Next >>') {

	}

	public function hasNext() {

	}

	public function hasPrev() {

	}

	public function hasPage() {

	}

	public function numbers() {

	}
}