jQuery(document).ready(function ($) {
	
	$("table > tbody > tr:nth-child(n+6)").css("display","none");
	
	$("#search").keyup(function() {
		var value = this.value;
	
		$("table").find(".rows_cpt").each(function(index) {
			var id = $(this).find("td").first().text();
			$(this).toggle(id.indexOf(value) !== -1);
		});
	});
	
	$("table > tbody > tr:nth-child(n+6)").css("display","none");
});
