$(document).ready(function () {
	$("._extra").tooltip({
		"html" : true,
		"title" : "PK : Primary key<br />UI : Unique<br />I : Index<br />AI : Auto increment<br />FK : Foreign key<br />UN : Unsigned",
		"placement" : "right"
	});
	$("._foreign_key").tooltip({
		"placement" : "right"
	});
	$("#_global_search").chosen().change(function(e) {
		window.location = $(this).val();
	});
	/**
	 * Hotkeys
	 */
	key.filter = function (event){
		var tagName = (event.target || event.srcElement).tagName;
		// ignore keypressed in any elements that support keyboard data input
		return !(tagName == "SELECT" || tagName == "TEXTAREA");
	}
	// Go to home
	key("alt+h", function(){
		window.location = "./index.html";
	});
	// Go to tables
	key("alt+t", function(){
		window.location = "./tables.html";
		return false;
	});
	// Go to indexes
	key("alt+i", function(){
		window.location = "./indexes.html";
		return false;
	});
	// Go to views
	key("alt+v", function(){
		window.location = "./views.html";
		return false;
	});
	// Go to global search
	key("alt+g", function(){
		$("#_global_search_chzn").trigger("mousedown");
		return false;
	});
	// Go to next search field
	key("alt+down", function(){
		var inputs = $(".form-search > input");

		switch (inputs.length) {
		case 0:
			break;
		case 1:
			inputs.focus();
			break;
		default:
			var top = (typeof $(":focus").offset() == "undefined")
				? 0 : $(":focus").offset().top;

			inputs.each(function() {
				if (top < $(this).offset().top) {
					$(this).focus();

					return false;
				}
			});
			break;
		}
	});
	// Go to previous search field
	key("alt+up", function(){
		var inputs = $(".form-search > input");

		switch (inputs.length) {
		case 0:
			break;
		case 1:
			inputs.focus();
			break;
		default:
			var top = (typeof $(":focus").offset() == "undefined")
				? 0 : $(":focus").offset().top;

			$(inputs.get().reverse()).each(function() {
				if ($(this).offset().top < top) {
					$(this).focus();

					return false;
				}
			});
			break;
		}
	});
});
