<?php

class userService extends Service {

	public function getUser() {
		$userModel = InitPHP::getMysqlDao('member');
		return 'seec';
	}
	
	public function getName() {
		
		return 'pwstrick';
	}
}