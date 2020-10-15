<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 4:18 PM
 *
 * @var $this ContestController
 * @var $data ContestEntry
 */
?>

<div style="border: 1px dotted gray;padding:20px; margin: 5px;">
	<a href="<?php echo $this->createUrl('entry', array('id' => $data->id)); ?>">
	<div>Submitted By: <?php echo $data->designer->user_name; ?></div>
	<div>Comments: <?php echo $data->comments; ?></div>
	<div><img style="width:300px;" src="<?php echo $data->primary_image_src; ?>"></div>
	</a>
</div>
