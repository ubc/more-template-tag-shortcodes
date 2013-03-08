<?php 
/**
 * Plugin Name: More Template Tag Shortcodes
 * Plugin URI: 
 * Description: Adds even more simple shortcode that can be used in posts and pages 
 * Version: 1.1
 * Author: CTLT UBC
 * Author URI: http://ctlt.ubc.ca
 * License: GPL2 or later
 *
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */
 
 
 /* Unit Test Place this in your to the content of a page 
 
	[post-class]
	[the_plain_tags]
	
	[author]
	[posts-author-link]
	[modified-author]
	[permalink]
	
	[date]
	[time]
	[modified_date]
	[modified_time]
	
	
	[the_post_thumbnail]
	[the_post_thumbnail_src]
	
	[comments]
	[comment-link]
	[categories]
	[tags]
	[revisions]
	[last-updated]
	[the_content]
	[the_excerpt]
	
	[child-pages]
	[child-pages depth=1]
	
	[the_post_thumbnail_src size=thumbnail]
	[the_post_thumbnail_src size=medium]
	[the_post_thumbnail_src size= large]
	[the_post_thumbnail_src size=full]
	
	[the_post_thumbnail size=thumbnail]
	[the_post_thumbnail size=medium]
	[the_post_thumbnail size= large]
	[the_post_thumbnail size=full]
	
	[menu]
	
 

 */
 
 
 /**
  * CTLT_More_Template_Tag_Shortcode class.
  */
 class CTLT_More_Template_Tag_Shortcode {
	

	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
	
		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );
		
	}
	
	
	/**
	 * has_shortcode function.
	 * 
	 * @access public
	 * @param mixed $shortcode
	 * @return void
	 */
	function has_shortcode( $shortcode ){
		global $shortcode_tags;
		/* don't do anything if the shortcode exists already */
		return ( in_array( $shortcode, array_keys( $shortcode_tags ) ) ? true : false );
	}
	
	/**
	 * add_shortcode function.
	 * 
	 * @access public
	 * @param mixed $shortcode
	 * @param mixed $shortcode_function
	 * @return void
	 */
	function add_shortcode( $shortcode, $shortcode_function ){
	
		if( !$this->has_shortcode( $shortcode ) )
			add_shortcode( $shortcode, array( &$this, $shortcode_function ) );
		
	}
	
	/**
	 * register_shortcode function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		
		
		
		$this->add_shortcode( 'post-class','post_class_shortcode' );
		$this->add_shortcode( 'the_plain_tags','get_plain_tags_shortcode');
		
		$this->add_shortcode( 'author', 'author_shortcode' );
		$this->add_shortcode( 'posts-author-link', 'author_posts_link_shortcode' );
		$this->add_shortcode( 'modified-author','modified_author_shortcode' );
		$this->add_shortcode( 'permalink', 'permalink_shortcode' );

		$this->add_shortcode( 'date', 'date_shortcode' );
		$this->add_shortcode( 'time', 'time_shortcode' );
		$this->add_shortcode( 'modified_date', 'modified_date_shortcode' );
		$this->add_shortcode( 'modified_time', 'modified_time_shortcode' );


		$this->add_shortcode( 'the_post_thumbnail', 'the_post_thumbnail_shortcode');
		$this->add_shortcode( 'the_post_thumbnail_src', 'the_post_thumbnail_src_shortcode');

		$this->add_shortcode('comments', 'comments_shortcode');
		$this->add_shortcode('comment-link', 'comments_link_shortcode');
		$this->add_shortcode('categories', 'post_categories_shortcode');
		$this->add_shortcode('tags', 'post_tags_shortcode');
		$this->add_shortcode('revisions', 'post_revisions_shortcode');
		$this->add_shortcode('last-updated', 'last_updated_shortcode');

		$this->add_shortcode( 'the_content', 'the_content_shortcode');
		$this->add_shortcode( 'the_excerpt', 'the_excerpt_shortcode');

		$this->add_shortcode( 'child-pages', 'childpages_shortcode');
		$this->add_shortcode( 'menu', 'menu_shortcode');
	}
	
	
	/**
	 * get_plain_tags_shortcode function.
	 * Gets post tags in plain text
	 * @access public
	 * @return void
	 */
	function get_plain_tags_shortcode() {
	
		$posttags = get_the_tags();
		if ($posttags) {
			foreach($posttags as $tag) {
				$htmlstr .= $tag->name . ' '; 
			}
		}
		return $htmlstr;
	}

	/**
	 * post_class_shortcode function.
	 * 
	 * @access public
	 * @return void
	 */
	function post_class_shortcode() {
		ob_start();
	
		if( function_exists('hybrid_entry_class') )
			hybrid_entry_class();
		else
			post_class();
			
		return ob_get_clean();
	}
	
	
	/**
	 * author_shortcode function.
	 * 
	 * Displays the post/page author
	 * @access public
	 * @return void
	 */
	function author_shortcode()
	{
		return get_the_author();
	}
	
	/**
	 * the_content_shortcode function.
	 * 
	 * Displays the post content
	 * @access public
	 * @return void
	 */
	function the_content_shortcode()
	{
		$content = get_the_content();
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
	
		return $content;
	}
	
	/**
	 * the_excerpt_shortcode function.
	 * 
	 * Displays the excerpt
	 * @access public
	 * @return void
	 */
	function the_excerpt_shortcode()
	{
		return get_the_excerpt();
	}
	
	/**
	 * author_posts_link_shortcode function.
	 * 
	 * Displays the link the authors posts archive page
	 * with the authors nickname as the link text
	 * @access public
	 * @return void
	 */
	function author_posts_link_shortcode()
	{
		global $authordata;	
		return sprintf(
			'<a href="%1$s" title="%2$s">%3$s</a>',
			get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
			sprintf( __( 'Posts by %s' ), attribute_escape( get_the_author() ) ),
			get_the_author()
		);
		
	}
	/**
	 * modified_author_shortcode function.
	 * 
	 * Displays the last person who changed the post/page
	 * @access public
	 * @return void
	 */
	function modified_author_shortcode()
	{
		return get_the_modified_author();
	}
	
	/**
	 * permalink_shortcode function.
	 * 
	 * Displays the permalink if a particular post/page
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function permalink_shortcode( $attr )
	{	
		global $post;
		// to work nicely with ther loop shortcode
		if( isset( $post->is_loop_shortcode_feed ) )
			return $post->guid;
			
		$id = ( isset( $attr['id'] )? $attr['id']: null );
		return get_permalink( $id );
	}
	
	/**
	 * date_shortcode function.
	 * 
	 * Displays the publish date of post/page as defined in the general settings
	 * publish time only appear once in the page that happen on the same date
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function date_shortcode($attr)
	{	
		$format = ( isset($attr['format']) ? $attr['format'] : get_option('date_format'));
		return get_the_time( $format );
	}
	
	/**
	 * time_shortcode function.
	 *
	 * Displays the publish time of post/page as defined in the general settings
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function time_shortcode($attr)
	{
		$format = ( isset($attr['format']) ? $attr['format'] : '');
		return get_the_time( $format );
	}
	
	/**
	 * modified_date_shortcode function.
	 * 
	 * Displays the modefied date of post/page as 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function modified_date_shortcode( $attr ) {
		$format = ( isset($attr['format']) ? $attr['format'] : get_option('date_format'));
		return get_the_modified_date( $format );
	}
	
	/**
	 * modified_time_shortcode function.
	 * 
	 * Displays the modefied time of post/page as defined in the general settings
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function modified_time_shortcode( $attr ) {
		$format = ( isset($attr['format']) ? $attr['format'] : '');
		return get_the_modified_time( $format );
	}
	/**
	 * the_post_thumbnail_shortcode function.
	 * size options - thumbnail,medium,large,full, page-header, single-post-slide, single-post-slide-half, single-post-wide-slide
	 * [the_post_thumbnail size=thumbnail]
	 * [the_post_thumbnail size=medium]
	 * [the_post_thumbnail size= large]
	 * [the_post_thumbnail size=full]
	 * [the_post_thumbnail size=page-header]
	 * [the_post_thumbnail size=single-post-slide]
	 * [the_post_thumbnail size=single-post-slide-half]
	 * [the_post_thumbnail size=single-post-wide-slide]
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function the_post_thumbnail_shortcode( $attr )
	{
		extract(shortcode_atts(array(
			'size' => 'post-thumbnail',
			'width' =>null,
			'height'=>null,
		), $attr));
		
		
		
		if( $width && $height ):
			$size = array($width,$height);
		endif;
		return get_the_post_thumbnail(NULL,$size, $attr);
	}
	
	/**
	 * the_post_thumbnail_src_shortcode function.
	 * size options - thumbnail,medium,large,full, page-header, single-post-slide, single-post-slide-half, single-post-wide-slide
	 * timthumb = 1 gives you back the url of the image passed though the timthumb image processor
	 * width and height, sizes in pixels - used by both timbthumb and wp_get_atacgemnet_img_src function
	 * [the_post_thumbnail_src size=thumbnail]
	 * [the_post_thumbnail_src size=medium]
	 * [the_post_thumbnail_src size= large]
	 * [the_post_thumbnail_src size=full]
	 * [the_post_thumbnail_src size=page-header]
	 * [the_post_thumbnail_src size=single-post-slide]
	 * [the_post_thumbnail_src size=single-post-slide-half]
	 * [the_post_thumbnail_src size=single-post-wide-slide]
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function the_post_thumbnail_src_shortcode( $attr ) {
		
		global $blog_id,$post;
		$default_blog_id = $blog_id;
		extract(shortcode_atts(array(
			'size' 		=> 'post-thumbnail',
			'width' 	=>null,
			'height'	=>null,
			'timthumb' 	=>null,
			'blog_id'  	=>$blog_id,
			'default'	=> null,
			'end'		=> '',
			'network' 	=> true
		), $attr));
		
		if( $width && $height ):
			$size = array($width,$height);
			$end =  "&w=".$width."&h=".$height.$end;
		endif;
		
		if(!isset( $post->is_loop_shortcode_feed ) ):
			$attachment_id = get_post_thumbnail_id(NULL); // gives you the current id
			
			$image = wp_get_attachment_image_src( $attachment_id, $size, false );
			if(isset($image[0])):
				if( $timthumb ):
					$src = $image[0];
					if (isset($blog_id) && $blog_id > 0) {
					$imageParts = explode('/files/', $image[0]);
					if (isset($imageParts[1])) {
						$src = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
						}
					}
					$blog_id = $default_blog_id;
					$src = get_bloginfo('wpurl')."/wp-content/themes/clf-base/library/images/timthumb.php?bid=".$default_blog_id."&src=".urlencode($src).$end;
				endif;
				
				$blog_id = $default_blog_id;
				$src = $image[0];
			else:
				if(isset($default)):
					$src = $default;
				else:
					$src = "No Images";
				endif;
			endif;
			$blog_id = $default_blog_id;
			return $src;
	
		else: 
			// the conten is comming from a feed
			
			if( $enclosure = $post->post_content_filtered->get_enclosure()):
				if(isset($timthumb) && isset($blog_id)) {
					$src = $enclosure->link;
					
					$imageParts = explode('/files/', $src);
					if ( isset($imageParts[1]) ) {
						$src = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
						$src = get_bloginfo('wpurl')."/wp-content/themes/clf-base/library/images/timthumb.php?bid=".$blog_id."&src=".urlencode($src).$end;
					} elseif($blog_id = 1) {
						$src = get_bloginfo('wpurl')."/wp-content/themes/clf-base/library/images/timthumb.php?bid=".$blog_id."&src=".urlencode($src).$end;
					}
	
				} elseif( isset($timthumb) ) {
					$src = $enclosure->link;
					$src = get_bloginfo('wpurl')."/wp-content/themes/clf-base/library/images/timthumb.php&src=".urlencode(   ).$end;
				
				} elseif($size == 'post-thumbnail' || $size= 'thumbnail' ) {
					$src = $enclosure->thumbnails[0];
				
				} else{
					$src = $enclosure->link;
				}
			else:
				if(isset($default)):
					$src = $default;
				else:
					$src = "No Images";
				endif;
			endif;
			$blog_id = $default_blog_id;
			return $src;
		endif; 
	}
	/**
	 * comments_shortcode function.
	 * 
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	function comments_shortcode($atts)
	{	
		global $post;
		extract(shortcode_atts(array(
			'no_comments' => false,
			'one_comment' => false,
			'numer_of_comments' =>false
		), $atts));
		
		if( !isset($post->is_loop_shortcode_feed) ):
			ob_start(); ?>
			<a href="<?php echo get_comments_link(); ?>" title="<?php comments_number($no_comments,$one_comment,$numer_of_comments); ?>" ><?php comments_number($no_comments,$one_comment,$numer_of_comments); ?></a>
			<?php 
			return ob_get_clean();
		else:
			
		$number = $this->get_feed_comments_number();
	
		if ( $number > 1 )
			$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments') : $more);
		elseif ( $number === 0 )
			$output = ( false === $no_comments ) ? __('No Comments') : $no_comments;
        elseif($number == 1 ) // must be one
            $output = ( false === $one_comment ) ? __('1 Comment') : $one_comment;
        else
        	return "";
		endif;
		
		return '<a href="'.$this->comments_link_shortcode().'" title="'.$output.'">'.$output.'</a>';
	}
	
	/**
	 * get_feed_comments_number function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_feed_comments_number(){
		global $post;
		
		$link_data = $post->post_content_filtered->get_item_tags('http://purl.org/rss/1.0/modules/slash/','comments');
		$comment_number = ( isset($link_data[0]['data']) ? $link_data[0]['data']: null );
		
		if( !is_null( $comment_number ) )
			return (int)$comment_number;
			
		// try something else see if the feed is an atom feed
		$link_data = $post->post_content_filtered->get_item_tags('http://purl.org/syndication/thread/1.0','total');
		$comment_number = ( isset($link_data[0]['data']) ? $link_data[0]['data']: null );
		
		if( !is_null( $comment_number ) )
			return (int)$comment_number;
		
		return null;
	}
	
	/**
	 * comments_link_shortcode function.
	 * 
	 * @access public
	 * @return void
	 */
	function comments_link_shortcode()
	{
		
		
		global $post;
		
		if( !isset($post->is_loop_shortcode_feed) ):
			return get_comments_link();
		else:
			//  try to get the comments link
			
			
			$link_data = $post->post_content_filtered->get_item_tags('','comments');
			$link = ( isset($link_data[0]['data']) ? $link_data[0]['data']: null );
			
			if( $link ):
				return $link;
			else:
				// I am guessing the feed is an atom feed
				$link_data = $post->post_content_filtered->get_item_tags(SIMPLEPIE_NAMESPACE_ATOM_10,'link');
				
				foreach($link_data as $data):
					if( 'text/html' == $data['attribs']['']['type'] && 'replies' == $data['attribs']['']['rel'] )
					$link = $data['attribs']['']['href'];
				endforeach;	
				return $link;
			endif;
			return ;
			
		endif;
		
	}
	
	
	/**
	 * post_categories_shortcode function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function post_categories_shortcode($attr)
	{
		if ( isset( $attr['post_id'] ) )
			$attr['post_id'] = (int)$attr['post_id'];
		else
			$attr['post_id']  = false;
		$parents = ( in_array( $attr['parents'], array( 'multiple', 'single' ) ) ? $attr['parents'] : false );
		$separator = ( isset( $attr['separator'] ) ? $attr['separator']: ", ");
		return get_the_category_list( $separator, $parents, $attr['post_id'] );
	}
	
	/**
	 * post_tags_shortcode function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function post_tags_shortcode( $attr ) {
		$separator = ( isset( $attr['separator'] ) ? $attr['separator'] : ", ");
		
		$before = ( isset( $attr['before'] ) ? $attr['before'] : '');
		$after = ( isset( $attr['after'] ) ? $attr['after'] : '');
		
		return get_the_tag_list( $before, $separator, $after );
	}
	
	/**
	 * post_revisions_shortcode function.
	 * 
	 * Displays the current post revisions to the logged in user. 
	 *
	 * @access public
	 * @return void
	 */
	function post_revisions_shortcode()
	{
		global $post;
		$args = array( 'parent' => true,  'type'=>'revision');
		ob_start();
		wp_list_post_revisions( $post->ID , $args );
		
		return ob_get_clean();
	
	}
	/**
	 * last_updated_shortcode function.
	 * 
	 * Displays the last updated on and the date and time of a post/page
	 * @access public
	 * @return void
	 */
	function last_updated_shortcode($attr)
	{
		$format = (isset($attr['format']) ? $attr['format']: "F j, Y @g:i a");
		return get_the_modified_time( $format );
	
	}
	
	/**
	 * childpages_shortcode function.
	 * 
	 * @access public
	 * @param int $depth (default: 0)
	 * @return void
	 */
	function childpages_shortcode( $depth=0 )
	{
		global $post;
		if($post->is_loop_shortcode_feed)
			return "";
		$args = array(
	    'depth'        => $depth,
	    'date_format'  => get_option('date_format'),
	    'child_of'     => $post->ID,
	 	'title_li'     => __(''),
	    'echo'         => false,
	    'sort_column'  => 'menu_order, post_title'
	    );
	    
	    $child_pages = wp_list_pages( $args );
	    
	    if($child_pages)
			return "<div class='nav'><ul class='child-pages'>".$child_pages."</ul></div>";
		else
			return "";
	}
	
	function menu_shortcode( $atts ) {
		extract(shortcode_atts(array(  
			'menu'            => '', 
			'container'       => 'div', 
			'container_class' => '', 
			'container_id'    => '', 
			'menu_class'      => 'menu', 
			'menu_id'         => '',
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'depth'           => 0,
			'walker'          => '',
			'theme_location'  => ''), 
			$atts));
		
		
	$nav_arg = apply_filters( 'menu-shortcode-attributes', array( 
		'menu'            => $menu, 
		'container'       => $container, 
		'container_class' => $container_class, 
		'container_id'    => $container_id, 
		'menu_class'      => $menu_class, 
		'menu_id'         => $menu_id,
		'echo'            => false,
		'fallback_cb'     => $fallback_cb,
		'before'          => $before,
		'after'           => $after,
		'link_before'     => $link_before,
		'link_after'      => $link_after,
		'depth'           => $depth,
		'walker'          => $walker,
		'theme_location'  => $theme_location ) ) ;
		
 	return wp_nav_menu( $nav_arg );
	
	}
	
}

new CTLT_More_Template_Tag_Shortcode();

