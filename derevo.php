//заполняем базу данных
INSERT INTO `categories` (`id`, `parent_id`, `name`) VALUES
(1, 0, 'Раздел 1'),
(2, 0, 'Раздел 2'),
(3, 0, 'Раздел 3'),
(4, 1, 'Раздел 1.1'),
(5, 1, 'Раздел 1.2'),
(6, 4, 'Раздел 1.1.1'),
(7, 2, 'Раздел 2.1'),
(8, 2, 'Раздел 2.2'),
(9, 3, 'Раздел 3.1');

//Выборка данных из БД
$result=mysql_query("SELECT * FROM  categories");
//Если в базе данных есть записи, формируем массив
if   (mysql_num_rows($result) > 0){
    $cats = array();
//Массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
    while($cat =  mysql_fetch_assoc($result)){
        $cats_ID[$cat['id']][] = $cat;
        $cats[$cat['parent_id']][$cat['id']] =  $cat;
    }
}

//Рекурсивная функция build_tree(). Она будет строить наше иерархическое дерево абсолютно любой вложенности.

function build_tree($cats,$parent_id,$only_parent = false){
    if(is_array($cats) and isset($cats[$parent_id])){
        $tree = '<ul>';
        if($only_parent==false){
            foreach($cats[$parent_id] as $cat){
                $tree .= '<li>'.$cat['name'].' #'.$cat['id'];
                $tree .=  build_tree($cats,$cat['id']);
                $tree .= '</li>';
            }
        }elseif(is_numeric($only_parent)){
            $cat = $cats[$parent_id][$only_parent];
            $tree .= '<li>'.$cat['name'].' #'.$cat['id'];
            $tree .=  build_tree($cats,$cat['id']);
            $tree .= '</li>';
        }
        $tree .= '</ul>';
    }
    else return null;
    return $tree;
}
