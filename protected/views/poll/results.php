<?php
/**
 * View for displaying poll results
 *
 * @var $this PollController
 * @var $poll Poll
 */

// Calculate total votes
$totalVotes = 0;
$entryVotes = array();

foreach ($poll->pollEntries as $entry) {
    $votes = $poll->getVoteCount($entry->contestEntry->id);
    $entryVotes[$entry->contestEntry->id] = $votes;
    $totalVotes += $votes;
}

// Sort entries by vote count (descending)
usort($poll->pollEntries, function($a, $b) use ($entryVotes) {
    return $entryVotes[$b->contestEntry->id] - $entryVotes[$a->contestEntry->id];
});

// Find the winning entry
$winningEntry = null;
$maxVotes = 0;

foreach ($poll->pollEntries as $entry) {
    $votes = $entryVotes[$entry->contestEntry->id];
    if ($votes > $maxVotes) {
        $maxVotes = $votes;
        $winningEntry = $entry;
    }
}
?>

<div class="poll-results-container">
    <div class="poll-header">
        <h2><?php echo $poll->title ? CHtml::encode($poll->title) : 'Results for "' . CHtml::encode($poll->contest->contest_title) . '"'; ?></h2>
        
        <div class="poll-info">
            <p>Created by: <?php echo CHtml::encode($poll->user->user_name); ?></p>
            <p>Created on: <?php echo date('F j, Y', $poll->created_at); ?></p>
            <p>Total votes: <?php echo $totalVotes; ?></p>
        </div>
        
        <div class="share-section" style="margin: 15px 0;">
            <h4>Share this poll</h4>
            <input type="text" class="share-url" value="<?php echo $poll->getPublicUrl(); ?>" 
                   style="width: 100%; padding: 5px;" onclick="this.select();" readonly>
        </div>
    </div>
    
    <?php if ($winningEntry && $maxVotes > 0): ?>
    <div class="winning-entry" style="margin-bottom: 30px; padding: 20px; background-color: #f8f8f8; border: 2px solid #5cb85c; border-radius: 4px;">
        <h3 style="color: #5cb85c;">Winning Entry</h3>
        <div class="entry-details">
            <div class="designer">Submitted By: <?php echo $winningEntry->contestEntry->designer->user_name; ?></div>
            <div class="comments">Comments: <?php echo $winningEntry->contestEntry->comments; ?></div>
            <div class="image">
                <img style="width: 100%; max-width: 400px;" src="<?php echo $winningEntry->contestEntry->primary_image_src; ?>">
            </div>
            <div class="vote-stats" style="margin-top: 15px;">
                <div class="vote-count badge badge-success" style="font-size: 18px; padding: 10px;">
                    <?php echo $maxVotes; ?> votes (<?php echo $totalVotes > 0 ? round(($maxVotes / $totalVotes) * 100) : 0; ?>%)
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <h3>All Entries</h3>
    
    <div class="poll-entries">
        <?php foreach ($poll->pollEntries as $entry): ?>
            <?php $votes = $entryVotes[$entry->contestEntry->id]; ?>
            <div class="poll-entry" style="border: 1px solid #ddd; padding: 20px; margin: 10px 0; border-radius: 4px; position: relative;">
                <div class="entry-details">
                    <div class="designer">Submitted By: <?php echo $entry->contestEntry->designer->user_name; ?></div>
                    <div class="comments">Comments: <?php echo $entry->contestEntry->comments; ?></div>
                    <div class="image">
                        <img style="width: 100%; max-width: 400px;" src="<?php echo $entry->contestEntry->primary_image_src; ?>">
                    </div>
                </div>
                
                <div class="vote-stats" style="margin-top: 15px;">
                    <div class="progress" style="height: 25px; margin-bottom: 10px;">
                        <div class="bar" style="width: <?php echo $totalVotes > 0 ? round(($votes / $totalVotes) * 100) : 0; ?>%; 
                                                background-color: #5cb85c; height: 100%; line-height: 25px; color: white;">
                            <?php echo $totalVotes > 0 ? round(($votes / $totalVotes) * 100) : 0; ?>%
                        </div>
                    </div>
                    <div class="vote-count badge <?php echo $votes > 0 ? 'badge-success' : 'badge-info'; ?>">
                        <?php echo $votes; ?> votes
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="back-link" style="margin-top: 20px; text-align: center;">
        <a href="<?php echo $this->createUrl('view', array('token' => $poll->url_token)); ?>" class="btn btn-primary">
            Back to Poll
        </a>
    </div>
</div>

<style type="text/css">
.poll-results-container {
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
