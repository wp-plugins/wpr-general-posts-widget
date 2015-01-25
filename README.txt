=== Plugin Name ===
Contributors: aryanduntley, dunar21
Plugin Name: WPR General Posts Widget
Donate link: http://worldpressrevolution.com/wpr_myplugins/wpr-general-posts-widget/
Plugin URI: http://worldpressrevolution.com/wpr_myplugins/wpr-general-posts-widget/
Author URI: http://worldpressrevolution.com/ 
Tags: posts widget,recent posts, recent post, recent posts widget, popular posts, popular posts widget, posts sidebar, sidebar post widget, custom post type widget, recent posts custom post type, newest posts, newest posts widget
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gives you full control of a post listing widget.

== Description ==
With the general posts widget, you can place a list of posts into your widget areas based on any query parameters available in WP_QUERY.  You can generate the latest posts, popular posts (given you have some method of tracking post hits), post types, filter by category or other taxonomy, filter by post meta, etc...  If it's available in WP_QUERY it's available to you in the widget.  If there are customizations that the interface does not allow for, there are a number of hooks that allow you to edit and control pretty much any part of the widget from adjusting the query to adjusting the output.

#### Please Note
There is no styling associated with this plugin.  If you wish to style the output, assign a class and/or an ID to the widget and style appropriately in your style.css file.

> #### Available Hooks
> * `add_filter( 'widget_title', 'my_Func'); function my_Func($title){return $title;}`
> * `add_filter('wpr_adjust_genposts_query','my_Func'); function my_Func( $queryargs, $widgetargs, $instance){return $queryargs}`
> `$widgetargs` contains things like `before_widget` and `after_widget`.  `$instance` contains the widget params you added in the UI.
> * `add_filter('wpr_genposts_titlefilter', 'my_Func'); function my_Func($fintitle, $before_title, $title, $after_title, $instance){return $fintitle}`
> * `add_filter`('wpr_genposts_listloop', 'my_Func'); function my_Func($thisprint, $found_posts, $post, $count, $instance){return $thisprint;}
> This filter is within the loop that prints the `<li>'s`.  `$thisprint` is the final string containing all the html including the `<li>` opening and closing tags. This filter will likely be the one used the most.  By default, this outputs the featured image (if one exists) and the title.  That's all.  In order to edit the output of the loop, you would want to edit your my_Func function to something else, utilizing the $post variable which contains all the post information (title, excerpt, content, permalink, ect...).  This is up to you to customize however you wish.  I'm sure the support area will fill up with questions in regards to outputting the lists in a certain fashion.  Most people will not read or understand this that I wrote here and many examples will likely sprout up in the support section, so stay tuned and read through those (unless you are the very first to ask for support) before posting a support question.  This plugin is free and support should not be expected.  I will have a general support license available at a later time, for all WPR plugins, but for now, don't expect, but be grateful if I do answer.  I'm usually good about it though.
> * `add_filter('wpr_genposts_addtoend', 'my_Func'); function my_Func($readingon, $instance){return $readingon;}`
> This filter allows you to customize the read more link that is shown after all the posts are displayed.  The final text/html is the `$readingon` variable and the `$instance` provides you with all the widget instance params you supplied in the widget interface.
> * `apply_filters('wpr_genposts_list_print', 'my_Func'); function my_Func($finalprint, $openprint, $toprint, $closeprint, $instance, $wpQuery){return $finalprint;}`
> This supplies the final list with the container divs and everything else.  `$openprint` contains the opening div with the id and class supplied by the widget interface.  It also includes the openieng `<ul>` tag.  `$closeprint` contains all the closure tags for the `$openprint` as well as the readmore link/text.  `$toprint` contains everything in between (the result of the query contained in `<li>` tags). `$wpQuery` contains the WP_Query instance, which can be used for pagination or anything else where the data provided could be useful. To add pagination, something like this would work: 
> `function homeAddPages($finalprint, $openprint, $toprint, $closeprint, $instance, $postsQ){
	$big = 999999999;
	$cpage = get_query_var('paged')?get_query_var('paged'):0;
	if(!isset($cpage) || $cpage == "" || $cpage === 0){
		$cpage = get_query_var('page')?get_query_var('page'):1;
	}
	$addclose = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, $cpage),
		'total' => $postsQ->max_num_pages
	) );
	return $openprint . $toprint . $closeprint . '<div class="hpaginator">' . $addclose . '</div>';
}
add_filter('wpr_genposts_list_print', 'homeAddPages', 10, 6);`


---
 
> #### Instance Variables
>
> * `$title = apply_filters( 'widget_title', $instance['title'] );`
> * `$post_amount = $instance['show'];` This is the posts per page (total posts to show)
> * `$post_orderby = $instance['orderby'];`
> * `$post_order = $instance['order'];`
> * `$post_catin = $instance['catin'];` Category In
> * `$post_catout = $instance['catout'];` Category Exclude
> * `$pagecount = $instance['pagecount'];` Numer of Posts to show (not used, this is so you can define total posts to query and number to show per tabbed interface which is not implemented in the plugin, but available for hooking)
> * `$post_taxis = $instance['taxis'];` Taxonamy slug
> * `$post_taxterm = $instance['taxterm'];` Taxonomy term ids, comma separated list
> * `$post_typed = $instance['ptipe'];`
> * `$post_metakey = $instance['metakey'];`
> * `$post_metavalue = $instance['metavalue'];`
> * `$post_comparison = $instance['metacompare'];` Meta comparison operator
> * `$post_widgeid = $instance['widgetidentifier'];` Widget Container ID
> * `$post_widgeclass = $instance['widgetclassifier'];` Widget Container Class
> * `$post_readmoretitle = $instance['readmoretitle'];`
> * `$post_readmorelink = $instance['readmorelink'];`
>
 
---


Plugin site: [WorldpressRevolution](http://worldpressrevolution.com/wpr_myplugins/wpr-general-posts-widget/ "Aryan Duntley's Worldpress Revolution wordpress tutorials")


== Installation ==



1. Upload `wpr-general-posts` folder to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress



== Screenshots ==



1. Title, Id of the widget container, Class of the widget container, Choose post type, Posts per page, display per tab (not active with the plugin by default, you must hook the code in order to create this functionality yourself).

2. Choose order by.  Choose order.  You can choose either to include or exclude categories by id, one or the other. Query by taxonomy, choose a taxonomy.  Enter the SLUG of the taxonomy you chose.  Using the interface, you can only add one meta query.  You can provide the meta key and meta value.  In order to query by multiple meta key/values, you must hook into the appropriate area and extend the query parameters, adding your own meta query value.

3. Choose the meta compare operator for the above meta query.  You may enter in the read more text here.   It will automatically be wrapped in the appropriate <a> tag based on the link provided.  The read more text and link here does not refer to the individual posts in the list.  It is a link that is added after the entire list that directs the user to the page that contains a full list of the parameters set for the widget.  If your widget is listing most recent posts, of post type "revolution", then your read more link should link to the post type archive page for "revolution".  If it is a list of categories, then your read more link should link to the specific category post listing page (category.php, category-slug.php, etc...). You may use hmtl here (eg: More Articles <span class="readmorearrow" ></span>). Finally, if you put a read more title, you should add the read more link.  If you use a full url (http(s)://...) then that will be placed in the href attribute.  If you begin with a /, omitting the http(s)://, then the home url path will be appended to the link provided.



== Changelog ==

= 1.0.0 =

* Initial release.



