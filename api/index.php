<?php 

require_once(( realpath( dirname( __FILE__ ) ).'../../core/init.php'));
// instantiate the article class..
$articles = new Article();

$arr = [];
foreach($articles->getAllArticlesAPI() as $r) {
    if($r['isFeatured'] == 1){
    $arr[] = json_encode($r);
    }
}



$json = json_encode($arr);

echo $json;

