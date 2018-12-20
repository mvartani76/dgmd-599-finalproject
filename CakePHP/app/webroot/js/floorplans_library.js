


$(function() {


    $(document.body).off('click', '.preview-selected-media-file').on('click', '.preview-selected-media-file', function(e) {

        var puOpts = {
            title           : 'Loading Video',
            message         : 'Please wait...',
            confirm_label   : null,
            view            : 'loading',
            nofade          : true,
            btnText         : null,
            frmTarget       : null

        };

        popupMessage(puOpts);

        $.ajax({
            url: '/marketing/content/load_video_preview',
            type: 'POST',
            data: {
                floorplan_id: $(this).data('media-id')
            },
            success: function(response) {

                try {
                    var data		= JSON.parse( response );
                    var html    	= data.HTML;
                    var isRawHtml	= false;
                } catch ( e ) {
                    var html     	= response;
                    var isRawHtml	= true;
                }

                if (typeof data.extras != 'undefined') {
                    var extras = data.extras;
                }

                var callback = function() {

                    $('#MediaVideoPreviewPlayer').flowplayer({
                        key: '',
                        logo: '/img/logo.png'
                    });
                }

                var puOpts = {
                    title           : 'Video File Preview',
                    message         : html,
                    confirm_label   : '<i class="fa fa-play-circle" style="margin-right: 10px;"></i>Save Media Selection',
                    view            : 'confirmation',
                    btnDisabledDef  : true,
                    nofade          : true,
                    styleCheckBoxes : false,
                    width           : '60%',
                    btnText         : 'Saving Floorplan Selection',
                    frmTarget       : 'ContentSaveMediaSelection'
                };

                popupMessage(puOpts, callback);

            }
        });

        e.preventDefault();

    });


    $(document.body).on('submit', '#CropFloorplanForm', function(e) {
        var data = $(this).serialize();
        
        $.ajax(
            {
                data: data,
                type: 'POST',
                url:  '/marketing/floorplans/create_cropped_floorplan',
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.code == 200) {
                        $('#NoEditedMediaFound').remove();
                        
                        var htmlInsert = '<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">\
                            <div class="media-library-image-wrapper">\
                                <label class="media-library-image">\
                                    <div class="media-library-image-holder">\
                                        <img class=" img-responsive" src="{IMAGETHUMBPATH}" />\
                                        <span class="typetype" title="Image Media">\
                                            <i class="fa fa-camera"></i>\
                                        </span>\
                                        <div class="sub-meta-data">\
                                            <a class="lightbox-image" data-restrict-edit="true" data-media-id="{MEDIAID}" data-title="Cropped Image" data-description="" href="{IMAGEPATH}">View Image</a>\
                                            <a title="Edit Image" href="/marketing/floorplans/editFloorplansLibrary?ids[0]={MEDIAID}">\
                                                <i class="fa fa-edit"></i>\
                                            </a>\
                                            <a title="Delete Image" href="/marketing/floorplans/deleteFloorplansLibrary?ids[0]={MEDIAID}">\
                                                <i class="fa fa-times-circle"></i>\
                                            </a>\
                                        </div>\
                                    </div>\
                                </label>\
                                </div>\
                            </div>';

                        repl = htmlInsert.replace(/\{IMAGETHUMBPATH}/g, data.tpath);
                        repl = repl.replace(/\{MEDIAID}/g, data.id);
                        repl = repl.replace(/\{IMAGEPATH}/g, data.path);

                        $('#ImagesCroppedContainer' + data.parent_id).append(repl);

                        $('#fc-hm-us').modal('hide');

                    } else {

                        var opts = {
                                nofade: true,

                                title: "An Error Occurred",
                                message: data.text
                        };

                        errorMessage(eopts);
                    }


                }
            }
        );

        e.preventDefault();
        return false;
    });

    $('.CreateCroppedFloorplan').click(function(event) {

        var floorplansId = $(this).data('media-id');
        console.log("wtf man");
        var puOpts = {
            title           : 'Loading Cropping Tool',
            message         : 'Please wait...',
            confirm_label   : null,
            view            : 'loading',
            nofade          : false,
            btnText         : null,
            frmTarget       : null
        };

        popupMessage(puOpts);

        $.ajax({
            url: '/marketing/floorplans/cropping_tool',
            data: 'floorplan_id=' + floorplansId + '&action=request',
            type: 'POST',
            success: function(response) {

                try {
                    var data		= JSON.parse( response );
                    var html    	= data.HTML;
                    var isRawHtml	= false;


                } catch ( e ) {
                    var html     	= response;
                    var isRawHtml	= true;
                }
                if ( data.has_extras ) {
                    var username = data.extras.User.name;
                }

                var puOpts = {
                    title           : 'Floorplan Cropping Utility',
                    message         : html,
                    confirm_label   : '<i class="fa fa-crop" style="margin-right: 10px;"></i>Crop Image',
                    view            : 'content',
                    nofade          : true,
                    width           : '75%',
                    btnText         : 'Cropping Floorplan',
                    frmTarget       : 'CropFloorplanForm'
                };

                popupMessage(
                    puOpts,
                    function() {
                        $('#TargetCropFloorplan').cropper({
                            //  aspectRatio: 16 / 9,
                            preview: '.img-preview',
                            viewMode: 2,
                            checkOrientation: false,
                            responsive: false,
                            crop: function(e) {

                                var c = $('#TargetCropFloorplan').cropper('getCropBoxData');
                                var d = $('#TargetCropFloorplan').cropper('getData');
                                var i = $('#TargetCropFloorplan').cropper('getImageData');

                                var nh = i.naturalHeight;
                                var nw = i.naturalWidth;

                                var refW = i.width;
                                var refH = i.height;

                                var mh = nh / refH;
                                var mw = nw / refW;

                                $('#imgCropX').val(d.x);
                                $('#imgCropY').val(d.y);
                                $('#imgCropW').val(c.width * mw);
                                $('#imgCropH').val(c.height * mh);
                                $('#imgCropR').val(d.rotate);
                                $('#imgCropSx').val(d.scaleX);
                                $('#imgCropSy').val(d.scaleY);

                                $('#dataHeight').val(Math.round(c.height * mh));
                                $('#dataWidth').val(Math.round(c.width * mw));

                            }
                        });


                        var $image = $('#TargetCropFloorplan');

                        $('.docs-buttons').off('click', '[data-method]').on('click', '[data-method]', function () {
                            var $this = $(this);
                            var data = $this.data();
                            var $target;
                            var result;

                            if ($this.prop('disabled') || $this.hasClass('disabled')) {
                                return;
                            }

                            if ($image.data('cropper') && data.method) {
                                data = $.extend({}, data); // Clone a new one

                                if (typeof data.target !== 'undefined') {
                                    $target = $(data.target);

                                    if (typeof data.option === 'undefined') {
                                        try {
                                            data.option = JSON.parse($target.val());
                                        } catch (e) {
                                            console.log(e.message);
                                        }
                                    }
                                }

                                result = $image.cropper(data.method, data.option, data.secondOption);

                                switch (data.method) {
                                    case 'scaleX':
                                    case 'scaleY':
                                        $(this).data('option', -data.option);
                                        break;

                                    case 'getCroppedCanvas':
                                        if (result) {

                                            // Bootstrap's Modal
                                            $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

                                            if (!$download.hasClass('disabled')) {
                                                $download.attr('href', result.toDataURL('image/jpeg'));
                                            }
                                        }

                                        break;
                                }

                                if ($.isPlainObject(result) && $target) {
                                    try {
                                        $target.val(JSON.stringify(result));
                                    } catch (e) {
                                        console.log(e.message);
                                    }
                                }

                            }
                        });

                    }
                );

            }
        });


        event.preventDefault();
    });



    function checkLibrarySelections() {

        $('#edit-container,#del-container').empty();

        var allChecked = true;
        var allUnchecked = true;

        $('.cb-select').each(function(i,v) {
            if ($(this).is(':checked')) {
                allUnchecked = false;

                var $input = $('<input/>').attr('type', 'hidden').attr('name', 'ids[]').val($(this).val());

                $('#edit-container,#del-container').append($input);

            } else {
                allChecked = false;
            }
        });

        if (allChecked) {
            $('#select-all-images').removeClass('btn-default').removeClass('select-all').addClass('btn-primary').html('<i class="fa fa-square"></i> &nbsp;Deselect All');
        } else if (allUnchecked) {
            $('#select-all-images').addClass('btn-default').addClass('select-all').removeClass('btn-primary').html('<i class="fa fa-check-square"></i> &nbsp;Select All');
        } else {
            $('#select-all-images').addClass('btn-default').addClass('select-all').removeClass('btn-primary').html('<i class="fa fa-check-square"></i> &nbsp;Select All');
        }

    }

    $(document.body).on('mouseover', 'label.media-library-image', function(e) {

        var $meta = $(this).find('div.sub-meta-data');

        $meta.stop().animate(
            {
                'opacity': 1,
                'margin-bottom': 0
            },
            400
        );


        e.preventDefault();
    });

    $(document.body).on('mouseout', 'label.media-library-image', function(e) {

        var $meta = $(this).find('div.sub-meta-data');

        $meta.stop().animate(
            {
                'opacity': 0,
                'margin-bottom': -25
            },
            400
        );


        e.preventDefault();
    });


    $(document.body).on('click', '#select-all-images', function(e) {

        if ($(this).hasClass('select-all')) {
            $('.cb-select').each(function(i,v) {
                if (!$(this).is(':checked')) {
                    $(this).trigger('click');
                }
            });
        } else {
            $('.cb-select').each(function(i,v) {
                if ($(this).is(':checked')) {
                    $(this).trigger('click');
                }
            });
        }



    });


    var refreshing = false;


    $(document.body).on('click', '.cb-select', function() {
        $(this).blur();
        if ($(this).is(':checked')) {
            $(this).parent().parent().addClass('image-selected');
        } else {
            $(this).parent().parent().removeClass('image-selected');
        }

        checkLibrarySelections();

    });

    if (window.location.hash.length) {
        $('.nav-tabs a[href=' + window.location.hash + ']').tab('show');
    }

    $('#library-tab').click(function() {

        if (refreshing) {
            return true;
        } else {
            refreshing = true;

            $('#image-library-container').block({
                message: '<div class="ajax-processing">Loading Content, please wait.</div>',
                css: {
                    padding: 10,
                    backgroundColor: '#fff',
                    color: '#666',
                    top: '30%',
                    left: '50%',
                    marginLeft: '-170px',
                    textAlign: 'center',
                    width: 340
                },
                overlayCSS: {
                    backgroundColor: '#f1f1f1'
                }
            });

        }

        $.ajax({
            url: '/marketing/floorplans/floorplans_library/refreshed',
            type: 'GET',
            cache: false,
            success: function(response) {

                try {
                    var data		= JSON.parse( response );
                    var html    	= data.HTML;
                    var isRawHtml	= false;

                } catch ( e ) {
                    var html     	= response;
                    var isRawHtml	= true;
                }


                if ( data.has_extras ) {
                    var code        = data.extras.code;
                }

                $('#image-library-container').unblock().html(html);
                refreshing = false;

            }
        });




    });

    if ($('#dropzone').length) {
        Dropzone.autoDiscover = false;
        Dropzone.options.dropzone =
        {
            paramName: "filename",
            maxFilesize: 1024,
            url: '/marketing/floorplans/upload_floorplans',
            fileBaseSize: 1024,
            uploadMultiple: true,
            parallelUploads: 1,
            previewsContainer: '.media-previews',
            maxFiles: 100,
            acceptedFiles: 'image/*,video/*,.ai,.svg',

            processingmultiple: function(files) {

                var $obj = $('#upload-total-progress');
                var $sp  = $('<span></span>');

                $sp.css({'margin-left': '10px', 'font-size': '11px', 'margin-top': '-3px', 'padding': '2px 5px'}).addClass('totalUploadProgress').addClass('badge').addClass('bg-blue');
                $obj.empty().show().append($sp);

            },

            totaluploadprogress: function(progress, totalBytes, totalBytesSent) {
                $('.totalUploadProgress').text(Math.ceil(progress) + '% total');
            },

            queuecomplete: function() {
                $('#upload-total-progress').fadeOut(800, function() { $(this).empty(); });
            },

            uploadprogress: function(file, progress, bytes) {

                var node, node2, _i, _len, _ref, _results, _results2, _ref2;
                if (file.previewElement) {
                    _ref = file.previewElement.querySelectorAll("[data-dz-uploadprogress]");
                    _results = [];
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i];
                        if (node.nodeName === 'PROGRESS') {
                            _results.push(node.value = progress);
                        } else {
                            _results.push(node.style.width = "" + progress + "%");
                        }
                    }

                    _ref2 = file.previewElement.querySelectorAll("[data-dz-uploadcounter]");

                    _results2 = [];
                    for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
                        node2 = _ref2[_i];
                    }

                    node2.innerHTML = Math.ceil(progress) + '%';

                    return _results;
                }

            },
            accept: function (file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                }
                else {
                    done();
                }
            }
        };

        var DZ = new Dropzone('form#dropzone');

        DZ.on('success', function(file, response) {

            var $Json = JSON.parse(response);

            if ($Json.code != 200) {
                var $errors = $Json.errors;
                if (typeof $errors.filename != 'undefined') {   // error is with file upload

                    var $content = $('<div></div>');

                    var $p       = $('<p></p>').text('There was an error while processing your file upload request:');
                    var $ul      = $('<ul></ul>');
                    var $ftr     = $('<p></p>').text('Please try your file upload again. Remember, only vector files and images are accepted.');


                    for (k in $errors.filename) {

                        var $li = $('<li></li>');
                        $li.text($errors.filename[k]);

                        $ul.append($li);

                    }

                    $content.append($p).append($ul).append($ftr);

                    var opts = {
                        title: "File Upload Error",
                        message: $content.prop('outerHTML')
                    };

                    var cb = function() {
                        DZ.removeFile(file);
                    };

                    errorMessage(opts, null, cb);

                } else {

                    var $content = $('<div></div>');
                    var $p       = $('<p></p>').text('There was an error while processing your file upload request.');
                    var $ftr     = $('<p></p>').text('Please try your file upload again. Remember, only video files and images are accepted.');

                    $content.append($p).append($ftr);

                    var opts = {
                        title: "File Upload Error",
                        message: $content.prop('outerHTML')
                    };

                    var cb = function() {
                        DZ.removeFile(file);
                    };

                    errorMessage(opts, null, cb);
                }


            }

        });
    }



    $(document.body).on('keydown', '#floorplansSearchTerm', function(e) {
        if (e.keyCode === 13) {
            $('#doFloorplansSearch').trigger('click');
        }
    });

    $(document.body).on('click', '#doFloorplansSearch', function(e) {

        var searchTerm = $('#floorplansSearchTerm').val();

        if (refreshing) {
            return true;
        } else {
            refreshing = true;

            $('#image-library-container').block({
                message: '<div class="ajax-processing">Processing Search, please wait.</div>',
                css: {
                    padding: 10,
                    backgroundColor: '#fff',
                    color: '#666',
                    textAlign: 'center',
                    width: 340
                },
                overlayCSS: {
                    backgroundColor: '#f1f1f1'
                }
            });

        }

        $.ajax({
            url: '/marketing/floorplans/floorplans_library/search/' + searchTerm,
            type: 'GET',
            cache: false,
            success: function(response) {

                try {
                    var data		= JSON.parse( response );
                    var html    	= data.HTML;
                    var isRawHtml	= false;

                } catch ( e ) {
                    var html     	= response;
                    var isRawHtml	= true;
                }


                if ( data.has_extras ) {
                    var code        = data.extras.code;
                }

                $('#image-library-container').unblock().html(html);
                refreshing = false;

            }
        });


    });

});