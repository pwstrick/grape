define([
    'jquery',
	'shortcutView',
	'cropzoomView'
], function($, shortcut, cropzoom) {
  var initialize = function() {
		shortcut.allNeed();
		cropzoom.setCropzoom();
  };
  return { 
    initialize: initialize
  };
});
