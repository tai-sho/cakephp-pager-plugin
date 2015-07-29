# CakePHP PagerPlugin
CakePHPのモデルを使用しないページングプラグイン  
CakePHPのPaginatorはモデルを使用したデータ取得が前提となっているため、  
外部APIなどから取得したデータをページングに適用できません。  
PagerPluginを使用することで初期化関数にページング対象となるデータ配列と最大件数を渡すことで  
モデルを使用しないページングAPIを提供します。

## Requirements
* CakePHP2.x
* PHP5.3以降

## Installation
1.ダウンロードかclone後、cakephp-pager-pluginをPagerにリネーム  
2.app/Plugin以下にコピー  
3.app/Config/bootstrap.phpに一行追加  
    CakePlugin::load('Pager');  
4.Controllerでcomponentの読み込み  
<pre>
    public $components = array('Pager.Pager' => array(  
        'limit' => 20,// 1ページの最大件数  
        ’pageQuery’ => 'p'// ページングで使用するURLパラメータ名  
    ));  
</pre>
5.アクションで初期化処理  
<pre>
    public function index() {
        // 設定とページングパラメータからLIMIT,OFFSETの値を自動取得
        $limit = $this->Pager->getLimit();
        $offset = $this->Pager->getOffset();
        // ページごとのデータを取得
        $pageResult = $this->Model->find('all', compact('limit', 'offset'));
        // 最大件数を取得
        $totalCount = $this->Model->find('count');
        // 初期化処理
        $this->Pager->paginate($pageResult, $totalCount);
        $this->set(compact('pageResult'));
    }
</pre>
6.Viewで呼び出し(PagerHelperはオートロードされます。)
<pre>
    echo $this->Pager->numbers();
    echo '<br>';
    echo $this->Pager->counter(array(
        'format' => 
            '合計 {:pages} ページ中の {:page} ページ目です。総レコード {:count} のうち、  {:start} 行目から {:end} 行目までの {:current} 行を表示しています。'
));
</pre>

## Documentation
後ほど追記...  

## Author
Shohei Tai (@tai-sho)  
http://wp.tech-style.info/
