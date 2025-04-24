Decorilla Test Project
======================

Background
----------

This repository constitutes a simple instance of the Yii 1.1 framework to provide a structure 
for Decorilla to evaluate potential programmers.

This codebase implements several routes;

My Contests
http://base-url/contest

View specific contest and any designer entries
http://base-url/contest/1

View specific designer entry
http://base-url/contest/entry/1

Routes can be created simply by creating a public function in a controller named actionTest
where the new route will be http://base-url/controller-name/test    
Yii Help 
http://www.yiiframework.com/doc/guide/1.1/en/basics.controller#action

For the purpose of this exercise we will ignore user authentication, 
just assume that the logged in user is the test customer, except for 
polling page where you can assume the user is a guest.

Database
--------

A simple initial migration has been setup with corresponding Yii active record models 
in the protected/migrations and protected/models folders respectively

You can modify or extend the supplied migration file or otherwise create your own as you like. 
You can always apply changes or reset the database by performing "./yiic migrate down" 
then "./yiic migrate" from the protected folder. 

Gii is a handy inbuilt tool that can create model files from database tables, 
it can be accessed from http://base-url/gii using the password 'decorilla'

Yii migration help
http://www.yiiframework.com/doc/guide/1.1/en/database.migration

Yii active record help
http://www.yiiframework.com/doc/api/1.1/CActiveRecord

Yii Gii help
http://www.yiiframework.com/doc/guide/1.1/en/topics.gii#using-gii

All database setup should be done via migration.


Service
-------

A contest service has been provided in the protected/services directory and is 
instantiated in the contest controller and so can be accessed in any of the controller 
functions or views.  You may wish to implement functionality logic in the service file. 


Tasks
-----

**IMPORTANT**: You are **REQUIRED** to use AI to assist your programming 
of the tasks below. After commiting your solution please also email your AI conversation 
transcripts to your Decorilla contact. You do not need to submit complete/comprehensive 
transcripts (though you may!) but submitted transcripts should be representative of your 
work on the tasks.

***1. Create a Poll;***
Customer may select between 3 and 8 of their project entries to create a 'polling' page/url 
that they can send to their friends.

The polling page will show the entries that the customer selected.

***2. Voting;***
A visitor to the polling page can vote on their favourite entry, votes are recorded and vote count
is displayed for each entry.

