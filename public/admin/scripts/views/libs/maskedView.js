define([
	'masked'
], function () {
	var layer ={};
	layer.form = function() {//表单特效
			$("#mask-phone").mask("(999) 999-9999", {completed:function(){alert("Callback action after complete");}});
			$("#mask-phoneExt").mask("(999) 999-9999? x99999");
			$("#mask-phoneInt").mask("+40 999 999 999");
			$("#mask-date").mask("99/99/9999");
			$("#mask-ssn").mask("999-99-9999");
			$("#mask-productKey").mask("a*-999-a999", { placeholder: "*" });
			$("#mask-eyeScript").mask("~9.99 ~9.99 999");
			$("#mask-percent").mask("99%");
	};

	return layer;
});
