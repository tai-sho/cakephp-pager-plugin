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

    public function prev($title = '<< Previous', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
        $default = array('tag' => 'span');
        $out = '';
        if($this->hasPage(2)) {
            if($this->hasPrev()) {
                $options += $default;

            }
        }
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

    /**
     * ページングカウンター文字列を出力します。
     * オプション
     *  - 'separator' 値を区切るセパレータを指定します。
     *  - 'format' 出力する文字列のフォーマットを指定します。
     *      'pages'とした場合は「1 of 5」のように変換します。
     *      'range'とした場合は「 1 - 3 of 13」のように変換します。
     *      その他の場合は文字列の中に次の文字を含めることで各値に自動変換します。
     *      `{:page}`, `{:pages}`, `{:current}`, `{:count}`, `{:start}`, `{:end}`
     * @param array|string $options 設定
     * @return mixed
     */
    public function counter($options = array()) {
        if(is_string($options)) {
            $options = array('format' => $options);
        }
        $options += array(
                'format' => 'pages',
                'separator' => 'of'
        );
        extract($this->settings);
        switch($options['format']) {
            case 'range':
                if(!is_array($options['separator'])) {
                    $options['separator'] = array('-', $options['separator']);
                }
                $out = $start. $options['separator'][0]. $end. $options['separator'][1];
                $out .= $count;
                break;
            case 'pages':
                $out = $page. $options['separator']. $pages;
                break;
            default:
                $map = array(
                        '{:page}' => $page,
                        '{:pages}' => $pages,
                        '{:current}' => $current,
                        '{:count}' => $count,
                        '{:start}' => $start,
                        '{:end}' => $end
                );
                $out = str_replace(array_keys($map), array_values($map), $options['format']);
        }
        return $out;
    }

    /**
     * ページ数のクエリを付与したURLを返します。
     * @param integer $page ページングのクエリに付与するページ
     * @return string
     */
    protected function _getUrl($page = null) {
        // TODO fullbaseUrlのメモ化
        $url = Router::fullbaseUrl();
        $requestUrl = parse_url(env('REQUEST_URI'));
        if(isset($requestUrl['path'])) {
            $url .= $requestUrl['path'];
        }
        if(is_numeric($page)) {
            if($page === 1) {
                $page = null;
            }
            $url .= $this->_convertUrlQuery(array(
                    $this->settings['pageQuery'] => $page
            ));
        }
        return $url;
    }

    /**
     * 現在のクエリパラメータを取得し、$paramsで指定した値へ書き換えて返します。
     * @param array $params URLパラメータを配列で指定
     * @param boolean $op ?の付与
     */
    protected function _convertUrlQuery($params = array(), $op = true) {
        $url = parse_url(env('REQUEST_URI'));
        if(isset($url['query'])) {
            parse_str($url['query'], $query);
        } else {
            $query = array();
        }
        foreach($params as $key => $value) {
            if($key && is_null($value)) {
                unset($query[$key]);
            } else {
                $query[$key] = $value;
            }
        }
        $query = preg_replace('/%5B[0-9]%5D/', '%5B%5D', http_build_query($query));
        $query = str_replace('=&', '&', $query);
        $query = preg_replace('/=$/', '', $query);
        return $query ? ($op ? '?' : ''). $query;
    }
}