<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 4:02 PM
 *
 * @var $contest Contest
 * @var $this ContestController
 */
?>
<h3>Contest</h3>
<?php $this->render('_listContest', array('data' => $contest)); ?>

<hr>

<h3>Contest Entries</h3>

<?php $dataProvider = $this->contestService->getContestEntryDataProvider();?>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_listContestEntry',
	'itemsCssClass' => 'contest-row-container'
)); ?>