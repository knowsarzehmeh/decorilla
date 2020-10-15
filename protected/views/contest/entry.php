<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 4:47 PM
 *
 * @var $this ContestController
 * @var $contestEntry ContestEntry
 */
?>
<h3>Contest Entry</h3>
<?php $this->render('_listContestEntry', array('data' => $contestEntry)); ?>


<h3>For Contest</h3>

<?php $this->render('_listContest', array('data' => $contestEntry->contest)); ?>
