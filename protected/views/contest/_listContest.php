<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 4:07 PM
 *
 * @var $this ContestController
 * @var $data Contest
 */
?>
<div>
	<a href="<?php echo $this->createUrl('view', array('id' => $data->id))?>">
		<div>Title: <?php echo $data->contest_title; ?></div>
		<div>Created By: <?php echo $data->user->user_name; ?></div>
		<div>This contest has <?php echo count($data->contestEntries); ?> entries</div>
		<div><img style="width:300px;" src="<?php echo $data->primary_image_src; ?>"></div>
	</a>
</div>
