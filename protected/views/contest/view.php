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

<?php if(Yii::app()->user->hasFlash('error')): ?>
<div class="flash-error">
    <?php echo Yii::app()->user->getFlash('error'); ?>
</div>
<?php endif; ?>

<div class="poll-creation-form" style="margin-bottom: 20px; padding: 10px; background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 4px;">
    <h4>Create a Poll</h4>
    <p>Select 3-8 entries to create a poll that you can share with friends.</p>

    <form id="poll-form" action="<?php echo $this->createUrl('/poll/create'); ?>" method="post">
        <input type="hidden" name="contest_id" value="<?php echo $contest->id; ?>">

        <div class="">
            <label for="poll-title">Poll Title (optional):</label>
            <input type="text" id="poll-title" name="title" class="span5" placeholder="Enter a title for your poll">
        </div>

        <div id="poll-entries-container">
            <!-- Entries will be selected in the list below -->
        </div>

        <div class=" buttons" style="margin-top: 10px;">
            <input type="submit" value="Create Poll" class="btn btn-primary" id="create-poll-btn" disabled>
            <span id="selection-status">Please select 3-8 entries</span>
        </div>
    </form>
</div>

<div id="contest-entries-list">
    <?php $dataProvider = $this->contestService->getContestEntryDataProvider($contest->id);?>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_listContestEntry',
        'itemsCssClass' => 'contest-row-container'
    )); ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Add checkboxes to each contest entry
    $('.contest-row-container > div').each(function() {
        var entryId = $(this).find('a').attr('href').split('/').pop();
        var checkbox = $('<input type="checkbox" class="poll-entry-checkbox" name="contest_entries[]" value="' + entryId + '">');
        $(this).prepend(checkbox);
    });

    // Handle checkbox changes
    $('.poll-entry-checkbox').change(function() {
        var checkedCount = $('.poll-entry-checkbox:checked').length;
        var $status = $('#selection-status');
        var $button = $('#create-poll-btn');

        if (checkedCount >= 3 && checkedCount <= 8) {
            $status.text(checkedCount + ' entries selected');
            $button.prop('disabled', false);
        } else {
            $status.text('Please select 3-8 entries (currently ' + checkedCount + ' selected)');
            $button.prop('disabled', true);
        }
    });

    // Form validation
    $('#poll-form').submit(function(e) {
        var checkedCount = $('.poll-entry-checkbox:checked').length;

        if (checkedCount < 3 || checkedCount > 8) {
            e.preventDefault();
            alert('Please select between 3 and 8 entries for your poll.');
            return false;
        }

        return true;
    });
});
</script>

<style type="text/css">
.poll-entry-checkbox {
    margin-right: 10px;
    float: left;
    margin-top: 20px;
}
#selection-status {
    margin-left: 10px;
    color: #666;
}
.flash-error {
    padding: 10px;
    margin-bottom: 10px;
    background-color: #ffeeee;
    border: 1px solid #ffcccc;
    color: #cc0000;
    border-radius: 4px;
}
</style>