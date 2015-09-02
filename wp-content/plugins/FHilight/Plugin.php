<?php
/**
 * 用SyntaxHighlighter实现代码高亮
 * @package FHilight
 * @author df
 * @version 1.1.2
 * @link http://www.df-blog.cn
 */
class FHilight_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Abstract_Contents')->filter = array('FHilight_Plugin', 'render');
        Typecho_Plugin::factory('Widget_Archive')->header = array('FHilight_Plugin', 'Call_Back');
    }
   
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
   
    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
    $sect= new Typecho_Widget_Helper_Form_Element_Select('sect',array('Default',
  'Django','Eclipse','Emacs','FadeToGrey','Midnight','RDark'),0,_t('代码样式'));
    $form->addInput($sect);
    
    $CodeType = new Typecho_Widget_Helper_Form_Element_Radio('CodeType', 
        array('Code1' => '[code=php123]代码[/code]',
        'Code2' => '&lt;code lang=php&gt;代码&lt;/code&gt;',
        'Code3' => '&lt;code lang=php line=123&gt;代码&lt;/code&gt;'), 'Code1',
        '标签格式');
    $form->addInput($CodeType->multiMode());
    
    $showzh = new Typecho_Widget_Helper_Form_Element_Radio('showzh',
    array('true'=>'显示中文提示，如“viewSource”=>“查看代码”。',
    'false'=>'使用默认的英文提示。'),'true','提示语言');
    $form->addInput($showzh->multiMode());
    
    $collapse = new Typecho_Widget_Helper_Form_Element_Radio('collapse',
    array('true'=>'打开网页时折叠代码。',
    'false'=>'代码一直展开。'),'true','代码折叠');
    $form->addInput($collapse->multiMode());
    
    $gutter = new Typecho_Widget_Helper_Form_Element_Radio('gutter',
    array('true'=>'显示行号。',
    'false'=>'隐藏行号。'),'true','行号显示');
    $form->addInput($gutter->multiMode());
    
    $auto_links = new Typecho_Widget_Helper_Form_Element_Radio('auto_links',
    array('true'=>'代码中有网址时自动添加超链接。',
    'false'=>'不使用超链接。'),'false','自动链接');
    $form->addInput($auto_links->multiMode());
    
    $filters = new Typecho_Widget_Helper_Form_Element_Checkbox('filters',
    array('AS3'=>'AS3','Bash'=>'Bash','ColdFusion'=>'ColdFusion',
    'Cpp'=>'Cpp,C,C++','CSharp'=>'CSharp,C#','Css'=>'Css',
    'Delphi'=>'Delphi','Diff'=>'Diff','Erlang'=>'Erlang',
    'Groovy'=>'Groovy','Java'=>'Java','JavaFX'=>'JavaFX',
    'JScript'=>'JScript','Perl'=>'Perl','Php'=>'Php',
    'Plain'=>'Plain','PowerShell'=>'PowerShell',
    'Python'=>'Python','Ruby'=>'Ruby','Scala'=>'Scala',
    'Sql'=>'Sql','Vb'=>'Vb','Xml'=>'Xml'),
    array('Php', 'JScript','Python','Cpp' ,'Vb','CSS','Xml'),'语言支持',
    _t('选取需要支持的语言，勿多选，选得多载入的js文件越多，对速度有一定的影响'));
    $form->addInput($filters->multiMode()); 
 }
   
    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}   
   
    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render($value, $widget, $lastResult)
    {
        $value = empty($lastResult) ? $value : $lastResult;
        $settings = Helper::options()->plugin('FHilight');
        if ($widget instanceof Widget_Archive) {
            if($settings->CodeType == 'Code1'){
                $prg_str="/\[code=(.[a-zA-Z]*)([0-9]*)\](.*?)\[\/code\]/is";
            }elseif($settings->CodeType == 'Code2'){
                $prg_str="/<code(\s*lang=\S*)>(.*?)<\/code>/is";
            }else{
                $prg_str="/<code(\s*[a-zA-Z]{4}=\S*)(\s*[a-zA-Z]{4}=\S*)>(.*?)<\/code>/is";
            }
            $value['text'] = preg_replace_callback($prg_str, array('FHilight_Plugin', 'parseCallback'), $value['text']);
        }      
        return $value;   
    }
    public static function parseCallback($matches)
    {  
        $settings = Helper::options()->plugin('FHilight');
        if($settings->CodeType == 'Code1'){
            $lang=$matches[1];
            $line=$matches[2];
            $code=$matches[3];
        }elseif($settings->CodeType == 'Code2'){
            $temp = trim($matches[1]);
            if(!empty($temp)){  
                eval('$' . str_replace(' ', ';$', $temp) . ';');    
            }else{  
                $lang = 'Php';  
            }
            $line = 1;          
            $code=$matches[2];          
        }else{
            $temp = trim($matches[1]);
            if(!empty($temp)){  
                eval('$' . str_replace(' ', ';$', $temp) . ';');    
            }           
            $temp1 = trim($matches[2]);
            if(!empty($temp1)){ 
                eval('$' . str_replace(' ', ';$', $temp1) . ';');   
            }
            if($lang == '') {$lang = 'php';}
            if($line == '') {$line = '1';}
            $code=$matches[3];          
        }       
        return '<pre class="brush:'.$lang.'; first-line: '.$line.';">'.$code.'</pre>';
    }

    public static function Call_Back($headlink)
    {
        $settings = Helper::options()->plugin('FHilight');
        $Options=Typecho_Widget::widget('Widget_Options');
        $FHiLight_ul=$Options->pluginUrl .'/FHilight/';
        $Css_list=array('Default','Django','Eclipse','Emacs','FadeToGrey','Midnight','RDark');
        $FHiLight_css = 'shTheme' . $Css_list[$Options->plugin('FHilight')->sect] . '.css';
        $Prg_List=$Options->plugin('FHilight')->filters;
        $headlink='<link type="text/css" rel="stylesheet" href="'.$FHiLight_ul.'shCore.css"/>
<link type="text/css" rel="stylesheet" href="'.$FHiLight_ul.$FHiLight_css.'"/>
<script type="text/javascript" src="'.$FHiLight_ul.'shCore.js"></script>';   
        foreach($Prg_List as $item){
            $headlink.='
<script type="text/javascript" src="'.$FHiLight_ul.'shBrush'.$item.'.js"></script>';
    }
        $headlink.='
<script type="text/javascript">
  SyntaxHighlighter.config.clipboardSwf = "'.$FHiLight_ul.'clipboard.swf";
  SyntaxHighlighter.config.bloggerMode = false;';
    if($settings->showzh == 'true'){
      $headlink.='
  SyntaxHighlighter.config.strings.viewSource = "查看代码";
  SyntaxHighlighter.config.strings.expandSource = "展开代码";
  SyntaxHighlighter.config.strings.copyToClipboard = "复制到剪贴板";
  SyntaxHighlighter.config.strings.copyToClipboardConfirmation = "已将代码复制到剪贴板";
  SyntaxHighlighter.config.strings.print = "打印代码";
  SyntaxHighlighter.config.strings.help = "帮助";
  SyntaxHighlighter.config.strings.alert = "'.Typecho_Widget::widget('Widget_Options')->title.'提示您:\n\n";';
    }

    $headlink.='
  SyntaxHighlighter.defaults["collapse"] = '.$settings->collapse;

    $headlink.='
  SyntaxHighlighter.defaults["gutter"] = '.$settings->gutter;

    $headlink.='
  SyntaxHighlighter.defaults["auto-links"] = '.$settings->auto_links; 
   
    $headlink.='
  SyntaxHighlighter.all();
</script>
';
        echo $headlink;
    }
}
