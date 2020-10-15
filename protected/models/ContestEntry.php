<?php

/**
 * This is the model class for table "contest_entry".
 *
 * The followings are the available columns in table 'contest_entry':
 * @property integer $id
 * @property integer $designer_id
 * @property integer $contest_id
 * @property string $comments
 * @property string $primary_image_src
 *
 * The followings are the available model relations:
 * @property Contest $contest
 * @property User $designer
 */
class ContestEntry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContestEntry the static model class
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
		return 'contest_entry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('designer_id, contest_id', 'numerical', 'integerOnly'=>true),
			array('primary_image_src', 'length', 'max'=>255),
			array('comments', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, designer_id, contest_id, comments, primary_image_src', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contest' => array(self::BELONGS_TO, 'Contest', 'contest_id'),
			'designer' => array(self::BELONGS_TO, 'User', 'designer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'designer_id' => 'Designer',
			'contest_id' => 'Contest',
			'comments' => 'Comments',
			'primary_image_src' => 'Primary Image Src',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('designer_id',$this->designer_id);
		$criteria->compare('contest_id',$this->contest_id);
		$criteria->compare('comments',$this->comments,true);
		$criteria->compare('primary_image_src',$this->primary_image_src,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}