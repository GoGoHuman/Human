/**
 * @package: SCRUM
 * @subpackage: SCRUM Brain Character JS
 * @author: SergeDirect
 * @param: SCRUM Brain Character JS
 */

jQuery(document).ready(function($){

	$(function() {
		 $( "#dates" ).datepicker({ minDate: 1 });
				
	                         $("#ui-datepicker-div").css({position:'absolute',top:-2000});                            
	});
});



/*
 * 
 * @param {type} data
 * @param {type} callback
 * @returns returns ajax callback to specified function
 */

function scrum_ajax(data,callback,$type){
    
    jQuery.ajax({
          url: ajaxurl,
          data: data,
          type: 'POST',
          datatype:'html',
          async: false,
          cache: false,
          timeout: 3000,
          success: function (response) {
                //console.log(response)
                window[callback](response);
          }
    });
    
    
}

 jQuery(function($) {
          
       
           $( ".scrum_sortable" ).sortable()
           $( ".scrum_sortable" ).disableSelection();
           $( ".scrum_sortable" ).sortable({
                         items: ".scrum_sortable",
                          handle: ".scrum_ui_handle",
                          containment: "parent"
                          
           });
           
           $(".scrum_del").live("click",function(){
                   $(this).parents(".scrum_parent").remove();
           });
           
  });


/*
 * 
 * @param {type} id
 * @param {type} act
 * @returns Toogle checkboxes
 */

function scrum_checkbox(id,act){
    
       if(act === "s_true"){
              jQuery('#'+id).val("s_true");
              jQuery('#'+id).attr('checked', true);
       }else{
              jQuery('#'+id).val("s_false");
              jQuery('#'+id).attr('checked', false);
       }
    
    
}


function escapeRegExp(string) {
    
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
    
}

/*
 * 
 * @param {type} str
 * @param {type} find
 * @param {type} replace
 * @returns string with replaced multiple values
 */

function replaceAll(str, find, replace) {
    
     return str.replace(new RegExp(find, 'g'), replace);
  
}


/*
 * @param {type}  str -> Url Param
 * @returns url Param Value
 */

var scrum_uid = function scrum_uid(sParam) {
    
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
        
    }
    
};