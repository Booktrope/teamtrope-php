<?php 	get_header();
		ini_set('memory_limit', '-1'); ?>
<link rel="stylesheet" id="gforms_css-css" href="/wp-content/plugins/gravityforms/css/forms.css" type="text/css" media="all">
<script src="http://underscorejs.org/underscore.js"></script>
<script src="https://raw.github.com/mkoryak/floatThead/master/dist/jquery.floatThead.js"></script>
<script type="text/javascript">
	jQuery(document).ready('table').floatThead();
</script>
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">
<?php 
	$i = 0;
	$total_projects = 0;
	$categories = get_terms('status', 'orderby=slug&order=ASC&hide_empty=1');
//	foreach( $categories as $category ): 
	$i = $i + 1;
?>
 <br/><h1 class="entry-title"><?php single_cat_title( '', true );

 echo " (" . count($posts) . ")"; ?></h1>
<?php 
$total_projects = $total_projects + intval(count($posts));

$headers = array( "Title", "Author","Genre",
							 	 "Editor","Proofreader", "Project Manager",
                 "Marketing Manager","Cover Designer", "Teamroom Link" );
?>

<script type="text/javascript" async="">
function toggleTable<?php echo $i; ?>() {
    var lTable = document.getElementById("Table<?php echo $i; ?>");
    lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
}
</script>
<table id="Table<?php echo $i; ?>" class="sortable">
   <tr>
   <?php 
   	foreach($headers as $header)
   	{
   		echo "<th>".$header."</th>\n";
   	}
   ?>
   </tr>			
<?php 

	$arrayFields = array(
   						"book_project_manager"		=> array("user", "-", "", false),
   						"book_editor"					=> array("user", "-", "", false),
   						"book_proofreader"			=> array("user", "-", "", false),
   						"book_marketing_manager"	=> array("user", "-", "", false),
   						"book_cover_designer"		=> array("user", "-", "", false),
							"book_teamroom_link"       => array("link", "<span class='awesome-x'>&#xf00d;</span>", "Team Room", false)
	);
	
 foreach($posts as $post): 
 setup_postdata($post); //enables the_title(), the_content(), etc. without specifying a post ID
 
 $custom_fields = get_post_custom($post->ID);
 $user_info = get_userdata($post->post_author);
 
 $book_authors = $post->book_author;
 $genres = $post->book_genre;
 $current_category = single_cat_title("", false); 
 ?>
 	<tr>
 		<td class="book-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></td>

   	<?php echo wrapField($book_authors, "user"); ?>
   	<?php echo wrapField($genres, "taxonomy", "<span class='awesome-x'>&#xf00d;</span>"); ?>
   	<?php
 		foreach ($arrayFields as $key => $value)
		{
			echo wrapCustomField($custom_fields, $key, $value[0], $value[1], $value[2], $value[3]);
		}   	
   	?>   	
   </tr>
<?php // the_excerpt(); ?>
 <?php  endforeach; ?>
</table>
<?php // endforeach; ?>
<br/><h2>Total: <?php echo $total_projects; ?></h2>
<?php wp_reset_query(); ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php //get_sidebar() ?>

<?php get_footer(); ?>

<?php 

function wrapCustomField($customFields, $fieldName, $type, $default = "-", $link_text = "", $check = false)
{	
	if (isset($customFields[$fieldName]))
	{
		return wrapField($customFields[$fieldName], $type, $default, $link_text, $check);
	}
	else
	{
		return "<td>".$default."</td>";
	}
}

function wrapField($fieldArray, $type, $default = "-", $link_text = "", $check = false)
{
	$result = $default;
	
	if (!empty($fieldArray) && !($fieldArray[0] === NULL) && trim($fieldArray[0]) != "")
	{
		$result = "";
		$first = true;
		foreach($fieldArray as $field)
		{
		   if(!$first) $result.=",";
		   if($type == "user")
		   {
				$user = get_userdata($field);
				if (!empty($user))
				{
					$result.= $user->display_name;
				}
			}
			else if ($type == "taxonomy")
			{
				$result.= get_term($field, "genres")->name; 
			}
			else if ($type == "single")
			{
				if($check)
				{
					$result.= "<span class='awesome-check'>&#xf00c;</span>";
				}
				else
				{
					$result.= $field;
				}
			}
			else if ($type == "popup")
			{
				if($check)
				{
					$result.= "<span class='awesome-check'>&#xf00c;</span>";
				}
				else
				{
					$result.= 
					'<a class="tooltip" href="#">Show
						<span class="classic">' . $field . '</span></a>';
				}
			}
			else if ($type == "link")
			{
				$result.= "<a href=\"".$field."\">".$link_text."</a>";
			}
			else if ($type == "radio")
			{
				if($field != "")
					$result.= $field; 
			}
			else if ($type == "checkbox")
			{
				if($field == 1)
				{
					$result.= "<span class='awesome-check'>&#xf00c;</span>";
				}
			}
			else if ($type == "price")
			{
				if ($field != 0)
				{
					$result.= "$".$field;
				}
			}
			else if($type == "thumbnail")
			{
				if($field)
				{
					if($check) $result .="<span class='awesome-check'>&#xf00c;</span>";
				}
			}
			$first = false;
		}
	}
	if (strlen($result) == 0)
	{
		$result = $default;
		if($check) $result = "<span class='awesome-check'>&#xf00c;</span>";
	}
   return "<td>".$result."</td>";
}
/*
 	$statuses = get_posts(array(
	'post_type' => 'statuses',
 	'orderby' => 'date',
 	'order' => 'DESC',
 	'nopaging' => true,
	'meta_query' => array(
		array(
			'key' => 'project_id',
			'value' => '784'
		)),
		'meta_query' => array(
		array(
			'key' => 'status_id',
			'value' => '23'
		))	
	));
*/
