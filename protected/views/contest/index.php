<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 4:02 PM
 *
 * @var $this ContestController
 */
?>

<h3>My Contests</h3>

<?php $dataProvider = $this->contestService->getContestDataProvider();?>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_listContest',
	'itemsCssClass' => 'contest-row-container'
)); ?>