<?php

/**
 * This is the model class for table "vote".
 *
 * The followings are the available columns in table 'vote':
 * @property integer $id
 * @property integer $poll_id
 * @property integer $contest_entry_id
 * @property string $voter_ip
 * @property integer $created_at
 *
 * The followings are the available model relations:
 * @property Poll $poll
 * @property ContestEntry $contestEntry
 */
class Vote extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Vote the static model class
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
        return 'vote';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('poll_id, contest_entry_id, voter_ip', 'required'),
            array('poll_id, contest_entry_id, created_at', 'numerical', 'integerOnly'=>true),
            array('voter_ip', 'length', 'max'=>45),
            // Custom validator to ensure one vote per IP per poll
            array('voter_ip', 'validateUniqueVote'),
            // The following rule is used by search().
            array('id, poll_id, contest_entry_id, voter_ip, created_at', 'safe', 'on'=>'search'),
        );
    }

    /**
     * Custom validator to ensure one vote per IP per poll
     */
    public function validateUniqueVote($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $exists = self::model()->exists(
                'poll_id=:pollId AND voter_ip=:voterIp',
                array(':pollId' => $this->poll_id, ':voterIp' => $this->voter_ip)
            );
            
            if ($exists) {
                $this->addError($attribute, 'You have already voted in this poll.');
            }
        }
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
            'voter_ip' => 'Voter IP',
            'created_at' => 'Created At',
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
        $criteria->compare('voter_ip', $this->voter_ip, true);
        $criteria->compare('created_at', $this->created_at);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Before save operations
     */
    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_at = time();
        }
        
        return parent::beforeSave();
    }
}
