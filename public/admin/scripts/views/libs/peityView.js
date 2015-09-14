define([
	'peity'
], function () {
	var chart ={};
	chart.peity = function() {
				$.fn.peity.defaults.line = {
					delimiter: ",",
					fill: "#c6d9fd",
					height: 24,
					max: null,
					min: 0,
					stroke: "#4d89f9",
					strokeWidth: 1,
					width: 50
				};
				$.fn.peity.defaults.bar = {
					delimiter: ",",
					fill: ["#4d89f9"],
					height: 24,
					max: null,
					min: 0,
					padding: 0.1,
					width: 50
				};
				$(".peity_line_good span").peity("line", {
					colour: "#57a532",
					strokeColour: "#459D1C"
				});
				$(".peity_line_bad span").peity("line", {
					colour: "#FFC4C7",
					strokeColour: "#BA1E20"
				});
				$(".peity_line_neutral span").peity("line", {
					colour: "#CCCCCC",
					strokeColour: "#757575"
				});
				$(".peity_bar_good span").peity("bar", {
					colour: "#459D1C"
				});
				$(".peity_bar_bad span").peity("bar", {
					colour: "#BA1E20"
				});
				$(".peity_bar_neutral span").peity("bar", {
					colour: "#4fb9f0"
				});
	};

	return chart;
});
