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
        <?php echo CHtml::hiddenField(Yii::app()->request->csrfTokenName, Yii::app()->request->csrfToken); ?>

        <div class="">
            <label for="poll-title">Poll Title (optional):</label>
            <input type="text" id="poll-title" name="title" class="span5" placeholder="Enter a title for your poll">
        </div>

        <div id="poll-entries-container">
            <!-- Hidden inputs for selected entries will be added here dynamically -->
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
    // Handle checkbox changes
    $(document).on('change', '.poll-entry-checkbox', function() {
        var checkedCount = $('.poll-entry-checkbox:checked').length;
        var $status = $('#selection-status');
        var $button = $('#create-poll-btn');
        var $container = $('#poll-entries-container');

        // Clear the container
        $container.empty();

        // Add hidden inputs for each checked checkbox
        $('.poll-entry-checkbox:checked').each(function() {
            var entryId = $(this).val();
            $container.append('<input type="hidden" name="contest_entries[]" value="' + entryId + '">');
        });

        if (checkedCount >= 3 && checkedCount <= 8) {
            $status.text(checkedCount + ' entries selected');
            $button.prop('disabled', false);
        } else {
            $status.text('Please select 3-8 entries (currently ' + checkedCount + ' selected)');
            $button.prop('disabled', true);
        }

        // Debug - log selected entries
        console.log('Selected entries:', $('.poll-entry-checkbox:checked').map(function() {
            return $(this).val();
        }).get());
        console.log('Hidden inputs:', $('#poll-entries-container input').map(function() {
            return $(this).val();
        }).get());
    });

    // Form validation
    $('#poll-form').submit(function(e) {
        var checkedCount = $('.poll-entry-checkbox:checked').length;
        var $container = $('#poll-entries-container');

        // Clear and update the hidden inputs one more time to be sure
        $container.empty();
        $('.poll-entry-checkbox:checked').each(function() {
            var entryId = $(this).val();
            $container.append('<input type="hidden" name="contest_entries[]" value="' + entryId + '">');
        });

        if (checkedCount < 3 || checkedCount > 8) {
            e.preventDefault();
            alert('Please select between 3 and 8 entries for your poll.');
            return false;
        }

        // Make sure we have hidden inputs before submitting
        if ($('#poll-entries-container input').length === 0) {
            e.preventDefault();
            alert('Please select at least 3 entries for your poll.');
            return false;
        }

        // Debug - log form data before submission
        console.log('Form data:', $(this).serialize());
        console.log('Hidden inputs count:', $('#poll-entries-container input').length);
        console.log('Hidden inputs values:', $('#poll-entries-container input').map(function() {
            return $(this).val();
        }).get());

        return true;
    });

    // Trigger the change event to update the status initially
    if ($('.poll-entry-checkbox').length > 0) {
        $('.poll-entry-checkbox').first().trigger('change');
    }
});
</script>

<style type="text/css">
.poll-entry-checkbox {
    margin-right: 10px;
    width: 20px;
    height: 20px;
    cursor: pointer;
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