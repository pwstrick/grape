({
    appDir: './',
    baseUrl: './',
	dir: '../optimize',
	optimizeCss: 'standard',
	urlArgs: "bust=" + (new Date()).getTime(),
	//urlArgs: 'bust=v1.1',
	paths: {
		domReady: 'libs/require/domReady',
		jquery: 'libs/jquery/jquery',
		scrollLoading: 'libs/jquery/jquery.scrollLoading',
	    validate: 'libs/jquery/jquery.validate',
	    ui_custom: 'libs/jquery/jquery.ui.custom',
	    form: 'libs/jquery/jquery.form',
	    wizard: 'libs/jquery/jquery.wizard',
	    uniform: 'libs/uniform/jquery.uniform',
	    dragsort: 'libs/jquery/jquery.dragsort',
	    peity: 'libs/jquery/jquery.peity',
	    masked: 'libs/jquery/masked',
	    lightbox: 'libs/lightbox/lightbox',
	    uploadify: 'libs/uploadify/jquery.uploadify',
		backbone: 'libs/backbone/backbone',
		underscore: 'libs/underscore/underscore',
		cropzoom: 'libs/cropzoom/js/jquery.cropzoom',
		cropzoom_custom: 'libs/cropzoom/js/jquery-ui-1.9.2.custom.min',
		spin: 'libs/spin/spin',
		select2: 'libs/select2/select2',
		mustache: 'libs/mustache/mustache',
		artDialog: 'libs/artDialog/dialog',
		dialogPopup: 'libs/artDialog/popup',
		dialogConfig: 'libs/artDialog/dialog-config',
		dialogPlus: 'libs/artDialog/dialog-plus',
		dialogDrag: 'libs/artDialog/drag',
		datepicker: 'libs/datepair/bootstrap-datepicker',
	    gritter: 'libs/gritter/jquery.gritter',
	    autocompleter: 'libs/autocompleter/jquery.autocompleter',
	    ueditor: 'libs/ueditor/ueditor',
	    ueditor_config: 'libs/ueditor/ueditor.config',
	    echarts:'libs/echarts/echarts-original',
	    'echarts/chart/line': 'libs/echarts/echarts-original',
	    bootstrap_collapse: 'libs/bootstrap/bootstrap-collapse',
	    bootstrap_tooltip: 'libs/bootstrap/bootstrap-tooltip',
	    bootstrap_transition: 'libs/bootstrap/bootstrap-transition',
	    bootstrap_tab: 'libs/bootstrap/bootstrap-tab',
	    bootstrap_typeahead: 'libs/bootstrap/bootstrap-typeahead',
	    bootstrap_dropdown: 'libs/bootstrap/bootstrap-dropdown',
	    bootstrap_button: 'libs/bootstrap/bootstrap-button',
	    bootstrap_colorpicker: 'libs/colorpicker/bootstrap-colorpicker',
	    bootstrap_alert: 'libs/bootstrap/bootstrap-alert',
	    ztree: 'libs/ztree/jquery.ztree',
    	ztree_excheck: 'libs/ztree/jquery.ztree.excheck',
		constUtil: 'util/const',
		regexUtil: 'util/regex',
		viewUtil: 'util/view',
		modelUtil: 'util/model',
		comUtil: 'util/com',
		enumUtil: 'util/enum',
		shortcutView: 'views/util/shortcutView',
		widgetView: 'views/util/widgetView',
		dialogView: 'views/libs/dialogView',
	    echartsView: 'views/libs/echartsView',
	    peityView: 'views/libs/peityView',
	    uniformView: 'views/libs/uniformView',
	    maskedView: 'views/libs/maskedView',
	    tooltipView: 'views/libs/tooltipView',
	    ueditorView: 'views/libs/ueditorView',
	    uploadifyView: 'views/libs/uploadifyView',
	    validateView: 'views/libs/validateView',
	    wizardView: 'views/libs/wizardView',
	    select2View: 'views/libs/select2View',
	    ztreeView: 'views/libs/ztreeView',
	    cityView: 'views/libs/cityView',
	    cropzoomView: 'views/libs/cropzoomView',
	    publicModel: 'models/public/publicModel',
	    publicView: 'views/public/publicView',
	    homeModel: 'models/home/homeModel',
	    homeView: 'views/home/homeView',
	   	listModel: 'models/list/listModel',
	    listView: 'views/list/listView',
	    formModel: 'models/form/formModel',
	    formView: 'views/form/formView'
	},
    modules: [
		  {name: 'app/com/main'},
		  {name: 'app/public/main'},
		  {name: 'app/home/main'},
		  {name: 'app/list/main'},
		  {name: 'app/form/main'},
		  {name: 'app/form_layer/main'}
    ],
	fileExclusionRegExp: /^(r|build|jasmine|optimize)\.{0,1}(js|bat){0,1}$/,
	shim: {
    'backbone': {
          deps: ['underscore', 'jquery'],
          exports: 'Backbone'
    },
    'underscore': {
              exports: '_'
     },
     'dialogDrag': {
         deps: ['jquery']
     },
     'artDialog': {
         deps: ["jquery", "dialogPopup", "dialogConfig"]
     },
     'dialogPlus': {
         deps: ["jquery", "dialogPopup", "dialogConfig", "artDialog"]
     },
     'datepicker': {
       deps: ['jquery']
     },
     'peity': {
       deps: ['jquery']
     },
     'autocompleter': {
       deps: ['jquery']
     },
     'gritter': {
       deps: ['jquery']
     },
     'bootstrap_transition':{
       deps: ['jquery']
     },
     'bootstrap_tab':{
       deps: ['jquery']
     },
     'bootstrap_collapse':{
       deps: ['jquery', 'bootstrap_transition']
     },
     'bootstrap_tooltip':{
       deps: ['jquery']
     },
     'bootstrap_typeahead':{
       deps: ['jquery']
     },
     'bootstrap_dropdown':{
       deps: ['jquery']
     },
     'bootstrap_colorpicker': {
       deps: ['jquery']
     },
     'bootstrap_alert': {
       deps: ['jquery']
     },
     'uniform': {
       deps: ['jquery']
     },
     'uploadify': {
       deps: ['jquery']
     },
     'ui_custom': {
       deps: ['jquery']
     },
     'lightbox': {
       deps: ['jquery']
     },
     'ztree': {
       deps: ['jquery']
     },
     'ztree_excheck': {
       deps: ['jquery', 'ztree']
     },
     'wizard': {
       deps: ['jquery', 'ui_custom', 'validate']
     },
     'ueditor_config': {
     	deps: ['constUtil']
     },
     'ueditor': {
       deps: ['ueditor_config']
     },
     'scrollLoading': {
    	 deps: ['jquery']
     },
     'select2': {
    	 deps: ['jquery']
     },
     'dragsort': {
    	 deps: ['jquery']
     },
     'cropzoom': {
    	 deps: ['jquery', 'cropzoom_custom']
     },
     'cropzoom_custom': {
    	 deps: ['jquery']
     }
	}
})
