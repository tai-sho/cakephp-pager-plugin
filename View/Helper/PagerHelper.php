<?php
/**
 * PagerHelper
 * @author ShoheiTai
 * @link https://github.com/tai-sho/cakephp-pager-plugin
 */
class PagerHelper extends AppHelper {

    /**
     * 使用するヘルパーの宣言
     * @var array
     */
    public $helpers = array('Html');

    /**
     * PagerHelper設定
     * PagerComponentで初期化された設定値を格納します。
     * @var array
     */
    public $settings = array();

    /**
     * オプション
     * @var array
     */
    public $options = array();

    /**
     * レンダー前処理
     * @param unknown $viewFile
     * @see Helper::beforeRender()
     */
    public function beforeRender($viewFile) {
        $this->options['url'] = array_merge($this->request->params['pass'], $this->request->params['named']);
        if (!empty($this->request->query)) {
            $this->options['url']['?'] = $this->request->query;
        }
        parent::beforeRender($viewFile);
    }

    /**
     * ページングのリンクタグを取得します。
     * @link https://support.google.com/webmasters/answer/1663744
     * @return string
     */
    public function getRelationLink() {
        $link = '<link rel="%s" href="%s" />';
        $out = '';
        if($this->hasNext()) {
            $url = $this->_getUrl($this->settings['page'] + 1);
            $out .= sprintf($link, 'next', $url);
        }
        if($this->hasPrev()) {
            $url = $this->_getUrl($this->settings['page'] - 1);
            $out .= sprintf($link, 'prev', $url);
        }
        return $out;
    }

    public function first($title = '<< First') {

    }

    public function last($title = 'Last >>') {

    }

    public function prev($title = '<< Previous') {

    }

    public function next($title = 'Next >>') {

    }

    /**
     * 次のページの存在チェックを行います。
     * 現在のページより一つ先のページが存在すればtrue,そうでなければfalseを返します。
     * @return boolean
     */
    public function hasNext() {
        $page = $this->settings['page'];
        return $this->hasPage($page + 1);
    }

    /**
     * 前のページの存在チェックを行います。
     * 現在のページより一つ前のページが存在すればtrue,そうでなければfalseを返します。
     * @return boolean
     */
    public function hasPrev() {
        $page = $this->settings['page'];
        return $this->hasPage($page - 1);
    }

    /**
     * ページの存在チェックをし、存在すればtrue、そうでなければfalseを返します。
     * 引数がnullの場合は現在のページが存在するかをチェックします。
     * @param integer $page ページ
     * @return boolean
     */
    public function hasPage($page = null) {
        $current = $this->settings['current'];
        $pages = $this->settings['pages'];
        if(isset($page)) {
            $result = (0 < $page) && ($page <= $pages);
        } else {
            $result = (bool)$current;
        }
        return $result;
    }

    public function numbers() {

    }
}