<div class="bpfb_images">
<?php $rel = md5(microtime() . rand());?>
<?php foreach ($images as $img) { ?>
	<?php if (!$img) continue; ?>
	<?php if (preg_match('!^https?:\/\/!i', $img)) { // Remote image ?>
		<img src="<?php echo $img; ?>" />
	<?php } else { ?>
		<?php $info = pathinfo($img);
                switch ( $info['extension']) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    ?>
		<?php $thumbnail = file_exists(bpfb_get_image_dir($activity_blog_id) . $info['filename'] . '-bpfbt.' . strtolower($info['extension'])) ?
			bpfb_get_image_url($activity_blog_id) . $info['filename'] . '-bpfbt.' . strtolower($info['extension'])
			:
			bpfb_get_image_url($activity_blog_id) . $img
		;
		?>
		<a href="<?php echo bpfb_get_image_url($activity_blog_id) . $img; ?>" class="<?php echo $use_thickbox; ?>" rel="<?php echo $rel;?>">
			<img src="<?php echo $thumbnail;?>" />
		</a>
                <?php
                        break;
                    case 'doc':
                    case 'docx': ?>
                    <a href="<?php echo bpfb_get_image_url($activity_blog_id) . $img; ?>" rel="<?php echo $rel;?>"><?php echo '<img class="bpfb_thumbnail_chooser_right" src="/wp-content/plugins/teamtrope-activity-plus/img/system/Word-3.png" />'; ?></a> &nbsp; <a href="<?php echo bpfb_get_image_url($activity_blog_id) . $img; ?>" rel="<?php echo $rel;?>"><?php echo $info['filename'] . "." . $info['extension'];?></a>
                <?php
                        break;
                    case 'pdf': ?>
                    <a href="<?php echo bpfb_get_image_url($activity_blog_id) . $img; ?>" rel="<?php echo $rel;?>"><?php echo '<img class="bpfb_thumbnail_chooser_right" src="/wp-content/plugins/teamtrope-activity-plus/img/system/pdf-32.png" />'; ?></a> &nbsp; <a href="<?php echo bpfb_get_image_url($activity_blog_id) . $img; ?>" rel="<?php echo $rel;?>"><?php echo $info['filename'] . "." . $info['extension'];?></a>
                <?php
                        break;
                    
                } 
	      } 
      } ?>
</div>