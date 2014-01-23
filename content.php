<?php
/*
 * Outputs the default primary content area for all pages.
 */

/*
 * 404
 */
 
if (is_404()) {
?>
<header id="article-header">
<h1 id="page-title">Sorry!</h1>
</header>
<h2>We couldn't locate your page.</h2>
<p>To continue using our site, please click any navigational link.</p>
<?php
}


/*---------------------------------------------*/


/*
 * BLOG: Archives, Search
 */
 
elseif (is_archive() || is_search()) {
?>

<div class="inner">
<?php
	if (have_posts()) {
		while (have_posts()) {
			
			the_post();

			$custom = get_post_custom($post->ID);
			extract($custom);

			if (!empty($_tdrcheading[0]) && 'none' != $_tdrcheading[0]) {
				$category = get_category($_tdrcheading[0]);
			}
			else {
				$categories = get_the_category();
				$category = $categories[0];
			}

			$title = (!empty($_tdrshorttitle[0])) ? $_tdrshorttitle[0] : get_the_title();

			$excerpt = apply_filters('the_content', get_the_excerpt());
?>

<div <?php post_class('archive-post clearfix'); ?>>
<?php
			if (has_post_thumbnail()) {
?>
<div class="image">
<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-post'); ?></a>
</div>
<?php
			}
?>
<div class="post-content">
<div class="meta"> 
<a class="cat" href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->cat_name; ?></a><span class="date"><time datetime="<?php the_time('Y-m-d'); ?>" pubdate><?php the_time('M j,Y'); ?></time></span>
</div>
<h2><a href="<?php the_permalink(); ?>"><?php echo $title; ?></a></h2>
<?php echo tdr_shorten_text($excerpt, 130, '...'); ?>
<div class="archive-author-likes clearfix">
<div class="author"><?php the_author_posts_link(); ?></div>
<div class="likes"><span class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=400');return false"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-gray-facebook.png"> <?php echo tdr_get_fb_likes(); ?></a></span>&bull;<span class="twitter"><a href="https://twitter.com/share?text=<?php the_title(); ?>+<?php the_permalink(); ?>+%40thedatereport&url=<?php the_permalink(); ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=265');return false"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-gray-twitter.png"> <?php echo tdr_get_twitter_shares(); ?></a></div>
</div>
<a class="more" href="<?php the_permalink(); ?>">Read More</a>
</div>
</div>
<?php
			unset($_tdrcheading, $_tdrshorttitle);
		}
	}

	else {
		echo '<p>No posts found.</p>';
	}
?>

</div>

<?php
	$count_posts = $wp_query->found_posts;

	if ($count_posts > get_option('posts_per_page')) {
?>

<div id="more-posts"><a href="#">Show More Posts</a></div>
<?php
	}

	if (function_exists('adrotate_ad')) {
		$adrotate = 3;
?>

<!-- Ad -->
<div id="bottom-ad"><?php echo adrotate_ad($adrotate); ?></div>
<?php
	}
}


/*---------------------------------------------*/


/*
 * BLOG: Individual Post
 */
 
elseif (is_singular('post')) {
	if (have_posts()) {
		while (have_posts()) {

			the_post();

			$custom = get_post_custom();
			extract($custom);

			if (!empty($_tdrcheading[0]) && 'none' != $_tdrcheading[0]) {
				$top_category = get_category($_tdrcheading[0]);
			}
			else {
				$categories = get_the_category();
				$top_category = $categories[0];
			}

			$author_twitter = get_the_author_meta('twitter');
			$image_attributes = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

			$twitter_html = '';

			if (!empty($author_twitter))
				$twitter = '&bull;<span class="twitter"><a href="http://twitter.com/'.$author_twitter.'" target="_blank">@'.$author_twitter.'</a></span>';

			$tweet_content = get_the_title($post->ID).' '.get_permalink($post->ID).' @'.get_option('tdrtwitter');

			$thumb_id = get_post_thumbnail_id();
			$thumb_url = wp_get_attachment_image_src($thumb_id,'full', true);
?>
<header id="article-header">

<?php 
$tdrfullwidth = get_post_custom_values('_tdrfullwidth'); // check if full width template
if($tdrfullwidth[0] != 'on'){ ?>
	<h2 id="header-slug-category"><span class="outer"><span class="inner"><a href="<?php echo get_category_link($top_category->term_id); ?>"><?php echo $top_category->name ?></a></span></span></h2>
	<h1 id="page-title"><?php the_title(); ?></h1>
<?php }

 ?>



<div class="clearfix" id="header-meta-likes">
<div class="meta">
<span class="author"><?php the_author_posts_link(); ?></span><?php echo $twitter; ?><span class="mobile-noshow">&bull;</span><span class="date"><time datetime="<?php the_time('Y-m-d'); ?>" pubdate><?php the_time('M. jS, Y'); ?></time></span>
</div>
<div id="article-header-likes">
<span class="mobile-show">&bull;</span><span class="facebook"><div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div></span>&bull;<span class="pinterest-top"><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pin it"></a></span>&bull;<span class="twitter"><a href="https://twitter.com/share" data-text="<?php echo $tweet_content; ?>" data-url=" " data-counturl="<?php echo get_permalink(); ?>" class="twitter-share-button">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'howaboutwe');</script></span>
</div>
</div>
</header>


<div id="article-content">
<?php the_content();?>
</div>


<footer id="article-footer">

<div id="article-footer-top">

<!-- Shares -->
<div id="article-footer-likes">
<div class="inner">
<span class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank"><span>Share</span></a></span>&bull;<span class="pinterest-top"><a href="//www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>" data-pin-do="buttonBookmark" data-pin-height="28"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_28.png"></a></span>&bull;<span class="twitter"><a href="https://twitter.com/share" data-text="<?php echo $tweet_content; ?>" data-lang="en" data-size="large" data-count="none" data-url=" " class="twitter-share-button">Tweet</a></span>
</div>
</div>
<?php
			$footer_categories = get_the_category();
			$countStop = count($footer_categories);
?>


<!-- Categories -->
<div class="clearfix" id="footer-categories">
<p>Get More</p>
<ul class="post-categories">
<li><a href="<?php echo get_category_link($top_category->term_id); ?>"><?php echo $top_category->name; ?></a></li>

<?php
			// In order to exclude the category heading from the list
			$top_category_ID = get_cat_ID($top_category->name);

			foreach($footer_categories as $fcategory) {
				if (get_cat_ID($fcategory->name) != $top_category_ID) {
					echo '<li><a href="'.get_category_link($fcategory->term_id).'">'.$fcategory->name.'</a></li>';
				}
			}
?>
</ul></div>

</div>
<?php
			// Check for footnote
			if ('' != $_tdrfootnote[0]) {
?>


<!-- Footnote -->
<div id="article-footnote"><?php echo $_tdrfootnote[0]; ?></div>
<?php } ?>

</footer>
<?php
			$related_posts = array();

			for ($i = 1; $i <= 3; $i++) {

				$relpostkey = "_tdrrelpost$i";

				if ('' != ${$relpostkey}[0])
					$related_posts[] = ${$relpostkey}[0];

			}

			if (!empty($related_posts)) {
?>


<!-- Related Posts -->
<div id="related-posts" class="clearfix">
<h3>You May Also Like</h3>	
<ul>
<?php foreach ($related_posts as $rpost) { ?>
<li>
<a href="<?php echo get_permalink($rpost); ?>" class="img"><?php echo get_the_post_thumbnail($rpost, 'related-image') ?></a>
<span class="clearfix">
<a class="title" href="<?php echo get_permalink($rpost); ?>"><?php echo get_the_title($rpost); ?></a>
<a class="more_btn" href="<?php echo get_permalink($rpost); ?>">Read More</a>
</span>
</li>
<?php } ?>
</ul>
</div>
<?php
			}


			$comments_number = get_comments_number();
			$comments_text = $comments_number.' Comments';

			if (1 == $comments_number)
				$comments_text = '1 Comment';
?>



<div id="precomments">
<span id="countcomments"><?php echo $comments_text; ?></span>
<span id="opencomments"><a href="#">Add A Comment</a></span>
</div>


<div id="disqus_thread"></div>
<script type="text/javascript">
var disqus_shortname = 'hbwdatereport';
var disqus_identifier = <?php the_ID(); ?>;

(function() {
    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>
<?php
		}
	}

	if (function_exists('adrotate_ad')) {
		$adrotate = 3;

		if (is_front_page())
			$adrotate = 6;
?>

<!-- Ad -->
<div id="bottom-ad"><?php echo adrotate_ad($adrotate); ?></div>
<?php
	}
}


/*---------------------------------------------*/


/*
 * DEFAULT
 */
 
else {
	if (have_posts()) {
		while (have_posts()) {
			the_post();
			$footnote = get_post_custom_values('_tdrfootnote');
?>
<header id="article-header">
<h1 id="page-title"><?php the_title(); ?></h1>
</header>
<?php
			if (has_post_thumbnail())
				the_post_thumbnail('image-header');
?>
<?php
			the_content();
?>
<footer id="article-footer">
<div id="article-footer-top">
<div id="article-footer-likes">
<div class="inner">
<span class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank"><span>Share</span></a></span>&bull;<span class="pinterest-top"><a href="//www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>" data-pin-do="buttonBookmark" data-pin-height="28"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_28.png"></a></span>&bull;<span class="twitter"><a href="https://twitter.com/share" data-text="<?php echo $tweet_content; ?>" data-lang="en" data-size="large" data-count="none" data-url=" " class="twitter-share-button">Tweet</a></span>
</div>
</div>
</div>





<?php
	if ($footnote[0] != '' ){
?>

<div id="article-footnote"><?php echo $footnote[0]; ?></div>
<?php
}
?>

</footer>
<?php
		}
	}
}
