<?php
/*
Plugin Name: WPR General Posts
Plugin URI: http://worldpressrevolution.com/wpr_myplugins/wpr-general-posts-widget/
Description: Full wp_query parameters for a post listing widget.  Completely customize a post/post_type list in the widget area.  Used for latest posts, popular posts, posts by taxonomy or category, posts by meta query, whatever.
Version: 1.0.0
Author: Aryan Duntley
Author URI: http://worldpressrevolution.com
License: GPLv2 or later
*/
class GardenGeneralPosts extends WP_Widget {

	function GardenGeneralPosts() {
		// Instantiate the parent object
		parent::__construct( false, 'General Posts' );
	}

	function widget( $args, $instance ) {
		// Widget output on the frontend
		extract( $args, EXTR_SKIP );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $post_amount = $instance['show'];
		$post_orderby = $instance['orderby'];
		$post_order = $instance['order'];
		$post_catin = $instance['catin'];
		$post_catout = $instance['catout'];
		$pagecount = $instance['pagecount'];
		$post_taxis = $instance['taxis'];
		$post_taxterm = $instance['taxterm'];
		$post_typed = $instance['ptipe'];
		$post_metakey = $instance['metakey'];
		$post_metavalue = $instance['metavalue'];
		$post_comparison = $instance['metacompare'];
		$post_widgeid = $instance['widgetidentifier'];
		$post_widgeclass = $instance['widgetclassifier'];
		$post_readmoretitle = $instance['readmoretitle'];
		$post_readmorelink = $instance['readmorelink'];
        //$term = $instance['term'];


		if(!$post_typed){$post_typed = 'post';}
		if(!$post_comparison){$post_comparison = '=';}
        // getting the posts we want
		
		$cpage = get_query_var('paged')?get_query_var('paged'):0;
		if(!isset($cpage) || $cpage == "" || $cpage === 0){
			$cpage = get_query_var('page')?get_query_var('page'):1;
		}
		
        $qargs = array(
          'post_type'         => $post_typed,
          'posts_per_page'    => $post_amount,
		  'post_status'       => 'publish',
		  'paged'			  => $cpage
        );
		if($post_catin && !$post_catout){
			$catin = explode(",", $post_catin);
			$qargs['category__in'] = $catin;
		}
		if($post_catout && !$post_catin){
			$catout = explode(",", $post_catout);
			$qargs['category__not_in'] = $catout;
		}
		if($post_taxis && $post_taxterm){
			$taxray = explode(",", $post_taxterm);
			$qargs['tax_query'] = array(
				array(
				'taxonomy'  => $post_taxis,
				'field'     => 'slug',
				'terms'     => $taxray,
				)
			);
		}
		if($post_metakey && $post_metavalue){
			$qargs['meta_query'] = array(
				array(
					'key'     => $post_metakey,
					'value'   => $post_metavalue,
					'compare' => $post_comparison,
				),
			);
		}
		if($post_orderby){
			$qargs['orderby'] = $post_orderby;
		}
		if($post_order){
			$qargs['order'] = $post_order;
		}

		$qargs = apply_filters('wpr_adjust_genposts_query', $qargs, $args, $instance);
        $postsQ = new WP_Query($qargs);//get_posts
		
		$maxpages = $postsQ->max_num_pages;
		$totalfound = $postsQ->found_posts;
		
        echo $before_widget;		
			
			$makeid = '';
			$makeclass = '';
			if($post_widgeid){$makeid = 'id="' . $post_widgeid . '"';}
			if($post_widgeclass){$makeclass = 'id="' . $makeclass . '"';}
			$openprint = '<div ' . $makeid . ' ' . $makeclass . '>';
            if(!empty($title)){
				$fintitle = $before_title . $title . $after_title;
				$fintitle =  apply_filters('wpr_genposts_titlefilter', $fintitle, $before_title, $title, $after_title, $instance);
				$openprint .= $fintitle;
			};			
			
			//if($post_amount > $pagecount){
			//	echo '<div class="content-pages-widget">'.'<a href="/" class="widget-prev-page current-page button-prev"><</a><a href="/" class="widget-next-page button-next">></a>'.'</div>';
			//}
			
			$toprint = '';
            $openprint .= '<ul class="list-post-widget-home">';
			
			$count = 1;			
			
			if($postsQ->have_posts()){
				while($postsQ->have_posts()){ $postsQ->the_post(); global $post; //foreach( $postsQ as $post ){
					//if ($count == 1) {
					//	echo '<div class="page-1">';
					//}
					
					$thisprint = '<li id="postwidg' . $post->ID . '" class="widget-area-imbed">';				
					$thisprint .= '<a href="'.get_permalink( $post->ID ).'" title="'.get_the_title( $post->ID ).'">'.get_the_post_thumbnail($post->ID, 'thumbnail', array('class' => 'middle_img_radius_10')).'</a>';
					$thisprint .= '<a class="genposts_linktitle" href="'.get_permalink( $post->ID ).'" title="'.get_the_title( $post->ID ).'">'.get_the_title( $post->ID ).'</a>';
					$thisprint .= '</li>';
					
					$toprint .= apply_filters('wpr_genposts_listloop', $thisprint, $postsQ->found_posts, $post, $count, $instance);
					//if ($count == $pagecount && $post_amount > $pagecount) {
					//	echo '</div><div class="page-2">';
					//}
					//if ($count == ($pagecount * 2) || $count == $post_amount) {
					//	echo '</div>';break;
					//}
					$count++;
				}wp_reset_postdata();
			}
			$readingon = '';
			$extern = '';
			if($post_readmoretitle && $post_readmorelink){
				if(strpos($post_readmorelink, "http://") !== false || strpos($post_readmorelink, "https://") !== false){
					if(strpos($post_readmorelink, home_url()) === false){$extern = 'target="_blank"';}
					$readingon = '<a class="wpr_genpost_readmore" href="' . $post_readmorelink . '" ' . $extern . '>' . $post_readmoretitle . '</a>';
				}
				else{
					//$p = parse_url($post_readmorelink);
					if($post_readmorelink[0] ==  '/' || $post_readmorelink[0] ==  '\\'){$post_readmorelink = substr($post_readmorelink, 1);}
					$readingon = '<a class="wpr_genpost_readmore" href="' . home_url() . "/" . $post_readmorelink . '">' . $post_readmoretitle . '</a>';
				}
			}
            $closeprint = '</ul>';
			$closeprint .= apply_filters('wpr_genposts_addtoend', $readingon, $instance);
			$closeprint .= '</div>';
			
			$finalprint = apply_filters('wpr_genposts_list_print', $openprint . $toprint . $closeprint, $openprint, $toprint, $closeprint, $instance, $postsQ);
			echo $finalprint;
			
        echo $after_widget;	
	

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']	= strip_tags( $new_instance['title'] );
		$instance['show']	= strip_tags( $new_instance['show'] );
		$instance['orderby']	= strip_tags( $new_instance['orderby'] );
		$instance['order']	= strip_tags( $new_instance['order'] );
		$instance['catin']	= strip_tags( $new_instance['catin'] );
		$instance['catout']	= strip_tags( $new_instance['catout'] );
		$instance['pagecount'] = strip_tags( $new_instance['pagecount']);
		$instance['taxis'] = strip_tags( $new_instance['taxis']);
		$instance['taxterm'] = strip_tags( $new_instance['taxterm']);
		$instance['ptipe'] = strip_tags( $new_instance['ptipe']);
		$instance['metakey'] = strip_tags( $new_instance['metakey']);
		$instance['metavalue'] = strip_tags( $new_instance['metavalue']);
		$instance['metacompare'] = strip_tags( $new_instance['metacompare']);
		$instance['widgetidentifier'] = strip_tags( $new_instance['widgetidentifier']);
		$instance['widgetclassifier'] = strip_tags( $new_instance['widgetclassifier']);
		$instance['readmoretitle'] = $new_instance['readmoretitle'];
		$instance['readmorelink'] = strip_tags( $new_instance['readmorelink']);
		//$instance['term']	= absint( $new_instance['term'] );
		return $instance;
	}

	function form( $instance ) {
	// outputs the options form on admin
		$defaults = array( 'title' => 'General Posts', 'show' => '3', 'orderby'=> 'date', 'order'=>'DESC', 'catin' => '', 'catout' => '', 'pagecount' => '3', 'taxis' => '', 'taxterm' => '', 'ptipe' => 'post', 'metakey'=> '', 'metavalue' => '', 'metacompare' => '=', 'widgetidentifier' => '', 'widgetclassifier' => '', 'readmoretitle' => '', 'readmorelink' => '');//'term' => ' ', 
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		$show  = $instance['show'];
		$orderby  = $instance['orderby'];
		$order  = $instance['order'];
		$post_catin = $instance['catin'];
		$post_catout = $instance['catout'];
		$pagecount = $instance['pagecount'];
		$post_taxis = $instance['taxis'];
		$post_taxterm = $instance['taxterm'];
		$post_typed = $instance['ptipe'];
		$post_metakey = $instance['metakey'];
		$post_metavalue = $instance['metavalue'];
		$post_comparison = $instance['metacompare'];
		$post_widgeid = $instance['widgetidentifier'];
		$post_widgeclass = $instance['widgetclassifier'];
		$post_readmoretitle = $instance['readmoretitle'];
		$post_readmorelink = $instance['readmorelink'];
		//$term  = $instance['term'];

        // get the parent term
        //$season = get_term_by( 'slug', 'seasonal', 'featured' );

		$orbe = array('none', 'ID', 'author', 'title', 'name', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num');
		$metcompare = array( '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'EXISTS', 'NOT EXISTS');
		
		?>

		<p>Title <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" /></p>
		
		<p>ID Tag <input class="widefat" name="<?php echo $this->get_field_name( 'widgetidentifier' ); ?>" value="<?php echo esc_attr($post_widgeid); ?>" /></p>
		
		<p>Class Tag <input class="widefat" name="<?php echo $this->get_field_name( 'widgetclassifier' ); ?>" value="<?php echo esc_attr($post_widgeclass); ?>" /></p>
		
		<p>Choose post type: 	
			<select name="<?php echo $this->get_field_name('ptipe'); ?>"><?php
		
			$datype = get_post_types(array('public'=>true), 'objects'); 
			foreach($datype as $atipe){
				?>
					<option value="<?php echo $atipe->name; ?>" <?php if($atipe->name == $post_typed){echo "selected";} ?>><?php echo $atipe->label; ?></option>
				<?php
			}
			?>
			</select>
		</p>
		
		
		<p>How many Articles to show total. Defaults to 3. <input class="widefat" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo esc_attr( $show ); ?>" /></p>
		<p>How many artles to show at once. Defaults to 3 (note: this is not used.  It is available for you to hook into in order to separate display into tabs or whatever). <input class="widefat" name="<?php echo $this->get_field_name( 'pagecount' ); ?>" value="<?php echo esc_attr( $pagecount ); ?>" /></p>
        <p>Order By
		
            <select name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                <?php
                foreach( $orbe as $orb ){
                ?>
                    <option value="<?php echo $orb ?>" <?php selected( $orderby, $orb); ?>><?php echo $orb; ?></option>
                <?php } ?>
            </select>
        </p>
		
		<p>Order
		
            <select name="<?php echo $this->get_field_name( 'order' ); ?>">
                    <option value="ASC" <?php selected( $order, 'ASC'); ?>>Ascending</option>
					<option value="DESC" <?php selected( $order, 'DESC'); ?>>Descending</option>
             </select>
        </p>
		<p>USE ONLY ONE OPTION BELOW</p>
		<p>Category Includes <small>(category id's, comma delimited)</small> <input class="widefat" name="<?php echo $this->get_field_name( 'catin' ); ?>" value="<?php echo esc_attr( $post_catin ); ?>" /></p>
		
		<p>Category Excludes <small>(category id's, comma delimited)</small> <input class="widefat" name="<?php echo $this->get_field_name( 'catout' ); ?>" value="<?php echo esc_attr( $post_catout ); ?>" /></p>
		
		<p>Query by Taxonomy, Choose taxonomy <select name="<?php echo $this->get_field_name('taxis'); ?>"><?php
		
			$dataxes = get_object_taxonomies($post_typed, 'objects');
			foreach($dataxes as $atax){
				?>
					<option value="<?php echo $atax->name; ?>" <?php if($atax->name == $post_taxis){echo "selected";} ?>><?php echo $atax->label; ?></option>
				<?php
			}
		?>
		</select>
		<br/>
		Then enter the term slug 
		<input class="widefat" name="<?php echo $this->get_field_name( 'taxterm' ); ?>" value="<?php echo esc_attr( $post_taxterm ); ?>" />
		</p>
		
		<p>For tax queries, this widget interface only supports one tax query, for multiple use wpr_adjust_genposts_query filter<br/>
		Meta Key: <input class="widefat" name="<?php echo $this->get_field_name( 'metakey' ); ?>" value="<?php echo $post_metavalue; ?>" />
		<br/>
		Meta Value: <input class="widefat" name="<?php echo $this->get_field_name( 'metavalue' ); ?>" value="<?php echo $post_metavalue; ?>" />
		<br/>
		Meta Compare
		<select name="<?php echo $this->get_field_name( 'metacompare' ); ?>">
                <?php
                foreach( $metcompare as $mc ){
                ?>
                    <option value="<?php echo $mc ?>" <?php selected( $post_comparison, $mc); ?>><?php echo $mc; ?></option>
                <?php } ?>
            </select>
		</p>
		
		<p>Read More title.  Leave blank to omit. <input class="widefat" name="<?php echo $this->get_field_name( 'readmoretitle' ); ?>" value="<?php echo esc_attr($post_readmoretitle); ?>" /></p>
		
		<p>Read More link.  Leave blank to omit. Do not put home url (http(s)://example.com) if you want to use relative path.  If http(s) exists, static url you entered will be used. <input class="widefat" name="<?php echo $this->get_field_name( 'readmorelink' ); ?>" value="<?php echo esc_attr($post_readmorelink); ?>" /></p>
		<?php
	}
}

function garden_register_general_posts_widget() {
	register_widget( 'GardenGeneralPosts' );
}

add_action( 'widgets_init', 'garden_register_general_posts_widget' );
