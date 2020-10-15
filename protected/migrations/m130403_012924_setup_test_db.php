<?php

class m130403_012924_setup_test_db extends CDbMigration
{
    public function safeUp()
    {

        //setup our tables
        $this->createTable('user', array(
            'id' => 'pk',
            'user_name' => 'varchar(100)',
            'user_type' => 'varchar(20)',
            'email' => 'string',
        ));

        $this->createTable('contest', array(
            'id' => 'pk',
            'user_id' => 'int  REFERENCES user(id)',
            'contest_title' => 'varchar(50)',
            'primary_image_src' => 'varchar(255)',
        ));

        $this->createTable('contest_entry', array(
            'id' => 'pk',
            'designer_id' => 'int REFERENCES user(id)',
            'contest_id' => 'int REFERENCES contest(id)',
            'comments' => 'string',
            'primary_image_src' => 'varchar(255)',
        ));

        //create a test customer
        $customer = new User();
        $customer->user_name = "Test Customer";
        $customer->user_type = "customer";
        $customer->email = "test_customer@decorilla.com";
        $customer->save();

        //create a test contest
        $contest = new Contest();
        $contest->user_id = $customer->id;
        $contest->contest_title = 'Redesign my living room!';
        $contest->primary_image_src = "/img/stock/LivingRoom5.jpg";
        $contest->save();

        //create a bunch of test designers and an entry for each
        for ($i = 1; $i <= 10; $i++) {
            $designer = new User();
            $designer->user_name = "Test Designer " . $i;
            $designer->user_type = "designer";
            $designer->email = "test_designer" . $i . "@decorilla.com";
            $designer->save();

            $contestEntry = new ContestEntry();
            $contestEntry->designer_id = $designer->id;
            $contestEntry->contest_id = $contest->id;
            $contestEntry->comments = "Check it out!";
            $contestEntry->primary_image_src = "/img/stock/LivingRoom" . (($i % 4) + 1) . ".jpg";
            $contestEntry->save();
        }

    }

    public function safeDown()
    {
        $this->dropTable('user');
        $this->dropTable('contest');
        $this->dropTable('contest_entry');
    }

}