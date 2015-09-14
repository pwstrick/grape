define([
	'shortcutView',
	'homeView',
	'homeModel'
], function(shortcut, homeView, homeModel) {
  var initialize = function() {
		shortcut.allNeed();
		var model = new homeModel();
		var view = new homeView({model:model});
		view.lineChart();
		view.getOrders();
		view.getUsers();
  };
  return {
    initialize: initialize
  };
});
