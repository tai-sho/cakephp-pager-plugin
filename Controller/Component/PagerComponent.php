<?php
/**
 * PagerComponent
 * @author ShoheiTai
 */
class PagerComponent extends Component {

    /**
     * PagerComponent settings.
     * @var array
     */
    public $settings = array();

    /**
     * Constructor
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
     * beforeRender
     * PagerHelper autoload
     *
     * @see Component::beforeRender()
     */
    public function beforeRender(Controller $controller) {
        $helperSetting['pages'] = (int)ceil($this->settings['count'] / $this->settings['limit']);
    }

    public function paginate(array $data, $maxCount) {

    }

    public function getLimit() {

    }

    public function getOffset() {

    }
}