<?php
/**
 * Partial view for displaying a poll entry
 *
 * @var $this PollController
 * @var $data PollEntry
 * @var $poll Poll
 * @var $hasVoted boolean
 */
?>

<div class="poll-entry" style="border: 1px solid #ddd; padding: 20px; margin: 10px 0; border-radius: 4px; position: relative;">
    <div class="entry-details">
        <div class="designer">Submitted By: <?php echo $data->contestEntry->designer->user_name; ?></div>
        <div class="comments">Comments: <?php echo $data->contestEntry->comments; ?></div>
        <div class="image">
            <img style="width: 100%; max-width: 400px;" src="<?php echo $data->contestEntry->primary_image_src; ?>">
        </div>
    </div>
    
    <?php if (!$hasVoted): ?>
    <div class="vote-section" style="margin-top: 15px;">
        <button class="vote-button btn btn-success" 
                data-poll-id="<?php echo $poll->id; ?>" 
                data-entry-id="<?php echo $data->contestEntry->id; ?>">
            Vote for this design
        </button>
    </div>
    <?php else: ?>
    <div class="vote-count-section" style="margin-top: 15px;">
        <div class="vote-count badge <?php echo $poll->getVoteCount($data->contestEntry->id) > 0 ? 'badge-success' : 'badge-info'; ?>">
            <?php echo $poll->getVoteCount($data->contestEntry->id); ?> votes
        </div>
    </div>
    <?php endif; ?>
</div>
