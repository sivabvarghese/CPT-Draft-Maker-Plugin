

function myFunction() {
	var input, filter, table, tr, td, i, txtValue;
	input = document.getElementById("myInput");
	filter = input.value.toUpperCase();
	table = document.getElementsByClassName("min-w-full");
	console.log(table);
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++) {
	  td = tr[i].getElementsByTagName("td")[0];
	  if (td) {
		txtValue = td.textContent || td.innerText;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
		  tr[i].style.display = "";
		} else {
		  tr[i].style.display = "none";
		}
	  }       
	}
  }

  jQuery(document).ready(function ($) {
	
	
	console.log("Test");
	jQuery("#referrals-head").text("Sales History");
	
	//jQuery("#referrals_nav_item").text("Sales History");
	
	var $input = jQuery('<input type="text" value="Search Sales History" id="myInput" onkeyup="myFunction()" />');
	$input.appendTo(jQuery("#portal-content-wrap"));
	//recursiveReplace(document.body," Referrals","Sales History");
	//recursiveReplace(document.body,"Referrals","Sales History");

	var tbl = $('.min-w-full tr').get().map(function(row) {
		return $(row).find('td').get().map(function(cell) {
		  return $(cell).html();
		});
	  });
	

});

function recursiveReplace(node,text_one,text_two) {
    if (node.nodeType == 3) { // text node
		if(text_one=="Referrals"){
			console.log(text_one);
		}
        node.nodeValue = node.nodeValue.replace(text_one, text_two);
        return;
    }

    if (node.nodeType == 1) { // element
        jQuery(node).contents().each(function () {
            recursiveReplace(this);
        });
    }
}







 
