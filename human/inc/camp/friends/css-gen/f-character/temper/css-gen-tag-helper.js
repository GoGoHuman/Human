$ = jQuery;
//  alert('hi');
$(document).ready(function () {
            window.parent.toggle_human_gif('hide');
});

human_tag_css_helper = $("#human_tag_css_helper");
var common_wrapers = ['human', 'header', 'footer', 'section', 'text'];
var humanClass = [];

//alert('hi');
function run_search_tag(toggle) {
            if (toggle) {
                        return;
            }
            console.log('run');

            $('h1,h2,h3,h4,h5,h6,ul,li,a,table,tr,td,button,p,img,ol,th,span').on("click", function (event) {
                        if ($('body').hasClass('search_tag')) {

                        } else {
                                    return;
                        }
                        event.preventDefault();

                        var thisClasses = '';
                        var thisElem = $(this);
                        if (thisElem.find('img').length > 0) {
                                    thisElem = $(this).find('img');
                        }
                        if (thisElem.attr('class') !== undefined) {
                                    console.log(thisElem.attr('class'));
                                    if (thisElem.attr('class')) {
                                                thisClasses = '.' + thisElem.attr('class').split(' ').join('.');
                                    }
                        }

                        var tag = thisElem.prop("tagName").toLowerCase();
                        //   console.log($(this));
                        var parents = [];
                        parents = thisElem.parents();


                        parents.each(function (index, z) {
                                    var parent = '';
                                    if ($(this).attr('class') != undefined) {
                                                parent = $(this).attr('class').split(' ');
                                    }
                                    //  console.log(parent);
                                    x = 0;
                                    for (j = 0; j < parent.length; j++) {
                                                var word = parent[j];
                                                var humanClassed = '';
                                                var humanClassed = [];
                                                //       console.log('humanClass -' + word);
                                                var k = 0;
                                                for (i = 0; i < common_wrapers.length; i++) {
                                                            //console.log(i);
                                                            //   console.log(common_wrapers[i]);


                                                            if (word.indexOf(common_wrapers[i]) > -1) {
                                                                        k++;
                                                                        //     console.log(word + '-' + k);
                                                                        humanClassed[k] = word;
                                                            } else {
                                                                        //     console.log(word + 'no' + this);
                                                            }
                                                }

                                                //   console.log(index);
                                                if (humanClassed.length > 0) {

                                                            humanClass[index] = [];
                                                            humanClass[index][j] = humanClassed;

                                                            // console.log(humanClassed);
                                                }
                                    }

                        });
                        //  humanClass = humanClass.reverse();
                        newClasses = '';
                        newClasses = [].concat.apply([], humanClass);
                        newClasses = [].concat.apply([], newClasses).reverse();
                        var humanClasses = [];

                        newClasses = newClasses.filter(function (element) {
                                    return element !== undefined;
                        });

                        newClasses = combinations(newClasses).reverse();

                        window.parent.tag_convert_test(newClasses, tag, thisClasses);



                        //    human_tag_css_helper.html(hc);



            });
}






