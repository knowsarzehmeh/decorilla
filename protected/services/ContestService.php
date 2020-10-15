<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Joshua Shammay
 * Date: 3/04/13
 * Time: 6:29 PM
 */
class ContestService {

	public function createContestPoll($contestEntries){
		//do something here
	}

	public function getContestDataProvider(){
		$dataProvider = new CActiveDataProvider('Contest');
		return $dataProvider;
	}

	public function getContestEntryDataProvider(){
		$dataProvider = new CActiveDataProvider('ContestEntry');
		return $dataProvider;
	}

}