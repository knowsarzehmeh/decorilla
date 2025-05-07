<?php

class m250507_080505_create_poll_tables extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		// Create poll table with foreign keys inline
		$this->createTable('poll', array(
			'id' => 'pk',
			'contest_id' => 'int NOT NULL REFERENCES contest(id) ON DELETE CASCADE ON UPDATE CASCADE',
			'user_id' => 'int NOT NULL REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE',
			'title' => 'varchar(100)',
			'created_at' => 'int NOT NULL',
			'url_token' => 'varchar(32) NOT NULL',
		));

		// Create unique index for url_token
		$this->createIndex('UX_poll_url_token', 'poll', 'url_token', true);

		// Create poll_entry table with foreign keys inline
		$this->createTable('poll_entry', array(
			'id' => 'pk',
			'poll_id' => 'int NOT NULL REFERENCES poll(id) ON DELETE CASCADE ON UPDATE CASCADE',
			'contest_entry_id' => 'int NOT NULL REFERENCES contest_entry(id) ON DELETE CASCADE ON UPDATE CASCADE',
		));

		// Create unique index to prevent duplicate entries in a poll
		$this->createIndex('UX_poll_entry_unique', 'poll_entry', 'poll_id, contest_entry_id', true);

		// Create vote table with foreign keys inline
		$this->createTable('vote', array(
			'id' => 'pk',
			'poll_id' => 'int NOT NULL REFERENCES poll(id) ON DELETE CASCADE ON UPDATE CASCADE',
			'contest_entry_id' => 'int NOT NULL REFERENCES contest_entry(id) ON DELETE CASCADE ON UPDATE CASCADE',
			'voter_ip' => 'varchar(45) NOT NULL',
			'created_at' => 'int NOT NULL',
		));

		// Create unique index to prevent duplicate votes from same IP for same poll
		$this->createIndex('UX_vote_ip_unique', 'vote', 'poll_id, voter_ip', true);

		// Create index for counting votes efficiently
		$this->createIndex('IX_vote_count', 'vote', 'poll_id, contest_entry_id');
	}

	public function safeDown()
	{
		// Drop tables in reverse order to avoid foreign key constraints
		$this->dropTable('vote');
		$this->dropTable('poll_entry');
		$this->dropTable('poll');
	}
}