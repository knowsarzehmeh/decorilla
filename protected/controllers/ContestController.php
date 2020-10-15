<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 4:01 PM
 */
class ContestController extends Controller
{

	/**
	 * @var ContestService
	 */
	public $contestService;

	public function init(){
		$this->contestService = new ContestService();
	}

	public function actionIndex(){
		$this->render('index');
	}

	public function actionView($id){
		$contest = Contest::model()->findByPk($id);
		$this->render('view', array('contest' => $contest));
	}

	public function actionEntry($id){
		$contestEntry = ContestEntry::model()->findByPk($id);
		$this->render('entry', array('contestEntry' => $contestEntry));
	}

}