define(function() {
	var regex = {};
	regex.checkEmail = function(str) {
		var rule = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/g;
		return rule.test(str);
	};
	regex.checkComInput = function(str) {//所有的输入都不能有的符号 TRUE表示通过 FALSE表示不通过
		var rule = /[,\|]+/g;
		return !rule.test(str);
	};
	regex.rtrim = function(str) {
		return str.replace(/(\s|\u00A0)+$/,'');
	};
	regex.ltrim = function(str) {
		return str.replace(/^(\s|\u00A0)+/,'');
	};
	regex.checkShortCode = function(str) {//短链码验证
		var rule = /^[A-Za-z0-9]+$/g;
		return rule.test(str);
	};
	regex.checkDigit = function(str) {
		var rule = /^\d+$/;
		return rule.test(str);
	};
	regex.checkCardNo = function(str) {
		//15位数身份证正则表达式
		var rule1 = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;
		//18位数身份证正则表达式
		var rule2 = /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/;
		return rule1.test(str) || rule2.test(str);
	};
	regex.checkMobile = function(str) {//TRUE表示通过 FALSE表示不通过
		var rule = /^1\d{10}/g;
		return rule.test(str);
	};
	regex.isPositiveInteger = function(str) {//正整数判断
		var rule = /^\d+$/;
		return rule.test(str);
	};
	regex.isTimeFormat = function(str) {//时间格式判断 10:00
		var rule = /^\d{1,2}:\d{1,2}$/;
		return rule.test(str);
	};
	return regex;
});
