define(['jquery'], function($) {

    return {
        init: function(quickrecording) {

            $( document ).ready(function() {

                window.ange = '';
                window.selected = '';
                window.htmlID = 0;
                window.entryID = 0;

                var pathname = window.location.pathname;
                var activePages = ['/mod/journal/report.php']; //

                if (contains(pathname, activePages) === true){

                    $(document).on("mouseup").unbind();
                    $(document).on("mouseup", function (e) {
                        if (contains(pathname, activePages) == true){

                            if (window.getSelection) {
                                window.selected = window.getSelection();
                            } else if (document.getSelection) {
                                window.selected = document.getSelection();
                            } else if (document.selection) {
                                window.selected = document.selection.createRange().text;
                            }

                            window.range = window.selected.getRangeAt(0);

                            documentFragment = window.range.cloneContents();

                            var div = document.createElement('div');

                            div.appendChild( documentFragment.cloneNode(true) );

                            var selectionFragment = div.innerHTML;

                            console.log(selectionFragment);

                            var isHTML = RegExp.prototype.test.bind(/(<([^>]+)>)/i);

                            /*
                             * Delete marker
                             */
                            if(selected.toString().length > 1 && isHTML(selectionFragment) == false
                                && $(event.target).attr("class") != "fa fa-microphone"){

                                getHtml(event.target);

                                $('#filter_voiceannotation_box_btn').show();
                                //$('#filter_voiceannotation_box_btn').trigger('click');

                                if($("#filter_voiceannotation_rec").is(":visible") == false && quickrecording == 1) {
                                    $("#filter_voiceannotation_container")[0].click();
                                }

                            } else {
                                $('#filter_voiceannotation_box_btn').hide();
                                //$('#filter_voiceannotation_marker').contents().unwrap();
                            }

                        }
                    });

                }


                function getHtml(element){
                    var pathname = window.location.pathname;
                    var htmlID = 0;
                    var entryID = 0;
                    var newID = ID();

                    try {
                        if (pathname.indexOf("/mod/journal/report.php") > -1) {
                            var attr = $(element).closest('td').attr("id");

                            if (typeof attr !== typeof undefined && attr !== false) {
                                htmlID = attr;
                            } else {
                                $(element).closest('td').attr("id", newID);
                                htmlID = newID;
                            }
                            entryID = $(element).closest('tbody').find("textarea").attr("name").split("c");
                            entryID = entryID[1];
                        } else if (pathname.indexOf("/mod/journal/view.php") > -1) {
                            var attr = $(".generalbox").attr("id");
                            if (typeof attr !== typeof undefined && attr !== false) {
                                htmlID = attr;
                            } else {
                                $(".generalbox").attr("id", newID);
                                htmlID = newID;
                            }

                        }
                        window.htmlID = htmlID;
                        window.entryID = entryID;
                        console.log("htmlID: " + window.htmlID);
                        console.log("entryID: " + window.entryID);
                    } catch (e) {
                        console.log("Error: Can't parse element");
                    }
                }


            });


            function contains(target, pattern){
                var value = 0;
                pattern.forEach(function(word){
                    value = value + target.includes(word);
                });
                return (value >= 1)
            }

            var ID = function () {
                // Math.random should be unique because of its seeding algorithm.
                // Convert it to base 36 (numbers + letters), and grab the first 9 characters
                // after the decimal.
                return '_' + Math.random().toString(36).substr(2, 9);
            };

        }
    };
});