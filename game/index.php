<?php
    $gameList = array(
        array('name' => '魂斗罗', 'file' => 'hundouluo.swf'),
        array('name' => '塔防', 'file' => 'tafang.swf'),
        array('name' => '屁王兄弟', 'file' => 'piwangxiongdi.swf'),
        array('name' => '变形金刚战记', 'file' => 'bianxingjingang.swf'),
        array('name' => '寻宝兄妹', 'file' => 'xunbaoxiongmei.swf'),
        array('name' => '幻想纹章', 'file' => 'huanxiangwenzhang.swf'),
        array('name' => '超级鸡鸭兄弟', 'file' => 'jiyaxiongdi.swf'),
        array('name' => '暴击僵尸', 'file' => 'baojijiangshi.swf'),
    );
    $host = $_SERVER['HTTP_HOST'];
    $html = '<ul>';
    foreach ($gameList as $gVal) {
        $html .= '<li><a href = "/game/' . $gVal['file'] . '" target = "_blank">' . $gVal['name'] . '</a></li>';
    }
    $html .= '</ul>';
    
    echo $html;
