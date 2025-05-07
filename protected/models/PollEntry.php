<?php

/**
 * This is the model class for table "poll_entry".
 *
 * The followings are the available columns in table 'poll_entry':
 * @property integer $id
 * @property integer $poll_id
 * @property integer $contest_entry_id
 *
 * The followings are the available model relations:
 * @property Poll $poll
 * @property ContestEntry $contestEntry
 */
class PollEntry extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PollEntry the static model class
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
        return 'poll_entry';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('poll_id, contest_entry_id', 'required'),
            array('poll_id, contest_entry_id', 'numerical', 'integerOnly'=>true),
            // Ensure unique combination of poll_id and contest_entry_id
            array('poll_id, contest_entry_id', 'unique', 'className' => 'PollEntry', 'attributeNames' => array('poll_id', 'contest_entry_id'), 'message' => 'This entry is already in the poll.'),
            // The following rule is used by search().
            array('id, poll_id, contest_entry_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'poll' => array(self::BELONGS_TO, 'Poll', 'poll_id'),
            'contestEntry' => array(self::BELONGS_TO, 'ContestEntry', 'contest_entry_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'poll_id' => 'Poll',
            'contest_entry_id' => 'Contest Entry',
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
        $criteria->compare('poll_id', $this->poll_id);
        $criteria->compare('contest_entry_id', $this->contest_entry_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Get the number of votes for this poll entry
     * @return integer The vote count
     */
    public function getVoteCount()
    {
        return Vote::model()->count('poll_id=:pollId AND contest_entry_id=:entryId', array(
            ':pollId' => $this->poll_id,
            ':entryId' => $this->contest_entry_id,
        ));
    }
}
