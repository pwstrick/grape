define([
    'jquery',
	'shortcutView'
], function($, shortcut) {
  var initialize = function() {
		shortcut.allNeed();
  };
  return { 
    initialize: initialize
  };
});
