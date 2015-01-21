<?php
App::uses('AppController', 'Controller');
class PagerController extends AppController {

    public $components = array('Pager.Pager');

    public $helpers = array('Pager.Pager');

}