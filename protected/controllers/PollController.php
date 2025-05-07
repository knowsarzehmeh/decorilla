<?php
/**
 * Controller for handling poll-related actions
 */
class PollController extends Controller
{
    /**
     * @var ContestService
     */
    public $contestService;

    /**
     * Initialize the controller
     */
    public function init()
    {
        $this->contestService = new ContestService();
    }

    /**
     * Creates a new poll
     */
    public function actionCreate()
    {
        // Log the POST data for debugging
        Yii::log('Poll creation POST data: ' . print_r($_POST, true), CLogger::LEVEL_INFO, 'poll');

        if (isset($_POST['contest_id']) && isset($_POST['contest_entries']) && is_array($_POST['contest_entries'])) {
            $contestId = (int)$_POST['contest_id'];
            $contestEntryIds = $_POST['contest_entries'];
            $title = isset($_POST['title']) ? $_POST['title'] : null;

            // Validate contest ID
            $contest = Contest::model()->findByPk($contestId);
            if (!$contest) {
                Yii::app()->user->setFlash('error', 'Invalid contest ID.');
                $this->redirect(array('contest/index'));
                return;
            }

            try {
                $poll = $this->contestService->createContestPoll($contestId, $contestEntryIds, $title);

                if ($poll) {
                    // Redirect to the poll view page
                    $this->redirect(array('view', 'token' => $poll->url_token));
                } else {
                    Yii::app()->user->setFlash('error', 'Failed to create poll.');
                    $this->redirect(array('contest/view', 'id' => $contestId));
                }
            } catch (Exception $e) {
                Yii::log('Poll creation error: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'poll');
                Yii::app()->user->setFlash('error', $e->getMessage());
                $this->redirect(array('contest/view', 'id' => $contestId));
            }
        } else {
            // Provide more detailed error message
            $errorMsg = 'Invalid request. ';
            if (!isset($_POST['contest_id'])) {
                $errorMsg .= 'Missing contest ID. ';
            }
            if (!isset($_POST['contest_entries'])) {
                $errorMsg .= 'No contest entries selected. ';
            } elseif (!is_array($_POST['contest_entries'])) {
                $errorMsg .= 'Contest entries must be an array. ';
            }

            Yii::log('Poll creation error: ' . $errorMsg . print_r($_POST, true), CLogger::LEVEL_ERROR, 'poll');
            throw new CHttpException(400, $errorMsg . 'Please do not repeat this request again.');
        }
    }

    /**
     * Displays a poll for voting
     * @param string $token The poll's unique token
     */
    public function actionView($token)
    {
        $poll = $this->loadPollByToken($token);

        // Check if the user has already voted
        $hasVoted = Vote::model()->exists(
            'poll_id=:pollId AND voter_ip=:voterIp',
            array(':pollId' => $poll->id, ':voterIp' => Yii::app()->request->userHostAddress)
        );

        $this->render('view', array(
            'poll' => $poll,
            'hasVoted' => $hasVoted,
        ));
    }

    /**
     * Handles voting on a poll entry
     */
    public function actionVote()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $response = array('success' => false);

            if (isset($_POST['poll_id']) && isset($_POST['entry_id'])) {
                $pollId = (int)$_POST['poll_id'];
                $entryId = (int)$_POST['entry_id'];

                // Check if the entry is part of the poll
                $exists = PollEntry::model()->exists(
                    'poll_id=:pollId AND contest_entry_id=:entryId',
                    array(':pollId' => $pollId, ':entryId' => $entryId)
                );

                if ($exists) {
                    // Check if the user has already voted
                    $hasVoted = Vote::model()->exists(
                        'poll_id=:pollId AND voter_ip=:voterIp',
                        array(':pollId' => $pollId, ':voterIp' => Yii::app()->request->userHostAddress)
                    );

                    if ($hasVoted) {
                        $response['error'] = 'You have already voted in this poll.';
                    } else {
                        // Create a new vote
                        $vote = new Vote();
                        $vote->poll_id = $pollId;
                        $vote->contest_entry_id = $entryId;
                        $vote->voter_ip = Yii::app()->request->userHostAddress;

                        if ($vote->save()) {
                            $response['success'] = true;
                            $poll = Poll::model()->findByPk($pollId);
                            $response['votes'] = $poll->getVoteCount($entryId);
                        } else {
                            $response['error'] = $vote->getErrors();
                        }
                    }
                } else {
                    $response['error'] = 'Invalid entry for this poll.';
                }
            } else {
                $response['error'] = 'Missing required parameters.';
            }

            echo CJSON::encode($response);
            Yii::app()->end();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Displays poll results
     * @param string $token The poll's unique token
     */
    public function actionResults($token)
    {
        $poll = $this->loadPollByToken($token);

        $this->render('results', array(
            'poll' => $poll,
        ));
    }

    /**
     * Loads a poll model by its token
     * @param string $token The poll's unique token
     * @return Poll The loaded model
     * @throws CHttpException If the poll cannot be found
     */
    protected function loadPollByToken($token)
    {
        $poll = Poll::model()->findByAttributes(array('url_token' => $token));

        if ($poll === null) {
            throw new CHttpException(404, 'The requested poll does not exist.');
        }

        return $poll;
    }
}
