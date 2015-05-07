<h1>Project Attachments</h1>
<?php

//stop memory limit errors from occurring as a result of loading all projects
ini_set('memory_limit', '1024M');

$attachments = array(
  'book_manuscript_original',
  'book_manuscript_edited',
  'book_manuscript_proofed',
  'book_layout_upload',
  'book_stock_cover_image',
  'book_cover_concept',
  'book_ebook_front_cover',
  'book_createspace_cover',
  'book_lightning_source_cover',
  'book_alternative_cover_template',
  'book_final_manuscript_pdf',
  'book_final_doc_file',
  'book_final_mobi',
  'book_final_epub',
  'book_final_pdf',
  );

#fetching the projects
$projects = new WP_Query( array('post_type' => 'projects', 'posts_per_page' => 1) );

$project_count = 0;
$attachment_count = 0;

$result = '';

while( $projects->have_posts() ) {
  $projects->the_post();

  $projectID = $projects->ID;

  $custom_fields = get_post_custom($projectID);

  foreach ($attachments as $type) {
    //$type = 'book_manuscript_original';
    //$attachment_id = $custom_fields['book_manuscript_original'][0];
    $attachment_id = $custom_fields[$type][0];
    if ($attachment_id !== null && $attachment_id != '') {
      $result.= $attachment_id.','.$type.','.wp_get_attachment_url($attachment_id)."\n";
      $attachment_count++;
    }
  }

  $project_count++;
}
print "total projects: $project_count<br />";
print "total attachments: $attachment_count";
?>
<textarea cols="100" rows="35"><?php print $result  ?></textarea>
<?php
