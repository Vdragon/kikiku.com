<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <title>游戏列表 | Kuu</title>
    <meta id="mydesc" name="keywords" content="game" />
    <meta id="description" name="description" content="game" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="http://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <style>
        #game_list {float: left;}
        #game_list .show_btn{color: #E91E63; font-size: 13px; cursor: pointer;}
        #game_list ul li{font-size: 14px; display: inline; padding: 5px;}
        #game_list ul li a{color: #FF5722; text-decoration: none;}
    </style>
</head>
<?php
    $gameList = array(
        array('name' => '魂斗罗', 'file' => 'hundouluo.swf'),
        array('name' => '拳皇出招表', 'file' => false, 'value' => 'http://www.chuzhaobiao.com/kof/quanhuang97/'),
        array('name' => '拳皇wing1.85无敌版', 'file' => 'quanhuangwing1.85wudi.swf'),
        array('name' => '拳皇wing1.91', 'file' => 'quanhuangwing1.91.swf'),
        array('name' => '拳皇Wing1.85', 'file' => 'quanhuangwing1.85.swf'),
        array('name' => '拳皇大战之饿狼来袭无敌版', 'file' => 'quanhuangdazhanelang.swf'),
        array('name' => '塔防', 'file' => 'tafang.swf'),
        array('name' => '屁王兄弟', 'file' => 'piwangxiongdi.swf'),
        array('name' => '变形金刚战记', 'file' => 'bianxingjingang.swf'),
        array('name' => '寻宝兄妹', 'file' => 'xunbaoxiongmei.swf'),
        array('name' => '幻想纹章', 'file' => 'huanxiangwenzhang.swf'),
        array('name' => '超级鸡鸭兄弟', 'file' => 'jiyaxiongdi.swf'),
        array('name' => '暴击僵尸', 'file' => 'baojijiangshi.swf'),
        array('name' => '黄金矿工', 'file' => 'huangjinkuanggong.swf'),
        array('name' => '双截龙', 'file' => 'shuangjielong.swf'),
    );
    $host = $_SERVER['HTTP_HOST'];
    $html = '<div id="game_list"><span class="show_btn">隐藏</span><ul>';
    foreach ($gameList as $gVal) {
        //是否为非文件模式
        if (!$gVal['file']) {
            $html .= '<li><a href="' . $gVal['value'] . '" target="_blank" data-nofile=1>' . $gVal['name'] . '</a></li>';
            continue;
        }
        $html .= '<li><a href="javascript:;" value="/game/' . $gVal['file'] . '">' . $gVal['name'] . '</a></li>';
    }
    $html .= '</ul></div>';
    
    echo $html;
    ?>

<script>
    $(function(){
        $('#game_list li a').click(function(){
            if ($(this).data('nofile')) {
                return false;
            }
            $('#game_swf').html('');
            showSWF($(this).attr('value'), 'game_swf');
        });
        $('#game_list span.show_btn').click(function(){
            var $ul = $(this).closest('#game_list').find('ul');
            //默认为1(显示中)
            $(this).data('value') || $(this).data('value', 1);
            //当前值为1(显示中)，2(隐藏中)
            if ($(this).data('value') == 1) {
                //隐藏掉
                $(this).html('显示').data('value', 2);
                $ul.fadeOut();
            } else {
                //显示出来
                $(this).html('隐藏').data('value', 1);
                $ul.fadeIn();
            }
        });
    });
    function showSWF(urlString, elementID){
        var displayContainer = document.getElementById(elementID);
        var flash = createSWFObject(urlString, 'opaque', 650, 650);
        displayContainer.appendChild(flash);
    }
    function createSWFObject(urlString, wmodeString, width, height){
        var SWFObject = document.createElement("object");
        SWFObject.setAttribute("type","application/x-shockwave-flash");
        SWFObject.setAttribute("width","100%");
        SWFObject.setAttribute("height","100%");
        var movieParam = document.createElement("param");
        movieParam.setAttribute("name","movie");
        movieParam.setAttribute("value",urlString);
        SWFObject.appendChild(movieParam);
        var wmodeParam = document.createElement("param");
        wmodeParam.setAttribute("name","wmode");
        wmodeParam.setAttribute("value",wmodeString);
        SWFObject.appendChild(wmodeParam);
        return SWFObject;
    }
</script>
<body>
<div id="game_swf"></div>
</body>
</html>
