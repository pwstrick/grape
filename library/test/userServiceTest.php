<?php

class userServiceTest {
	
	//单元测试核心函数 run($self)
	public function run($self) {
		//$self->add_data(array('123'), 'see2c', '='); //添加预测数据
		//$self->test('getUser'); //对getUser函数进行预测 会自动清空上面的预测数据
		
		$self->add_data(array('123'), 'pwstrick', '=');
		$self->test('getName');
	}
}