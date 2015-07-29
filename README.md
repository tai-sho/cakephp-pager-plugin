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
.

## Documentation
後ほど追記...

## Author
Shohei Tai (@tai-sho)  
http://wp.tech-style.info/