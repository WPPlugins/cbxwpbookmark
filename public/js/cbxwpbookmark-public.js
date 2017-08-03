(function ($) {
    'use strict';

    $(document).ready(function () {


        var ajaxurl = cbxwpbookmark.ajaxurl;

        //on click create category
        $('.cbxwpbkmarkaddnewcatcreate').on('click', function (event) {
            event.preventDefault();

            var $addnewwrap = $(this).parents('.cbxwpbkmarkaddnewwrap');
            var $globalparent = $(this).parents('.cbxwpbkmarkwrap');
            //var $cattrigparent      = $(this).parents('.cbxwpbkmarkaddnewcat');

            var $cat_name = $addnewwrap.find('.cbxwpbkmarkaddnewcatinput');
            var $object_id = $(this).data('object_id');

            var $privacy = $addnewwrap.find('.cbxwpbkmarkaddnewcatselect');


            $cat_name.removeClass('error');
            $addnewwrap.find(".cbxwpbkmarkaddnewaction_error").hide().removeClass('cbxwpbkmarkaddnewaction_success').text('');
            //$addnewwrap.find(".cbxwpbkmarkaddnewaction_error").removeClass('cbxwpbkmarkaddnewaction_success');
            //$addnewwrap.find(".cbxwpbkmarkaddnewaction_error").text('');


            //check if the input text field is empty or not
            //if the text input for category name validate then send ajax request

            if ($cat_name.val() != '') {

                //$globalparent.find(".cbx-onclick-loading").show();
                $globalparent.find('.cbxwpbkmarkloading').show();
                //send ajax request
                var data = {
                    'action': 'cbx_add_category',
                    'security': cbxwpbookmark.nonce,
                    'catename': $cat_name.val(),
                    'privacy': $privacy.find('input[type="radio"]:checked').val(),
                    'object_id': $object_id
                };

                // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                $.post(ajaxurl, data, function (response) {
                    //console.log('Got this from the server: ' + response);
                    response = $.parseJSON(response);
                    if (response.code == 2) {

                        //category created
                        $cat_name.val('');

                        var cats = $.parseJSON(response.cats);

                        var catoptions = '';

                        $.each(cats, function (key, val) {
                            //catoptions += "<li class=\"cbxlbjs-item\" data-privacy=" + cats[key].privacy + " data-incat=" + cats[key].incat + "  data-value='" + cats[key].id + "'>" + cats[key].cat_name + "</li>";
                            catoptions += "<li class=\"cbxlbjs-item\" data-privacy=" + cats[key].privacy + " data-incat=" + cats[key].incat + "  data-value='" + cats[key].id + "'><span title=\""+ cats[key].cat_name +"\" class=\"cbxlbjs-item-name\">" + cats[key].cat_name + "</span></li>";
                        });


                        $globalparent.find('.cbxwpbkmarklist').show();
                        $globalparent.find('.cbxwpbkmarklist-nocatfound').remove();

                        $globalparent.find('.cbxwpbkmarklist').html(catoptions);
                        //$globalparent.find(".cbx-onclick-loading").hide();
                        $globalparent.find('.cbxwpbkmarkloading').hide();

                        //show success message
                        $cat_name.val(''); //reset category name
                        $addnewwrap.find(".cbxwpbkmarkaddnewaction_error").show().addClass('cbxwpbkmarkaddnewaction_success').text(response.msg);
                        //$addnewwrap.find(".cbxwpbkmarkaddnewaction_error").addClass('cbxwpbkmarkaddnewaction_success');
                        //$addnewwrap.find(".cbxwpbkmarkaddnewaction_error").text(response.msg);

                        //hide the category create panel and show the add new category button link again
                        //$globalparent.find('.cbxwpbkmarkaddnewwrap').hide();
                        //$globalparent.find('.cbxwpbkmarkaddnewcattrig').show();


                    }
                    else {
                        //failed or duplicate
                        //$globalparent.find('.cbx-onclick-loading').hide('slow');
                        $globalparent.find('.cbxwpbkmarkloading').hide('slow');

                        $addnewwrap.find(".cbxwpbkmarkaddnewaction_error").show().text(response.msg);
                        //$addnewwrap.find(".cbxwpbkmarkaddnewaction_error").text(response.msg);
                        $cat_name.addClass('error');


                    }
                });

            }
            else {
                $cat_name.addClass('error');
                $addnewwrap.find(".cbxwpbkmarkaddnewaction_error").show().text(cbxwpbookmark.category_name_empty);
                //$addnewwrap.find(".cbxwpbkmarkaddnewaction_error").text(cbxwpbookmark.category_name_empty);

            }

        });


        //on click add category
        $('.cbxwpbkmarkaddnewcattrig').on('click', function (event) {
            event.preventDefault();
            $(this).next('.cbxwpbkmarkaddnewwrap').show();
            $(this).hide();
        });


        //on click on +add  it will open the boomark panel
        $('.cbxwpbkmarktrig').on('click', function (event) {
            event.stopPropagation();
            event.preventDefault();

            var $this = $(this);
            var $object_id = $this.data('object_id'); //post id
            var $object_type = $this.data('type'); //post type

            var $bookmarkpanel = $this.next('.cbxwpbkmarklistwrap');

            $bookmarkpanel.find('.cbxlbjs-searchbar').val('');
            $bookmarkpanel.find('.cbxwpbkmarkaddnewcatinput').val('').removeClass('error');
            $bookmarkpanel.find(".cbxwpbkmarkaddnewaction_error").hide().text('').removeClass('cbxwpbkmarkaddnewaction_success')

            $bookmarkpanel.find('.cbxwpbkmarkaddnewwrap').hide();
            $bookmarkpanel.find('.cbxwpbkmarkaddnewcattrig').show();
            $bookmarkpanel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_defult);

            $($bookmarkpanel).toggle("fast", function () {

                //show the ajax icon
                $bookmarkpanel.find('.cbxwpbkmarkloading').show();
                //send ajax request to popular the categories as fresh

                var data = {
                    'action': 'cbx_find_category',
                    'security': cbxwpbookmark.nonce,
                    'object_id': $object_id,
                    'object_type': $object_type
                };



                $.post(ajaxurl, data, function (response) {
                    response = $.parseJSON(response);



                    if (response && response.code == 1) {
                        //categories found
                        $bookmarkpanel.find('.cbxwpbkmarkloading').hide();
                        $bookmarkpanel.find('.cbxwpbkmarkselwrap').show();
                        var cats = $.parseJSON(response.cats);

                        var catoptions = '';


                        $.each(cats, function (key, val) {
                            catoptions += "<li class=\"cbxlbjs-item\" data-privacy=" + cats[key].privacy + " data-incat=" + cats[key].incat + "  data-value='" + cats[key].id + "'><span title=\""+ cats[key].cat_name +"\" class=\"cbxlbjs-item-name\">" + cats[key].cat_name + "</span></li>";
                        });

                        $bookmarkpanel.find('.cbxwpbkmarklist').html(catoptions);


                    }
                    else {
                        //failed to get category for this user
                        $bookmarkpanel.find('.cbxwpbkmarkloading').hide();
                        $bookmarkpanel.find('.cbxwpbkmarkselwrap').show();

                        //if no category found show message and put option to create one
                        $bookmarkpanel.find('.cbxwpbkmarklist').hide();
                        $bookmarkpanel.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound"><a data-clicked="0" class="cbxwpbkmarklist-nocatfoundtrig" href="#">'+response.msg+'</a></p>');

                    }

                });
            });

        });
        //on click Add Bookmark

        //on click on nocategory found
        $('.cbxwpbkmarkselwrap').on('click', 'a.cbxwpbkmarklist-nocatfoundtrig', function (event) {
            event.preventDefault();

            var $this       = $(this);
            var $clicked    = parseInt($this.data('clicked'));

            if(!$clicked){

                $('.cbxwpbkmarkaddnewcattrig').click();
                $this.data('clicked', 1)
            }
        });


        //on click on close icon disable the bookmark popup
        $('.cbxwpbkmarklistwrap').on('click', 'i.cbxwpbkmarktrig_close', function (event) {

            var $this = $(this);
            var $parent = $this.parents('.cbxwpbkmarklistwrap');
            $parent.fadeOut();

        });
        //end on click on close icon disbale the bookmark popup

        //on click on any where of body except the bookmark popup close the bookmark popup
        $("body").mouseup(function (e) {
            var subject = $(".cbxwpbkmarklistwrap");

            if ((e.target.id != 'cbxwpbkmarklistwrap-' + subject.data('object_id')) && !subject.has(e.target).length) {
                subject.fadeOut();

            }
        });
        //on click on any where of body except the bookmark popup close the bookmark popup

        //adding click event on the list
        $('.cbxwpbkmark-lbjs').on('click', '.cbxlbjs-item', function (e) {


            var $bookmarkpanel = $(this).parents('.cbxwpbkmarklistwrap');
            //$bookmarkpanel.find(".cbx-onclick-loading").show();
            $bookmarkpanel.find('.cbxwpbkmarkloading').show();

            var $object_id      = $bookmarkpanel.data('object_id');
            var $object_type    = $bookmarkpanel.data('type'); //object type



            var $cat_id = $(this).attr('data-value');

            var $item = $(this);

            //now send ajax request to save this post id and category as bookmark for this user
            //post id already in variable $object_id

            var addcat = {
                'action': 'cbx_add_bookmark',
                'security': cbxwpbookmark.nonce,
                'cat_id': $cat_id,
                'object_id': $object_id,
                'object_type': $object_type
            };

            $.post(ajaxurl, addcat, function (response) {

                response = $.parseJSON(response)
                if (response.code) {
                    $item.attr('data-incat', response.operation);
                    //$bookmarkpanel.find(".cbx-onclick-loading").hide();
                    $bookmarkpanel.find('.cbxwpbkmarkloading').hide();
                    $bookmarkpanel.find('.cbxwpbkmarktrig_label').text(response.msg);
                }
                else {
                    $bookmarkpanel.find('.cbxwpbkmarktrig_label').text(response.msg);
                    //console.log(response);
                }
            });


        });


        /* User Front Admin */


        //my category update event

        //save button action
        $('.cbxbookmark-category-list').on('click', 'a.cbxbookmark-cat-save', function (event) {


            event.preventDefault();


            var $parent = $(this).closest('li.cbxbookmark-mycat-item');
            var _$this = $(this);

            var $ucatid = $parent.data('id'); //get this value from the parent data attribute


            //get new value
            var $ucatname = $parent.find('.cbxbmedit-catname').val();
            var $uprivacy = $parent.find('.cbxbmedit-privacy').val();


            var updatedata = {
                'action': 'cbx_update_bookmark_category',
                'security': cbxwpbookmark.nonce,
                'id': $ucatid,
                'catname': $ucatname,
                'privacy': $uprivacy
            };

            _$this.find('span').css({
                'display': 'inline-block'
            });

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            $.post(ajaxurl, updatedata, function (response) {

                response = $.parseJSON(response);

                if (response.flag == 1) {
                    $parent.find(".cbxbookmark-mycat-editbox").hide();
                    $parent.data('privacy', response.privacy);
                    $parent.data('name', response.catname);
                    $parent.find(".cbxlbjs-item-widget").html(response.catname);
                }
                else if (response.flag == 0) {
                    $parent.parent('.cbxbookmark-category-list').prev('.cbxbookmark-errormsg').html(response.msg);
                }

                _$this.find('span').css({
                    'display': 'none'
                });

            });

        });


        //my category edit button action
        //edit button action and it opens the edit panel for the clicked item
        $('.cbxbookmark-category-list').on('click', 'a.cbxbookmark-edit-btn', function (event) {

            event.preventDefault();
            var $parent = $(this).parent('.cbxbookmark-mycat-item');

            var _$this = $(this);


            var $catid = $parent.data('id');
            var $catname = $parent.data('name');
            var $privacy = $parent.data('privacy');

            var $editpanel = $parent.find(".cbxbookmark-mycat-editbox");
            if ($editpanel.length == 0) {
                var $template = $.parseJSON(cbxwpbookmark.cat_template);

                /* Repalcing Input Values */
                var $template = $template.replace(/##catname##/g, $catname);
                $parent.append($template);

                if ($privacy == 0) {
                    $parent.find(".cbxbmedit-privacy option:eq(1)").prop('selected', true);
                    $parent.find(".cbxbmedit-privacy option:eq(0)").prop('selected', false);
                }

                if ($privacy == 1) {
                    $parent.find(".cbxbmedit-privacy option:eq(0)").prop('selected', true);
                    $parent.find(".cbxbmedit-privacy option:eq(1)").prop('selected', false);
                }
            }
            else {
                $editpanel.show();
                $parent.find('.cbxbmedit-catname').val($catname);
                //$parent.find('.cbxbmedit-privacy').val($privacy);

                if ($privacy == 0) {
                    $parent.find(".cbxbmedit-privacy option:eq(1)").prop('selected', true);
                    $parent.find(".cbxbmedit-privacy option:eq(0)").prop('selected', false);
                }

                if ($privacy == 1) {
                    $parent.find(".cbxbmedit-privacy option:eq(0)").prop('selected', true);
                    $parent.find(".cbxbmedit-privacy option:eq(1)").prop('selected', false);
                }
            }


        });


        //close action for edit panel for each single item
        $('.cbxbookmark-category-list').on('click', '.cbxbookmark-cat-close', function (e) {

            e.preventDefault();
            //$(".cbxbookmark-mycat-editbox").css("display", "none");
            $(this).parent(".cbxbookmark-mycat-editbox").css("display", "none");

        });


        //my category delete button action
        $('.cbxbookmark-category-list').on('click', 'a.cbxbookmark-delete-btn', function (event) {
            event.preventDefault();


            if (!confirm(cbxwpbookmark.areyousuretodeletecat)) {
                return false;
            }

            var $id = $(this).data('id');

            var _$this = $(this);

            //  console.log($id);
            var data = {
                'action': 'cbx_delete_bookmark_category',
                'security': cbxwpbookmark.nonce,
                'id': $id
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations

            if (_$this) {

                _$this.find('span').css({
                    'display': 'inline-block'
                });

                $.post(ajaxurl, data, function (response) {
                    //console.log('Got this from the server: ' + response);
                    response = $.parseJSON(response);
                    //  console.log( response );

                    if (response.msg == 0) {

                        // success Message
                        var message = cbxwpbookmark.category_delete_success;

                        // Remove the li tag if the category deleted
                        $(_$this).parent("li").remove();

                        // Loading success message on .msg div element
                        $(".msg").html(message);

                    }
                    else {
                        var message = cbxwpbookmark.category_delete_error;
                        $(".msg").html(message);
                    }
                    //console.log(message);

                    _$this.find('span').css({
                        'display': 'none'
                    });
                });
            }
        });


        /**
         * Delete bookmark
         */
        $('.cbxwpbookmark-mylist').on('click', 'a.cbxbookmark-post-delete', function (event) {
            event.preventDefault();

            if (!confirm(cbxwpbookmark.areyousuretodeletebookmark)) {
                return false;
            }


            var $postdelete = $(this);
            var $wrapper = $postdelete.parents('div.cbxwpbookmark-mylist-wrap');

            var $objectid = $postdelete.data("id");

            var data = {
                'action': 'cbx_delete_bookmark_post',
                'security': cbxwpbookmark.nonce,
                'postid': $objectid
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations

            if ($postdelete) {

                $postdelete.find('span').css({
                    'display': 'inline-block'
                });

                $.post(ajaxurl, data, function (response) {
                    response = $.parseJSON(response);

                    if (response.msg == 0) {
                        // Remove the li tag if the bookmark is deleted
                        $postdelete.parent("li").remove();

                        var $success_html = $('<div class="cbxbookmark-alert cbxbookmark-alert-success">' + cbxwpbookmark.bookmark_removed + '</div>');
                        $wrapper.prepend($success_html);


                        if ($wrapper.find('ul.cbxwpbookmark-mylist li').length === 0) {
                            $wrapper.find('ul.cbxwpbookmark-mylist').append('<li>' + cbxwpbookmark.bookmark_removed_empty + '</li>')
                        }
                    }
                    else if (response.msg == 1) {
                        var $error_html = $('<div class="cbxbookmark-alert cbxbookmark-alert-success">' + cbxwpbookmark.bookmark_removed_failed + '</div>');
                        $wrapper.prepend($error_html);
                    }
                    $postdelete.find('span').css({
                        'display': 'none'
                    });
                });


            }
        });


        //implementing the bookmark load more feature

        $('.cbxwpbookmark-mylist-wrap').on('click', 'a.cbxbookmark-more', function (e) {
            e.preventDefault();
            var _this = $(this);
            var $wrapper = _this.parents('div.cbxwpbookmark-mylist-wrap');

            //console.log($wrapper);
            $wrapper.find('.cbxwpbm_ajax_icon').show();


            var limit = _this.data('limit');
            var offset = _this.data('offset');
            var catid = _this.data('catid');

            var order = _this.data('order');
            var orderby = _this.data('orderby');
            var userid = _this.data('userid');

            var totalpage = _this.data('totalpage');
            var currpage = _this.data('currpage');
            var allowdelete = _this.data('allowdelete');

            if (currpage + 1 >= totalpage) {
                _this.hide();
            } else {
                _this.show();
            }

            var addcat = {
                'action': 'cbx_bookmark_loadmore',
                'security': cbxwpbookmark.nonce,
                'limit': limit,
                'offset': offset,
                'catid': catid,
                'order': order,
                'orderby': orderby,
                'userid': userid,
                'allowdelete': allowdelete
            };

            $.post(ajaxurl, addcat, function (response) {

                response = $.parseJSON(response);

                if (response.code) {
                    _this.data('offset', limit + offset);
                    _this.data('currpage', currpage + 1);

                    $wrapper.find('ul.cbxwpbookmark-mylist').append(response.data);
                    $wrapper.find('.cbxwpbm_ajax_icon').hide();


                } else {
                    //console.log('Error loading data. Response code=' + response.code);
                    var $error_html = $('<div class="cbxbookmark-alert cbxbookmark-alert-error">' + cbxwpbookmark.error_msg + response.code + '</div>');
                    $wrapper.find('ul.cbxwpbookmark-mylist').append($error_html);
                }


            });


        });

        //category search using fuzzy logic
        /* filter dinosaurs as you type */
        $(".cbxwpbkmarkwrap").on('keyup', '.cbxlbjs-searchbar', function () {


            var $this = $(this);
            var val = $this.val();


            var catlistholder = $this.parent('.cbxlbjs-searchbar-wrapper').next('.cbxwpbkmarklist');


            if (this.value.length > 0) {
                catlistholder.find('li').hide().filter(function () {
                    return $(this).text().toLowerCase().indexOf(val.toLowerCase()) != -1;
                }).show();
            }
            else {
                catlistholder.find('li').show();
            }

        });


    });

})(jQuery);

