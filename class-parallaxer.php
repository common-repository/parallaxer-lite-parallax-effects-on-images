<?php

class DZSParallaxer{

    public $the_url = '';


    private $frontend_errors = array();
    private $the_shortcode = 'parallaxer';

    public $db_mainoptions = array();
    public $db_mainoptions_default = array();
    public $dbname_layouts = 'dzsprx_items';
    public $dbname_mainoptions = 'dzsprx_mainoptoins';

    public $pagename_mainoptions = 'dzsprx_mainoptions';

    private $pluginmode = 'plugin';
    private $include_settings = 'off';
    private $layout_index = 0;
    private $enable_scrollbar = 'off';

    private $cap_admin = 'publish_posts';
	function __construct(){


        if ($this->pluginmode == 'theme') {
            $this->the_url = THEME_URL.'plugins/dzs-parallaxer/';
        } else {
            $this->the_url = plugins_url('',__FILE__).'/';
        }

        $this->db_mainoptions_default = array(
            'always_embed' => 'off'
        );

        $this->db_mainoptions = get_option($this->dbname_mainoptions);

        if($this->db_mainoptions==''){
            $this->db_mainoptions = $this->db_mainoptions_default;
        }

//        if (isset($_POST['dzsprx_importdb'])) {
//
//            $this->import_database();
//        }
//        if (isset($_POST['dzsprx_exportdb'])) {
//
//            header('Content-Type: text/plain');
//            header('Content-Disposition: attachment; filename="'."dzsprx_backup.txt".'"');
//            echo serialize($this->db_layouts);
//            die();
//        }

		add_action('init', array($this, 'handle_init'));
        add_action('admin_head', array($this, 'handle_admin_head'));
		add_action('wp_head', array($this, 'handle_wp_head'));
		add_action('wp_footer', array($this, 'handle_wp_footer'));
		add_action('admin_menu', array($this, 'handle_admin_menu'));

        add_shortcode($this->the_shortcode, array($this, 'shortcode_main') );
        add_shortcode('dzs_'.$this->the_shortcode, array($this,'shortcode_main') );
        add_shortcode('dzsprx_custom_content', array($this,'shortcode_custom_content') );
	}

	function handle_init(){

        wp_enqueue_script('jquery');
		if(is_admin()){




		}else{

		}



	}


    function handle_wp_head(){


    }

    function handle_wp_footer(){


    }


	function handle_admin_menu(){

//		add_options_page('Layouter Options', 'Layouter Options', $this->admin_cap, $this->pagename_main, array($this, 'page_mainoptions'));


	}


	function handle_admin_head(){

	}


    function shortcode_custom_content($pargs=array(), $content = null){
        $fout = '';

//        $fout.='<div style="display:none">';
        if($content){

        }

//        $fout.='</div>';

        return $fout;
    }

    function shortcode_main($pargs=array(), $content = null){

        $fout = '';


        wp_enqueue_style('dzs.parallaxer', $this->the_url.'dzsparallaxer/dzsparallaxer.css');
        wp_enqueue_script('dzs.parallaxer', $this->the_url.'dzsparallaxer/dzsparallaxer.js');

        $margs = array(
            'media' => '',
            'type' => 'detect',
            'clip_height' => '400',
            'total_height' => '600',
            'direction' => 'reverse',
            'mode' => 'normal',
            'enable_scrollbar' => 'off',
            'breakout' => 'off',
        );

        $margs = array_merge($margs, $pargs);


//        print_r($margs['type']);
/*
 *
 * <div class="" data-options='{   direction: "reverse"}' style="height: 350px;">

    <div class="divimage dzsparallaxer--target " style="width: 101%; height: 600px; background-image: url(img/imgbig1.jpg)">
    </div>
 */


        if($margs['type']==='detect'){ $margs['type'] = 'image'; }





        $str_h = '';

        if($margs['clip_height']){
            $str_h = ' height: '.DZSHelpers::transform_to_str_size($margs['clip_height']).';';
        }
        $str_th = '';

        if($margs['total_height']){
            $str_th = ' height: '.DZSHelpers::transform_to_str_size($margs['total_height']).';';
        }

        $fout.='<div class="dzsparallaxer auto-init use-loading';

        if($margs['mode']==='simple'){
            $fout.=' simple-parallax';
        }

        $fout.='"  data-options="{direction: \''.$margs['direction'].'\'';

        if($margs['mode'] && $margs['mode']!='normal'){
            $fout.=',mode_scroll:\''. $margs['mode'].'\'';
        }
        if($margs['breakout'] && $margs['breakout']=='trybreakout'){
            $fout.=',js_breakout:\'on\'';
        }

        $fout.='}" style="'.$str_h.'">';


        if($content && strpos($content, '[dzsprx_custom_content]')!==false){

//            echo $content;

            preg_match_all('/\[dzsprx_custom_content\](.*?)\[\/dzsprx_custom_content\]/',$content, $aux_a);

            if($aux_a[1]){
                $fout.='<div class="dzsparallaxer--target " style="width: 100%; '.$str_th.'">'.$aux_a[1][0].'</div>';
            }


        }else{
            if($margs['type']=='image'){
                $fout.='<div class="divimage dzsparallaxer--target "  style="background-image:url('.$margs['media'].'); width: 100%; '.$str_th.'"></div>';
            }
        }



        $fout.='<div class="preloader-semicircles"></div>';


        if($content){
            $fout.=do_shortcode($content);
        }

        $fout.='</div>';


        $this->layout_index++;
        return $fout;


    }


}