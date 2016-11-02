jQuery(document).ready(function($){
    
        function escapeRegExp(str) {
                 return str.replace(/'/g,"&#39;").replace(/"/g,'\\"');
       }
       function stripslashes(str) {
  //       discuss at: http://phpjs.org/functions/stripslashes/
  //      original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      improved by: Ates Goral (http://magnetiq.com)
  //      improved by: marrtins
  //      improved by: rezna
  //         fixed by: Mick@el
  //      bugfixed by: Onno Marsman
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //         input by: Rick Waldron
  //         input by: Brant Messenger (http://www.brantmessenger.com/)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //        example 1: stripslashes('Kevin\'s code');
  //        returns 1: "Kevin's code"
  //        example 2: stripslashes('Kevin\\\'s code');
  //        returns 2: "Kevin\'s code"

  return (str + '')
    .replace(/\\(.?)/g, function(s, n1) {
      switch (n1) {
        case '\\':
          return '\\';
        case '0':
          return '\u0000';
        case '':
          return '';
        default:
          return n1;
      }
    });
}


function esc_q(str){
    
    var str1 = replaceAll(str,'@quot@','"');
    console.log(str1);
   return replaceAll(str1,"@squot@","'");
   
}

/*
 *  @param Add New Food Menu Category  
 */

$('.fmenu_category_misc_plus').on('click',function(){
           
        $("#new_cat_submit").fadeIn(800);
        var id ="prices_"+ $('.category_prices').length+1;
        $("#cat_misc").append('<div class="cat_misc_wrap"><input type="text" name="category_miscs[]" value="" class="category_prices" id="'+id+'"><span class="del_cat_misc">[x]</span></div>');
        $("#"+id).val($('.fmenu_category_misc').val());
        $('.fmenu_category_misc').removeAttr('value');
                 
 });
          

          
$('.scrum_edit_fmenu_cats').live("click",function(){
              
                  var options = $(".options_"+$(this).attr('id')).html();
                  opts = options.split(", ");
                  for(i=0;i<opts.length;i++){
                      if(opts[i].length !== 0){
                           var id = "prices_"+$(".category_prices").length + i;
                            $("#cat_misc").append('<div class="cat_misc_wrap"><input type="text" name="category_miscs[]" class="category_prices" value="" id="'+id+'"><span class="del_cat_misc">[x]</span></div>');
                              $("#"+id).val(opts[i]);
                      }
                  }
                 $('input[name=fmenu_category]').val($(this).attr('id'));
          });
          
          $('.plus_product').on('click',function(){
               
                   var item = esc_q($("#fmenu_item").val());
                   var n_item = $("#fmenu_item").val();
                   var cats = $("#cats :selected").val();
                   
                   var catname = esc_q(cats.split('#')[0]);  
                   var n_catname = cats.split('#')[0];  
                   var opts = '<h3>New Product Name: <input type="text" value="" name="new_product_name" class="new_product_name"> for '+catname+' category</h3>';
                       //     copts[i] = $(selected).value; 
                   $("#new_product_options").append(opts); $('.new_product_name').val(item);
          
                       
                         console.log(cats);
                         var copts = cats.split('#')[1].split(',');
                         
                        var  opt = '<h4>'+catname+'</h4><input type="hidden" name="category_option_value[]" value="desc"><textarea name="product_price[]"></textarea><input type="hidden" name="product_price_category" value="'+n_catname+'">';
                   
                        $("#new_product_options").append(opt);
                        for(j=0;j<copts.length-1;j++){
                             
                                var id = "price_tags_"+copts.length + j;
                               var  opts = '<br>'+esc_q(copts[j])+'<br><input type="hidden" name="category_option_value[]" value="'+copts[j]+'" id="'+id+'"> <input type="text" name="product_price[]" >';
                               $("#new_product_options").append(opts); $('#'+id).val(copts[j]);
                               console.log(opts);
                       }
                        var  optend = '<button type="submit">Save</button>';
                    
                   $("#new_product_options").append(optend);
          });
          
          $('.del_cat_misc').live('click',function(){
                     
                     $(this).parent().remove();
                     
          });
          $(".cat_del").live('click',function(e){
              e.preventDefault(); 
               if(confirm("Are you sure you want to delete this category? All menu items associated with this category will be deleted too!")){
                      location.assign( $(this).attr("href"));
              }
                  else{
                       return false;
           }
          });
          
          $(".scrum_del_product").live('click',function(e){
              e.preventDefault();
              $(this).parents('.scrum_sort_row').remove();
          });
    
    
         $("#update_products_submit").on('click', function(e){
             
               e.preventDefault();
               $(".old_product_name").removeAttr('disabled');
               $("#update_products_form").submit();
             
         });
    $(".scrum_sortable tbody").sortable();
    
        $( ".scrum_sortable tbody" ).disableSelection();
})
