<?php
/*
Plugin Name: Beds24 Online Booking
Plugin URI: https://beds24.com
Description: Beds24.com is a full featured online booking engine. The system is very flexible with many options for customization. The Beds24.com online booking system and channel manager is suitable for any type of accommodation such as hotels, motels, B&B's, hostels, vacation rentals, holiday homes, campgrounds and property management companies selling multiple properties as well as selling extras like tickets or tours. The plugin is free to use but you do need an account with Beds24.com. A free trial account is available <a href="https://beds24.com/join.html" target="_blank">here</a>
Version: 2.0.29
Author: Mark Kinchin
Author URI: https://beds24.com
License: GPL2 or later
*/
register_activation_hook(__FILE__,'beds24_booking_install');
register_deactivation_hook( __FILE__, 'beds24_booking_remove' );

add_filter('widget_text', 'do_shortcode', 11);
add_filter( 'query_vars', 'add_query_vars_filter' );

// INCLUDES
include_once('inc/widgets/beds24_widget.php');
include_once('inc/shortcodes/b24_jquery_widget_shortcode.php');

function add_query_vars_filter( $vars ){
  $vars[] = "propid";
  $vars[] = "roomid";
  return $vars;
}

function save_output_buffer_to_file()
{
    file_put_contents(
      ABSPATH. 'wp-content/plugins/activation_output_buffer.html'
    , ob_get_contents()
    );
}
add_action('activated_plugin','save_output_buffer_to_file');


function beds24_booking_install()
{
$all_options=beds24_all_options();
$default_values=beds24_all_options('default_values');
	foreach($all_options as $opt){
		if(NULL === get_option( $opt, NULL )){
			update_option( $opt, $default_values[$opt]);
		}
	}
}

function beds24_booking_remove()
{
	$all_options=beds24_all_options();
	foreach($all_options as $opt){
		delete_option($opt);
	}
}


function beds24_scripts()
{
if (!session_id() && !headers_sent())
		session_start();

wp_enqueue_script('jquery');
wp_enqueue_style('beds24', plugins_url( '/theme-files/beds24.css', __FILE__ ));
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_script( 'beds24-datepicker', plugins_url('/js/beds24-datepicker.js', __FILE__ ), array( 'jquery-ui-datepicker' ));
wp_localize_script('beds24-datepicker', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));

wp_register_script( 'bed24-widget-script', '//media.xmlcal.com/widget/1.00/js/bookWidget.min.js', array('jquery'), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'beds24_scripts' );

add_shortcode("beds24", "beds24_booking_page");
add_shortcode("beds24-link", "beds24_booking_page_link");
add_shortcode("beds24-button", "beds24_booking_page_button");
add_shortcode("beds24-box", "beds24_booking_page_box");
add_shortcode("beds24-strip", "beds24_booking_page_strip");
add_shortcode("beds24-searchbox", "beds24_booking_page_searchbox");
add_shortcode("beds24-searchresult", "beds24_booking_page_searchresult");
add_shortcode("beds24-embed", "beds24_booking_page_embed");
add_shortcode("beds24-landing", "beds24_booking_page_landing");


add_action( 'admin_enqueue_scripts', 'beds24_admin_scripts' );

function beds24_admin_scripts( $hook_suffix ) {
		// first check that $hook_suffix is appropriate for your admin page
		wp_enqueue_style('beds24-admin-css', plugins_url( '/css/beds24-admin.css', __FILE__ ));
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'beds24-admin', plugins_url('/js/beds24-admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}


function beds24_booking_page_link($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'link';
if (!isset($atts['padding']))
	$atts['padding'] = 0;
if (!isset($atts['text']))
	$atts['text'] = 'Book Now';
return beds24_booking_page($atts);
}

function beds24_booking_page_button($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'button';
if (!isset($atts['text']))
	$atts['text'] = 'Book Now';
if (!isset($atts['class']))
	$atts['class'] = 'beds24_bookbutton';
return beds24_booking_page($atts);
}

function beds24_booking_page_box($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'box';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_strip($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'strip';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_searchbox($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'searchbox';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_searchresult($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'searchresult';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_embed($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'embed';
if (!isset($atts['advancedays']))
	$atts['advancedays'] = '-1';
return beds24_booking_page($atts);
}

function beds24_booking_page_landing($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'embed';
if (!isset($atts['noselection']))
	$atts['noselection'] = true;
return beds24_booking_page($atts);
}

function beds24_booking_page($atts)
{

foreach ($atts as &$item) {
	if (is_string($item)) {
		$item = esc_html($item);
	}
}
	
$postid = get_the_ID();

//ownerid
if (isset($atts['ownerid']))
	$ownerid = sanitize_text_field($atts['ownerid']);
else if (get_post_meta($postid, 'ownerid', true)>0)
	$ownerid = sanitize_text_field(get_post_meta($postid, 'ownerid', true));
else
	$ownerid = intval(get_option('beds24_ownerid'));

if ($ownerid > 0)
	$owner = '&amp;ownerid='.intval($ownerid);
else if (isset($atts['ownerid']))
	$owner = '&amp;ownerid='.intval($atts['ownerid']);
else
	$owner = '';

//propid
    $propid = false;
    if (isset($atts['propid']))
        $propid = intval($atts['propid']);
    else if (get_post_meta($postid, 'propid', true)>0 && !isset($atts['ownerid']))
        $propid = intval(get_post_meta($postid, 'propid', true));
    else if (get_query_var('propid')>0 && !isset($atts['ownerid']))
        $propid = intval(get_query_var('propid'));
    else if (!isset($atts['ownerid']))
        $propid = intval(get_option('beds24_propid'));

    if ($propid > 0)
        $prop = '&amp;propid='.intval($propid);
    else if (isset($atts['propid']))
        $prop = '&amp;propid='.intval($atts['propid']);
    else
        $prop = '';

//roomid
if (isset($atts['roomid']))
	$roomid = intval($atts['roomid']);
else if (get_post_meta($postid, 'roomid', true)>0)
	$roomid = intval(get_post_meta($postid, 'roomid', true));
else if (get_query_var('roomid')>0)
    $roomid = intval(get_query_var('roomid'));
else
	$roomid = intval(get_option('beds24_roomid'));

if ($roomid > 0)
	$room = '&amp;roomid='.intval($roomid);
else if (isset($atts['roomid']))
	$room = '&amp;roomid='.intval($atts['roomid']);
else
	$room = '';

//number of dates displayed
if (isset($atts['numdisplayed']))
	$numdisplayed = intval($atts['numdisplayed']);
else
	$numdisplayed = intval(get_option('beds24_numdisplayed'));
if (isset($numdisplayed) && $numdisplayed!=-1)
	$urlnumdisplayed = "&amp;numdisplayed=".urlencode(intval($numdisplayed));
else
	$urlnumdisplayed = '';

//show calendar
if (isset($atts['hidecalendar']))
	$hidecalendar = sanitize_text_field($atts['hidecalendar']);
else
	$hidecalendar = sanitize_text_field(get_option('beds24_hidecalendar'));
if (isset($hidecalendar) && $hidecalendar!=-1)
	$urlhidecalendar = "&amp;hidecalendar=".urlencode($hidecalendar);
else
	$urlhidecalendar = '';

//show header
if (isset($atts['hideheader']))
	$hideheader = sanitize_text_field($atts['hideheader']);
else
	$hideheader = sanitize_text_field(get_option('beds24_hideheader'));
if (isset($hideheader) && $hideheader!=-1)
	$urlhideheader = "&amp;hideheader=".urlencode($hideheader);
else
	$urlhideheader = '';

//show footer
if (isset($atts['hidefooter']))
	$hidefooter = sanitize_text_field($atts['hidefooter']);
else
	$hidefooter = sanitize_text_field(get_option('beds24_hidefooter'));
if (isset($hidefooter) && $hidefooter!=-1)
	$urlhidefooter = "&amp;hidefooter=".urlencode($hidefooter);
else
	$urlhidefooter = '';

//lang
if (isset($atts['lang']))
	$lang = sanitize_text_field(strtolower($atts['lang']));
else if (get_post_meta($postid, 'lang', true))
	$lang = sanitize_text_field(strtolower(get_post_meta($postid, 'lang', true)));
else
	$lang = '';

$allowed_langs = ["en", "ar", "bg", "ca", "hr", "cs", "da", "de", "el", "es", "et", "fi", "fr", "he", "hu", "hy", "id", "is", "it", "ja", "ko", "lt", "lv", "my", "nl", "no", "pl", "pt", "ro", "ru", "sk", "sl", "sr", "sv", "th", "tr", "vi", "zh", "zt"];
$lang = in_array($lang, $allowed_langs) ? $lang : '';

if ($lang)
	$urllang = '&amp;lang='.urlencode($lang);
else
	$urllang = '';

//referer
if (isset($atts['referer']))
	$referer = sanitize_text_field(strtolower($atts['referer']));
else if (get_post_meta($postid, 'referer', true))
	$referer = sanitize_text_field(get_post_meta($postid, 'referer', true));
else
	$referer = sanitize_text_field(get_option('beds24_referer'));

if ($referer)
	$urlreferer = '&amp;referer='.urlencode($referer);
else
	$urlreferer = '';

//domain
if (isset($atts['domain']))
	$domain = sanitize_text_field(strtolower($atts['domain']));
else if (get_post_meta($postid, 'domain', true))
	$domain = sanitize_text_field(get_post_meta($postid, 'domain', true));
else
	$domain = sanitize_text_field(get_option('beds24_domain'));

if (!$domain)
	$domain = 'https://beds24.com';

//scrolltop (for iframe)
if (isset($atts['scrolltop']))
	$scrolltop = sanitize_text_field(strtolower($atts['scrolltop']));
else if (get_post_meta($postid, 'scrolltop', true))
	$scrolltop = sanitize_text_field(strtolower(get_post_meta($postid, 'scrolltop', true)));
else
	$scrolltop = false;


//checkin or show this many days from now
$checkin = false;
if (isset($_REQUEST['checkin']))
	$checkin = sanitize_text_field($_REQUEST['checkin']);
else if (isset($_REQUEST['fdate_date']) && isset($_REQUEST['fdate_monthyear']))
	$checkin = date('Y-m-d', strtotime(sanitize_text_field($_REQUEST['fdate_monthyear']).'-'.sanitize_text_field($_REQUEST['fdate_date'])));
else if (isset($_SESSION['beds24-checkin']))
	{
	$checkin = sanitize_text_field($_SESSION['beds24-checkin']);
	}
else if (isset($atts['advancedays']))
	{
	$advancedays = intval($atts['advancedays']);
	if ($advancedays>=0)
		$checkin = date('Y-m-d', strtotime('+'.$advancedays.' days'));
	}
else
	{
	$advancedays = intval(get_option('beds24_advancedays'));
	if ($advancedays>=0)
		$checkin = date('Y-m-d', strtotime('+'.$advancedays.' days'));
	}

$urlcheckin = '';
if ($checkin)
	{
	$checkin = date('Y-m-d', strtotime(sanitize_text_field($checkin)));
	if ($checkin < date('Y-m-d'))
		$checkin = date('Y-m-d');
	$_SESSION['beds24-checkin'] = $checkin;
	if (!isset($atts['noselection']))
		$urlcheckin = "&amp;checkin=".urlencode($checkin);
	}

//default number of nights
if (isset($_REQUEST['numnight']))
	$numnight = intval($_REQUEST['numnight']);
else if (isset($_SESSION['beds24-numnight']))
	$numnight = intval($_SESSION['beds24-numnight']);
else if (isset($atts['numnight']))
	$numnight = intval($atts['numnight']);
else
	$numnight = intval(get_option('beds24_numnight'));
$_SESSION['beds24-numnight'] = $numnight;
if (isset($numnight) && !isset($atts['noselection']))
	$urlnumnight = "&amp;numnight=".urlencode(intval($numnight));
else
	$urlnumnight = '';

//number of guests
if (isset($_REQUEST['numadult']))
	$numadult = intval($_REQUEST['numadult']);
else if (isset($_SESSION['beds24-numadult']))
	$numadult = intval($_SESSION['beds24-numadult']);
else if (isset($atts['numadult']))
	$numadult = intval($atts['numadult']);
else
	$numadult = intval(get_option('beds24_numadult'));
    $_SESSION['beds24-numadult'] = $numadult;
if (isset($numadult) && !isset($atts['noselection']))
	$urlnumadult = "&amp;numadult=".urlencode(intval($numadult));
else
	$urlnumadult = '';

if (isset($_REQUEST['numchild']))
	$numchild = intval($_REQUEST['numchild']);
else if (isset($_SESSION['beds24-numchild']))
	$numchild = intval($_SESSION['beds24-numchild']);
else if (isset($atts['numchild']))
	$numchild = intval($atts['numchild']);
else
	$numchild = intval(get_option('beds24_numchild'));
    $_SESSION['beds24-numchild'] = $numchild;
if (isset($numchild) && !isset($atts['noselection']))
	$urlnumchild = "&amp;numchild=".urlencode(intval($numchild));
else
	$urlnumchild = '';



//default layout
if (isset($_REQUEST['layout']))
	$layout = intval($_REQUEST['layout']);
else if (isset($atts['layout']))
	$layout = intval($atts['layout']);
else
	$layout = intval(get_option('beds24_layout'));
if (isset($layout) && $layout>0)
	$urllayout = "&amp;layout=".urlencode(intval($layout));
else
	$urllayout = '';

//width of target
$width = false;
if (isset($atts['width']))
	{
	$width = intval($atts['width']);
	}
else
	$width = intval(get_option('beds24_width'));

if ($width<100)
	$width = 800;

//height of target
if (isset($atts['height']))
	$height = intval($atts['height']);
else
	$height = intval(get_option('beds24_height'));

if ($height<100)
	$height = 1600;

//type=link
//type=button
//type=box
//type=strip
//type=searchbox
//type=searchresult
//type=embed
if (isset($atts['type']))
	$type = sanitize_text_field($atts['type']);
else
	$type = 'embed';
//	$type = get_option('beds24_type');

//target=iframe
//target=window
//target=new
if (isset($atts['target']))
	$target = sanitize_text_field($atts['target']);
else if (get_option('beds24_target'))
	$target = sanitize_text_field(get_option('beds24_target'));
else
	$target = 'window';



if (isset($atts['display']))
	$display = sanitize_text_field($atts['display']);
else
	$display = '';

$suffix = '_'.$ownerid.'_'.$propid.'_'.$roomid;
if (isset($atts['targetid']))
	{
	$targetid = sanitize_text_field($atts['targetid']);
//	$target = 'none';
	}
else if (isset($atts['id']))
	{
	$targetid = sanitize_text_field($atts['id']);
	}
else
	$targetid = 'beds24target'.$suffix;

//widget text
if (isset($atts['text']))
	$text = sanitize_text_field(htmlspecialchars($atts['text']));
else
	$text = 'Book Now';

//widget class
if (isset($atts['class']))
	$class = sanitize_text_field(htmlspecialchars($atts['class']));
else
	$class = '';

//target url custom parameters
if (isset($atts['custom']))
	$custom = sanitize_text_field($atts['custom']);
else
	$custom = sanitize_text_field(get_option('beds24_custom'));
if (substr($custom,0,1) != '&')
	$custom = '&amp;'.$custom;

$style = 'cursor: pointer;';

//widget font size
if (isset($atts['fontsize']))
	$style .= 'font-size: '.esc_attr($atts['fontsize']).';';

//widget color
if (isset($atts['color']))
	$style .= 'color: '.esc_attr($atts['color']).';';
elseif (strlen(get_option('beds24_color')) >=3)
	$style .= 'color: '.esc_attr(get_option('beds24_color')).';';

//padding
if (isset($atts['padding']))
	$style .= 'padding: '.intval($atts['padding']).'px;';
else
	$style .= 'padding: '.intval(get_option('beds24_padding')).'px;';

$linkstyle = $style;

//widget background color
if (isset($atts['bgcolor']))
	$style .= 'background-color: '.esc_attr($atts['bgcolor']).';';
elseif (strlen(get_option('beds24_bgcolor')) >=3)
	$style .= 'background-color: '.esc_attr(get_option('beds24_bgcolor')).';';

$boxstyle = $style;
$buttonstyle = $style;

if (isset($atts['width']))
	$boxstyle .= 'max-width: '.esc_attr($atts['width']).'px;';

$defaulthref = esc_url($domain.'/booking2.php');

//href target
if (isset($atts['href']))
	$href = esc_url($atts['href']);
else
	$href = $defaulthref;

$formurl = $href;
$url = $href.'?1'.esc_attr($owner).esc_attr($prop).esc_attr($room).esc_attr($urlnumdisplayed).esc_attr($urlhideheader).esc_attr($urlhidefooter).esc_attr($urlhidecalendar).esc_attr($urlcheckin).esc_attr($urlnumnight).esc_attr($urlnumadult).esc_attr($urlnumchild).esc_attr($urllayout).esc_attr($urllang).esc_attr($urlreferer).esc_attr($custom);

include ('beds24-translations.php');

$output = '';
$thistarget = '';
$onclick = '';
$linkclass = '';

if ($target == 'window')
	{
	if ($type != 'box' && $type != 'strip')
		{
		if ($formurl == $defaulthref) //stay on same page
		$formurl = '';
		}
	}
else if ($target == 'new')
	{
	$thistarget = ' target="_blank" ';
	}
else //iframe
	{
	if ($target != 'none')
		$target = 'iframe';
        $onclick = 'onclick="jQuery(\'#' . esc_js($targetid) . '\').show();jQuery(\'#beds24book' . esc_js($suffix) . '\').hide();return false;"';
        $onclick = esc_attr($onclick);
	if ($type != 'embed')
		$display = 'none';
	}

if ($type == 'link')
	{
        $output .= '<a ' . esc_attr($thistarget) .
            ' class="' . esc_attr($linkclass . $class) . '" ' .
            'id="beds24book' . esc_attr($suffix) . '" ' .
            'style="' . esc_attr($linkstyle) . '" ' .
            'href="' . esc_url($url) . '" ' .
            esc_attr($onclick) . '>';
        $output .= esc_html($text);
        $output .= '</a>';
	}
else if ($type == 'button')
	{
        $output .= '<a ' . esc_attr($thistarget) .
            ' class="' . esc_attr($linkclass) . '" ' .
            'id="beds24book' . esc_attr($suffix) . '" ' .
            'style="text-decoration:none;" ' .
            'href="' . esc_url($url) . '" ' .
            esc_attr($onclick) . '>';
        $output .= '<button class="' . esc_attr($class) . '" ' .
            'style="' . esc_attr($buttonstyle) . '">' .
            esc_html($text) . '</button></a>';
	}
else if ($type == 'box' || $type == 'strip' || $type == 'searchbox' || $type == 'searchresult')
	{
	$searchbox = false;
	if ($type == 'box' || $type == 'strip' || $type == 'searchbox')
	{
	if ($type == 'box')
		{
		if ($lang)
			{
			ob_start();
			get_template_part('beds24-box-'.sanitize_text_field($lang));
			$searchbox = ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			get_template_part('beds24-box');
			$searchbox = ob_get_clean();
			}
		$file =  plugin_dir_path( __FILE__ ) . 'theme-files/beds24-box-'.sanitize_text_field($lang).'.php';
		if (!$searchbox && $lang && file_exists($file))
			{
			ob_start();
			include($file);
			$searchbox .= ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-box.php');
			$searchbox .= ob_get_clean();
			}
		}
	else if ($type == 'strip')
		{
		if ($lang)
			{
			ob_start();
			get_template_part('beds24-strip-'.sanitize_text_field($lang));
			$searchbox = ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			get_template_part('beds24-strip');
			$searchbox = ob_get_clean();
			}
		$file =  plugin_dir_path( __FILE__ ) . 'theme-files/beds24-strip-'.sanitize_text_field($lang).'.php';
		if (!$searchbox && $lang && file_exists($file))
			{
			ob_start();
			include($file);
			$searchbox .= ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-strip.php');
			$searchbox .= ob_get_clean();
			}
		}
	else if($type == 'searchbox')
		{
			if ($lang)
			{
			ob_start();
			get_template_part('beds24-searchbox-'.sanitize_text_field($lang));
			$searchbox = ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			get_template_part('beds24-searchbox');
			$searchbox = ob_get_clean();
			}
		$file = plugin_dir_path( __FILE__ ) . 'theme-files/beds24-searchbox-'.sanitize_text_field($lang).'.php';
		if (!$searchbox && $lang && file_exists($file))
		{
		ob_start();
		include($file);
		$searchbox .= ob_get_clean();
		}
		if (!$searchbox)
		{
		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-searchbox.php');
		$searchbox .= ob_get_clean();
		}
		}


        $output .= '<div id="beds24' . esc_attr($type . $suffix) . '" ' .
            'style="' . esc_attr($boxstyle) . '" ' .
            'class="beds24' . esc_attr($type) . '">';
        $output .= '<form ' . esc_attr($thistarget) . ' ' .
            'id="beds24book' . esc_attr($suffix) . '" ' .
            'method="post" ' .
            'action="' . esc_url($formurl) . '">';
	if ($ownerid > 0)
        $output .= '<input type="hidden" name="ownerid" value="' . esc_attr($ownerid) . '">';

        if ($propid > 0)
		$output .= '<input type="hidden" name="propid" value="'.esc_attr($propid).'">';
	if ($roomid > 0)
		$output .= '<input type="hidden" name="roomid" value="'.esc_attr($roomid).'">';
	if (isset($numdisplayed) && $numdisplayed!=-1)
		$output .= '<input type="hidden" name="numdisplayed" value="'.esc_attr($numdisplayed).'">';
	if (isset($hidecalendar) && $hidecalendar!=-1)
		$output .= '<input type="hidden" name="hidecalendar" value="'.esc_attr($hidecalendar).'">';
	if (isset($hideheader) && $hideheader!=-1)
		$output .= '<input type="hidden" name="hideheader" value="'.esc_attr($hideheader).'">';
	if (isset($hidefooter) && $hidefooter!=-1)
		$output .= '<input type="hidden" name="hidefooter" value="'.esc_attr($hidefooter).'">';
	if ($lang)
		$output .= '<input type="hidden" name="lang" value="'.esc_attr($lang).'">';
	if ($referer)
		$output .= '<input type="hidden" name="referer" value="'.esc_attr($referer).'">';


	$output .= $searchbox;
	$output .= '</form>';
	$output .= '</div>';

	$output .= '<script>jQuery(document).ready(function($) {';

	if (isset($_REQUEST['showmoredetails']) && $_REQUEST['showmoredetails']>0)
		$output .= '';
	else
		$output .= '$("#B24advancedsearch").hide();';

	$output .= '});</script>';
	}


	if($type == 'box')
	{

	}
	if($type == 'searchresult')
	{
	if (isset($_REQUEST['fdate_date']))
		{
		$xmlurl = esc_url_raw('https://api.beds24.com/getavailabilities.xml');

        $ownerid = sanitize_text_field($ownerid);
        $checkin = sanitize_text_field($checkin);
        $numnight = intval($numnight);
        $numadult = intval($numadult);
        $numchild = intval($numchild);
		$postarray = array( 'ownerid' => $ownerid, 'checkin' => $checkin, 'numnight' => $numnight, 'numadult' => $numadult , 'numchild' => $numchild );

		$category = array();
		foreach ($_REQUEST as $key => $val)
			{
			if (substr($key,0,8) == 'category')
				{
				$cat = substr($key,8,1);
				if ($cat>=1 && $cat<=4)
					{
					if (strlen($key)>9)
						$val = substr($key,10);
					$val = intval($val);
					if ($val > 0)
						{
						if (isset($category[$cat]))
							$category[$cat] .= ',';
						$category[$cat] .= $val;
						}
					}
				}
			}

		foreach ($category as $key => $val)
			{
			$postarray['category'.$key] = intval($val);
			}

		$args = array(
			'method' => 'POST',
			'timeout' => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => $postarray,
			'cookies' => array()
			);
		$response = wp_remote_post($xmlurl, $args);

		if ( is_wp_error( $response ) ) {
			$error_message = esc_html($response->get_error_message());
			$output .=  "Something went wrong: $error_message";
			return $output;
		} else {
			$result = new SimpleXMLElement($response['body']);
			$xmlowner = $result->owner;
            if (!isset($xmlowner->property) || !$xmlowner->property || !is_object($xmlowner) || count($xmlowner->property)==0)
                {
                $output .= '<p class="b24_message">No results found</p>';
                }
			else
			{
			foreach ($xmlowner->property as $xmlproperty)
				{
				$propid = intval($xmlproperty['id']);
				$name = sanitize_text_field($xmlproperty['name']);
				$bestprice = sanitize_text_field($xmlproperty['bestPrice']);
				$bookurl = esc_url($domain.'/booking2.php?propid='.$propid.'&amp;checkin='.$checkin.'&amp;numadult='.$numadult.'&amp;numchild='.$numchild.'&amp;numnight='.$numnight.$urlnumdisplayed.$urlhideheader.$urlhidefooter.$urlhidecalendar.$urlcheckin.$urllang.$urlreferer.$custom);
				$propoutput = false;

				$args = array('meta_key' => 'propid', 'meta_value'=> $propid);
				$mypropposts = get_posts( $args );
				foreach ($mypropposts as $post)
					{
					$postoutput = false;
					setup_postdata($post);
					if ($lang)
						{
						ob_start();
						include(locate_template('beds24-prop-post-'.sanitize_text_field($lang)));
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						include(locate_template('beds24-prop-post'));
						$postoutput = ob_get_clean();
						}
					$file = plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-post-'.sanitize_text_field($lang).'.php';
					if (!$postoutput && $lang && file_exists($file))
						{
						ob_start();
						include($file);
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-post.php');
						$postoutput = ob_get_clean();
						}
					$propoutput .= $postoutput;
					}

				if (!$propoutput)
					{
					if ($lang)
						{
						ob_start();
						get_template_part('beds24-prop-xml-'.sanitize_text_field($lang));
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						get_template_part('beds24-prop-xml');
						$postoutput = ob_get_clean();
						}
					$file =  plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-xml-'.sanitize_text_field($lang).'.php';
					if (!$postoutput && $lang && file_exists($file))
						{
						ob_start();
						include($file);
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-xml.php');
						$postoutput = ob_get_clean();
						}
					$propoutput .= $postoutput;
					}
				$output .= $propoutput;
				}
			}
			}
		}
	}
	}//end box

if ($type=='embed' || $target=='iframe') //iframe
	{
	$output .= '<div id="'.esc_attr($targetid).'">';
	if ($scrolltop == 'no')
		$output .= '<iframe src ="'.esc_url($url).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" style="max-width:100%;border:none;overflow:auto;"><p><a href="'.esc_url($url).'">'.esc_html($text).'</a></p></iframe>';
	else
		$output .= '<iframe onload="window.parent.parent.scrollTo(0,0)" src ="'.esc_url($url).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" style="max-width:100%;border:none;overflow:auto;"><p><a href="'.esc_url($url).'">'.esc_html($text).'</a></p></iframe>';
	$output .= '</div>';
	if ($display == 'none')
		{
		$output .= '<script>jQuery(document).ready(function($) {jQuery("#'.esc_js($targetid).'").hide(); });</script>';
		}
	}

return $output;
}

include_once('inc/plugin-options/beds24-options-page.php');
