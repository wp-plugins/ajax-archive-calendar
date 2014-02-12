<?php
/**
 * Plugin Name: Ajax Archive Calendar
 * Plugin URI: http://projapotibd.com
 * Description:A widget that List all recent post.
 * Version: 1.00
 * Author: osmansorkar
 * Author URI: http://www.projapotibd.com
 *
 * 
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'ajax_ac_int' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function ajax_ac_int() {
	register_widget( 'ajax_ac_widget' );
}
/***********************************************************/

class ajax_ac_widget extends WP_Widget {
	
function __construct() {
	
		parent::__construct(
	 		'ajax_ac_widget', // Base ID
			'Ajax Archive calendar', // Name
			array( 'description' =>'It is Ajax Archive Calendar') // Args
		);
	}
	

/**
/********************** It will be sow home page*****************/
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$np = $instance['np'];


		/* Before widget (defined by themes). */
		echo $before_widget;
		
		/* $title define by from */
		if ( $title )
		/* after title and before title defince by thime */
			echo $before_title . $title .$after_title;
			/* end title */
		/* now start your degine */
		?>
        <div id="ajax_ac_widget">
     <div class="select_ca">
     <select name="month" id="my_month" >
<?php $month=array(
	'01'=>'January',
	'02'=>'February',
	'03'=>'March',
	'04'=>'April',
	'05'=>'May',
	'06'=>'Jun',
	'07'=>'July',
	'08'=>'August',
	'09'=>'Septembar',
	'10'=>'Octobor',
	'11'=>'Novembar',
	'12'=>'Decembar',
	);
	
global $m;
if(empty($m) || $m==''){
	$nowm=date(m);
	$nowyear=date(Y);
	}
else {
	$mmm = str_split($m, 2);
	$nowm=$mmm['2'];
	$nowyear=$mmm['0'].$mmm['1'];
	}

	
foreach($month as $k=>$mu){
	if($k==$nowm){
	echo '<option value="'.$k.'" selected="selected" >'.$mu.'</option>';
	}
	else{
	echo '<option value="'.$k.'">'.$mu.'</option>';	
		}
	}	
	
	?>
     </select>
  
  <?php 
  $taryear=$nowyear+5;
  $yeararr=array();
  $lassyear=$nowyear-5;
  for($nowyearrr=$lassyear;$nowyearrr<=$taryear;$nowyearrr++){
	  $yeararr[$nowyearrr]=$nowyearrr;	  	  
  }
     ?> 
   
      <select name="Year" id="my_year" >
   <?php  foreach($yeararr as $k=>$year){
	   
	   if($k==$nowyear){
	echo '<option value="'.$k.'" selected="selected" >'.$year.'</option>';
	}
	else{
	echo '<option value="'.$k.'">'.$year.'</option>';	
		}
		
	   } ?>

     </select>
     </div><!--select ca -->
     <div class="clear" style="clear:both; margin-bottom: 5px;"></div>
        <div id="my_calender">
        </div><!--my_calender -->
<script type="text/javascript" >
jQuery('#my_month').change(function(e) {
    var mon=jQuery(this).val();
	var year=jQuery('#my_year').val();
	var to=year+mon;
	
	var data = {
		action: 'ajax_ac',
		ma: to
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.get(ajaxurl, data, function(response) {
		jQuery("#my_calender").html(response);
	});
	
});
jQuery(document).ready(function(e) {
    
});
</script>
<script type="text/javascript" >
jQuery('#my_year').change(function(e) {
    var mon=jQuery('#my_month').val();
	var year=jQuery('#my_year').val();
	var to=year+mon;
	
	var data = {
		action: 'ajax_ac',
		ma: to
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.get(ajaxurl, data, function(response) {
		jQuery("#my_calender").html(response);
	});
	
});
jQuery(document).ready(function(e) {
    
});
</script>

<script type="text/javascript" >


jQuery(document).ready(function(e) {
	<?php if(!isset($_GET['m'])){ echo 'var a='.date(Ym);} else echo 'var a='.$_GET['m'];  ?>
	
 	var data = {
		action: 'ajax_ac',
		ma:a
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.get(ajaxurl, data, function(response) {
		jQuery("#my_calender").html(response);
	});   
});
</script>
     
        
       </div>

<?php
		
	
		/* now end your degine
		
		/*arter_widget define by thimed*/
		echo $after_widget;
	}
	
/******************** It Update widget *******************************/
function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
/************************ It is sow only admin menu**********************************/
	
	function form( $instance ) {
		$defaults = array( 'title' => 'Archive Calendar');
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
   <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'Recent post'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        
         		
		<?php
	}// end from function
}// end widget class


add_action( 'wp_ajax_ajax_ac', 'ajax_ac_callback' );
add_action( 'wp_ajax_nopriv_ajax_ac', 'ajax_ac_callback' );

function ajax_ac_callback() {

function ajax_ac_calendar($ma,$initial = true, $echo = true) {
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
	$m=& $ma;
	$cache = array();
	$key = md5( $m . $monthnum . $year );
	
	if ( $cache = wp_cache_get( 'get_calendar', 'calendar' ) ) {
		if ( is_array($cache) && isset( $cache[ $key ] ) ) {
			if ( $echo ) {
				echo apply_filters( 'get_calendar',  $cache[$key] );
				return;
			} else {
				return apply_filters( 'get_calendar',  $cache[$key] );
			}
		}
	}

	if ( !is_array($cache) )
		$cache = array();

	// Quick check. If we have no posts at all, abort!
	if ( !$posts ) {
		$gotsome = $wpdb->get_var("SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' LIMIT 1");
		if ( !$gotsome ) {
			$cache[ $key ] = '';
			wp_cache_set( 'get_calendar', $cache, 'calendar' );
			return;
		}
	}

	if ( isset($_GET['w']) )
		$w = ''.intval($_GET['w']);

	// week_begins = 0 stands for Sunday
	$week_begins = intval(get_option('start_of_week'));

	// Let's figure out when we are
	if ( !empty($monthnum) && !empty($year) ) {
		$thismonth = ''.zeroise(intval($monthnum), 2);
		$thisyear = ''.intval($year);
	} elseif ( !empty($w) ) {
		// We need to get the month from MySQL
		$thisyear = ''.intval(substr($m, 0, 4));
		$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
		$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
	} elseif ( !empty($m) ) {
		$thisyear = ''.intval(substr($m, 0, 4));
		if ( strlen($m) < 6 )
				$thismonth = '01';
		else
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
	} else {
		$thisyear = gmdate('Y', current_time('timestamp'));
		$thismonth = gmdate('m', current_time('timestamp'));
	}

	$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
	$last_day = date('t', $unixmonth);

	// Get the next and previous month and year with at least one post
	$previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
		FROM $wpdb->posts
		WHERE post_date < '$thisyear-$thismonth-01'
		AND post_type = 'post' AND post_status = 'publish'
			ORDER BY post_date DESC
			LIMIT 1");
	$next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
		FROM $wpdb->posts
		WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
		AND post_type = 'post' AND post_status = 'publish'
			ORDER BY post_date ASC
			LIMIT 1");

	/* translators: Calendar caption: 1: month name, 2: 4-digit year */
	$calendar_caption = _x('%1$s %2$s', 'calendar caption');
	$calendar_output = '<table id="my-calendar">
	<!-- <caption>' . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . '</caption> --->
	<thead>
	<tr>';

	$myweek = array();

	for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
	}

	foreach ( $myweek as $wd ) {
		$day_name = (true == $initial) ? $wp_locale->get_weekday_abbrev($wd) : $wp_locale->get_weekday_abbrev($wd);
		$wd = esc_attr($wd);
		$calendar_output .= "\n\t\t<th class=\"$day_name\" scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	$calendar_output .= '
	</tr>
	</thead>
<!-----
	<tfoot>
	<tr>';

	if ( $previous ) {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="prev"><a href="' . get_month_link($previous->year, $previous->month) . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($previous->month), date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year)))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
	} else {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	$calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';

	if ( $next ) {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="next"><a href="' . get_month_link($next->year, $next->month) . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($next->month), date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) ) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
	} else {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}

	$calendar_output .= '
	</tr>
	</tfoot> -------->

	<tbody>
	<tr>';

	// Get days with posts
	$dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH(post_date)
		FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
		AND post_type = 'post' AND post_status = 'publish'
		AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N);
	if ( $dayswithposts ) {
		foreach ( (array) $dayswithposts as $daywith ) {
			$daywithpost[] = $daywith[0];
		}
	} else {
		$daywithpost = array();
	}

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'camino') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false)
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$ak_titles_for_day = array();
	$ak_post_titles = $wpdb->get_results("SELECT ID, post_title, DAYOFMONTH(post_date) as dom "
		."FROM $wpdb->posts "
		."WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' "
		."AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59' "
		."AND post_type = 'post' AND post_status = 'publish'"
	);
	if ( $ak_post_titles ) {
		foreach ( (array) $ak_post_titles as $ak_post_title ) {

				/** This filter is documented in wp-includes/post-template.php */
				$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );

				if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
					$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
		}
	}

	// See how much we should pad in the beginning
	$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
	if ( 0 != $pad )
		$calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

	$daysinmonth = intval(date('t', $unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( isset($newrow) && $newrow )
			$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
		$newrow = false;



		if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
		
			$calendar_output .= '<td id="today"  >';
		else
			$calendar_output .= '<td class="notday">';

		if ( in_array($day, $daywithpost) ) // any posts today?
				$calendar_output .= '<a class="has-post" href="' . get_day_link( $thisyear, $thismonth, $day ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
		else
			$calendar_output .='<span class="notpost">'.$day.'</span>';
		$calendar_output .= '</td>';

		if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
	if ( $pad != 0 && $pad != 7 )
		$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

	$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

	$cache[ $key ] = $calendar_output;
	wp_cache_set( 'get_calendar', $cache, 'calendar' );

	if ( $echo )
		echo apply_filters( 'get_calendar',  $calendar_output );
	else
		return apply_filters( 'get_calendar',  $calendar_output );

}

$ma=$_GET['ma'];
ajax_ac_calendar($ma);
	die(); // this is required to return a proper result
}

function ajax_ac_head(){?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<style type="text/css">

#ajax_ac_widget th {
    background: none repeat scroll 0 0 #008000;
    color: #FFFFFF;
    font-weight: normal;
    padding: 5px 1px;
    text-align: center;
	 font-size: 16px;
}
#ajax_ac_widget {
    padding: 5px;
}

#ajax_ac_widget td {
    border: 1px solid #CCCCCC;
    text-align: center;
}

#my-calendar a {
    background: none repeat scroll 0 0 #008000;
    color: #FFFFFF;
    display: block;
    padding: 6px 0;
    width: 100% !important;
	border-radius:20px;
}
#my-calendar{
	width:100%;
}


#my_calender span {
    display: block;
    padding: 6px 0;
    width: 100% !important;
	background-color:#999;
	border-radius:20px;
}

#today a {
       background: none repeat scroll 0 0 #FF0000 !important;
    color: #FFFFFF;
	border-radius:20px;
}
#ajax_ac_widget #my_year {
    float: right;
}
.select_ca #my_month {
    float: left;
}
</style>
<?php	
	}
	add_filter('wp_head',ajax_ac_head)
?>