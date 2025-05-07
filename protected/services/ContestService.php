<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 6:29 PM
 */
class ContestService {

	/**
	 * Creates a new poll for a contest with the selected entries
	 *
	 * @param integer $contestId The contest ID
	 * @param array $contestEntryIds Array of contest entry IDs to include in the poll
	 * @param string $title Optional title for the poll
	 * @return Poll|null The created poll or null on failure
	 * @throws CException If validation fails
	 */
	public function createContestPoll($contestId, $contestEntryIds, $title = null){
		// Validate number of entries (between 3 and 8)
		if (count($contestEntryIds) < 3 || count($contestEntryIds) > 8) {
			throw new CException('A poll must contain between 3 and 8 entries.');
		}

		// Validate that all entries belong to the specified contest
		foreach ($contestEntryIds as $entryId) {
			$entry = ContestEntry::model()->findByPk($entryId);
			if (!$entry || $entry->contest_id != $contestId) {
				throw new CException('One or more selected entries do not belong to this contest.');
			}
		}

		// Start a transaction
		$transaction = Yii::app()->db->beginTransaction();

		try {
			// Create the poll
			$poll = new Poll();
			$poll->contest_id = $contestId;
			$poll->user_id = 1; // Assuming user 1 is the test customer
			$poll->title = $title;
			$poll->created_at = time(); // Explicitly set the created_at timestamp
			$poll->generateUrlToken();

			if (!$poll->save()) {
				throw new CException('Failed to create poll: ' . print_r($poll->getErrors(), true));
			}

			// Add the selected entries to the poll
			foreach ($contestEntryIds as $entryId) {
				$pollEntry = new PollEntry();
				$pollEntry->poll_id = $poll->id;
				$pollEntry->contest_entry_id = $entryId;

				if (!$pollEntry->save()) {
					throw new CException('Failed to add entry to poll: ' . print_r($pollEntry->getErrors(), true));
				}
			}

			// Commit the transaction
			$transaction->commit();

			return $poll;
		} catch (Exception $e) {
			// Roll back the transaction on error
			$transaction->rollback();
			throw $e;
		}
	}

	/**
	 * Get contest data provider
	 *
	 * @return CActiveDataProvider
	 */
	public function getContestDataProvider(){
		$dataProvider = new CActiveDataProvider('Contest');
		return $dataProvider;
	}

	/**
	 * Get contest entry data provider
	 *
	 * @param integer $contestId Optional contest ID to filter entries
	 * @return CActiveDataProvider
	 */
	public function getContestEntryDataProvider($contestId = null){
		$criteria = new CDbCriteria();

		if ($contestId !== null) {
			$criteria->compare('contest_id', $contestId);
		}

		$dataProvider = new CActiveDataProvider('ContestEntry', array(
			'criteria' => $criteria,
		));

		return $dataProvider;
	}

	/**
	 * Get poll entry data provider
	 *
	 * @param integer $pollId The poll ID
	 * @return CActiveDataProvider
	 */
	public function getPollEntryDataProvider($pollId){
		$criteria = new CDbCriteria();
		$criteria->with = array('contestEntry');
		$criteria->compare('t.poll_id', $pollId);

		$dataProvider = new CActiveDataProvider('PollEntry', array(
			'criteria' => $criteria,
		));

		return $dataProvider;
	}

}