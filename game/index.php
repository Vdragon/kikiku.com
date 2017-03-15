<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <title>游戏列表 | Kuu</title>
    <meta id="mydesc" name="keywords" content="game" />
    <meta id="description" name="description" content="game" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="http://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
</head>
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
    $html = '<div id="game_list"><span class="show_btn">隐藏</span><ul style="float: left; border: 1px solid #ccc;">';
    foreach ($gameList as $gVal) {
        $html .= '<li><a href="javascript:;" value="/game/' . $gVal['file'] . '">' . $gVal['name'] . '</a></li>';
    }
    $html .= '</ul></div>';
    
    echo $html;
    ?>

<script>
    $(function(){
        $('#game_list li a').click(function(){
            showSWF($(this).attr('value'), 'game_swf');
        });
        $('#game_list span.show_btn').click(
            var $ul = $(this).closest('#game_list').find('ul');
            if ($(this).data('value')) {
                $(this).html('显示').data('value', 0);
                $ul.fadeOut();
            } else {
                $(this).html('隐藏').data('value', 1);
                $ul.fadeIn();
            }
        );
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
