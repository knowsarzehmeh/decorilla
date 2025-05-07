<?php
/**
 * View for displaying a poll for voting
 *
 * @var $this PollController
 * @var $poll Poll
 * @var $hasVoted boolean
 */
?>

<div class="poll-container">
    <div class="poll-header">
        <h2><?php echo $poll->title ? CHtml::encode($poll->title) : 'Poll for "' . CHtml::encode($poll->contest->contest_title) . '"'; ?></h2>
        
        <div class="poll-info">
            <p>Created by: <?php echo CHtml::encode($poll->user->user_name); ?></p>
            <p>Created on: <?php echo date('F j, Y', $poll->created_at); ?></p>
        </div>
        
        <?php if ($hasVoted): ?>
        <div class="alert alert-success">
            Thank you for voting! You can see the current results below.
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            Please vote for your favorite design below.
        </div>
        <?php endif; ?>
        
        <div class="share-section" style="margin: 15px 0;">
            <h4>Share this poll</h4>
            <input type="text" class="share-url" value="<?php echo $poll->getPublicUrl(); ?>" 
                   style="width: 100%; padding: 5px;" onclick="this.select();" readonly>
        </div>
    </div>
    
    <div class="poll-entries">
        <?php foreach ($poll->pollEntries as $entry): ?>
            <?php $this->renderPartial('_pollEntry', array(
                'data' => $entry,
                'poll' => $poll,
                'hasVoted' => $hasVoted,
            )); ?>
        <?php endforeach; ?>
    </div>
    
    <?php if ($hasVoted): ?>
    <div class="results-link" style="margin-top: 20px; text-align: center;">
        <a href="<?php echo $this->createUrl('results', array('token' => $poll->url_token)); ?>" class="btn btn-primary">
            View Detailed Results
        </a>
    </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Handle vote button clicks
    $('.vote-button').click(function() {
        var $button = $(this);
        var pollId = $button.data('poll-id');
        var entryId = $button.data('entry-id');
        
        // Disable all vote buttons to prevent multiple votes
        $('.vote-button').prop('disabled', true);
        
        // Send the vote via AJAX
        $.ajax({
            url: '<?php echo $this->createUrl('vote'); ?>',
            type: 'POST',
            data: {
                poll_id: pollId,
                entry_id: entryId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Reload the page to show results
                    window.location.reload();
                } else {
                    alert('Error: ' + (response.error || 'Failed to record your vote.'));
                    // Re-enable vote buttons
                    $('.vote-button').prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred while trying to record your vote. Please try again.');
                // Re-enable vote buttons
                $('.vote-button').prop('disabled', false);
            }
        });
    });
});
</script>

<style type="text/css">
.poll-container {
    max-width: 800px;
    margin: 0 auto;
}
.poll-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.share-url {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
}
.vote-button {
    padding: 8px 15px;
}
.vote-count {
    font-size: 16px;
    padding: 8px 15px;
}
.badge-success {
    background-color: #5cb85c;
}
.badge-info {
    background-color: #5bc0de;
}
</style>
