<?php

/**
 * This is the model class for table "poll".
 *
 * The followings are the available columns in table 'poll':
 * @property integer $id
 * @property integer $contest_id
 * @property integer $user_id
 * @property string $title
 * @property integer $created_at
 * @property string $url_token
 *
 * The followings are the available model relations:
 * @property Contest $contest
 * @property User $user
 * @property PollEntry[] $pollEntries
 * @property Vote[] $votes
 * @property ContestEntry[] $contestEntries
 */
class Poll extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Poll the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'poll';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('contest_id, user_id, created_at, url_token', 'required'),
            array('contest_id, user_id, created_at', 'numerical', 'integerOnly'=>true),
            array('title', 'length', 'max'=>100),
            array('url_token', 'length', 'max'=>32),
            array('url_token', 'unique'),
            // The following rule is used by search().
            array('id, contest_id, user_id, title, created_at, url_token', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'contest' => array(self::BELONGS_TO, 'Contest', 'contest_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'pollEntries' => array(self::HAS_MANY, 'PollEntry', 'poll_id'),
            'votes' => array(self::HAS_MANY, 'Vote', 'poll_id'),
            'contestEntries' => array(self::MANY_MANY, 'ContestEntry', 'poll_entry(poll_id, contest_entry_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'contest_id' => 'Contest',
            'user_id' => 'User',
            'title' => 'Title',
            'created_at' => 'Created At',
            'url_token' => 'URL Token',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('contest_id', $this->contest_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('url_token', $this->url_token, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Generates a unique URL token for this poll
     * @return string The generated token
     */
    public function generateUrlToken()
    {
        $this->url_token = md5(uniqid(mt_rand(), true));
        return $this->url_token;
    }

    /**
     * Before save operations
     */
    protected function beforeSave()
    {
        // Log the current state for debugging
        Yii::log('Poll beforeSave called. isNewRecord: ' . ($this->isNewRecord ? 'true' : 'false') . ', created_at: ' . $this->created_at, CLogger::LEVEL_INFO, 'poll');

        if ($this->isNewRecord) {
            // Make sure created_at is set
            if (empty($this->created_at)) {
                $this->created_at = time();
                Yii::log('Poll created_at set to: ' . $this->created_at, CLogger::LEVEL_INFO, 'poll');
            }

            if (empty($this->url_token)) {
                $this->generateUrlToken();
            }
        }

        return parent::beforeSave();
    }

    /**
     * Get the public URL for this poll
     * @return string The URL
     */
    public function getPublicUrl()
    {
        return Yii::app()->createAbsoluteUrl('poll/view', array('token' => $this->url_token));
    }

    /**
     * Get vote count for a specific contest entry in this poll
     * @param integer $contestEntryId The contest entry ID
     * @return integer The vote count
     */
    public function getVoteCount($contestEntryId)
    {
        return Vote::model()->count('poll_id=:pollId AND contest_entry_id=:entryId', array(
            ':pollId' => $this->id,
            ':entryId' => $contestEntryId,
        ));
    }

    /**
     * Get the entry with the most votes
     * @return ContestEntry|null The winning entry or null if no votes
     */
    public function getWinningEntry()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.contest_entry_id, COUNT(*) as vote_count';
        $criteria->condition = 't.poll_id=:pollId';
        $criteria->params = array(':pollId' => $this->id);
        $criteria->group = 't.contest_entry_id';
        $criteria->order = 'vote_count DESC';
        $criteria->limit = 1;

        $vote = Vote::model()->find($criteria);

        if ($vote) {
            return ContestEntry::model()->findByPk($vote->contest_entry_id);
        }

        return null;
    }
}
