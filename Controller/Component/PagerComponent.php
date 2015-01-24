<?php
/**
 * PagerComponent
 * @author ShoheiTai
 * @link https://github.com/tai-sho/cakephp-pager-plugin
 */
class PagerComponent extends Component {

    /**
     * PagerComponentの設定値を格納します。
     * 設定値はPagerHelperに引き継がれます。
     * @var array
     */
    public $settings = array();

    /**
     * コンストラクタ
     *
     * @param ComponentCollection $collection
     * @param array $settings configuration settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->settings = array_merge(array(
                'page' => 1, 'limit' => 20, 'count' => 0,
                'current' => 0, 'pageQuery' => 'page'
        ), (array)$settings);
        $pageQuery = $this->settings['pageQuery'];
        $page = (int)$collection->getController()->request->query($pageQuery);
        $this->settings['page'] = (1 > $page) ? 1 : $page;
    }

    /**
     * レンダー前処理
     * 現在のページ情報をクエリから取得し、ページングに関する設定値を初期化します。
     * @see Component::beforeRender()
     */
    public function beforeRender(Controller $controller) {
        parent::beforeRender($controller);
        $helperSetting['pages'] = (int)ceil($this->settings['count'] / $this->settings['limit']);
        if(($helperSetting['pages'] < $this->settings['page']) && (0 < $helperSetting['pages'])) {
            $url = array(
                    'action' => $controller->action,
                    '?' => $controller->request->query
            );
            $url += array_merge($controller->request->params['pass'],$controller->request->params['named']);
            $url['?'][$this->settings['pageQuery']] = 1;
            $controller->redirect($url);
        }
        $end = ($this->settings['page'] - 1) * $this->settings['limit'] + $this->settings['limit'];
        $helperSetting['end'] = $end > $this->settings['count'] ? $this->settings['count'] : $end;
        $helperSetting['start'] = ($helperSetting['end'] === 0) ? 0 : ($this->settings['page'] - 1) * $this->settings['limit'] + 1;
        $controller->helpers['Pager.Pager'] = array_merge($this->settings, $helperSetting);
    }

    /**
     * ページングの初期化を行います。
     * @param array $data
     * @param integer $maxCount
     */
    public function paginate(array $data, $maxCount = 0) {
        $current = count($data);
        $this->settings['current'] = $current;
        $this->settings['count'] = $maxCount;
    }

    /**
     * 1ページの最大件数を取得します。
     * @return integer
     */
    public function getLimit() {
        return $this->settings['limit'];
    }

    /**
     * 現在のページのOFFSETを取得します。
     * @return integer
     */
    public function getOffset() {
        return ($this->getPage() - 1) * $this->getLimit();

    }

    /**
     * 現在のページ数を取得します。
     * @return integer
     */
    public function getPage() {
        return $this->settings['page'];
    }
}