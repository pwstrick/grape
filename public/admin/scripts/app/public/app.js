define([
	'publicView',
	'publicModel'
], function(publicView, publicModel) {
  var initialize = function() {
			var model = new publicModel();
			var view = new publicView({model:model});
			view.visibleForgetPage();
			view.login();
			view.revocer();
  };
  return {
    initialize: initialize
  };
});
