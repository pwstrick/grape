define([
    'jquery',
	'shortcutView',
	'widgetView',
	'homeView',
	'homeModel'
], function($, shortcut, widgetView, homeView, homeModel) {
  var initialize = function() {
		shortcut.allNeed();
		widgetView.menuCollapse('menu');
		var model = new homeModel();
		var view = new homeView({model:model});
		view.menuOrder();
  };
  return { 
    initialize: initialize
  };
});
