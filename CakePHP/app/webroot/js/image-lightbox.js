$(function() {
    $(document.body).off('click', '.lightbox-image').on('click', '.lightbox-image', function(e) {



        if ($(this).data('title')) {
            var title = $(this).data('title');
        } else {
            var title = '';
        }

        if ($(this).data('description')) {
            var description = $(this).data('description');
        } else {
            var description = '';
        }


        var misc = '';
        var imageUrl = $(this).attr('href');

        if (!$(this).data('restrict-edit')) {
            if ($(this).data('media-id')) {
                // Update editLink based on which page the request came from
                if ($(this).prop('hostname') == $(this).data('hostname')) {
                    var editLink = '<a href="/marketing/floorplans/editFloorplansLibrary?ids[0]=' + $(this).data('media-id') + '" class="btn btn-default"><i class="fa fa-edit"></i>&nbsp; Edit Image</a>';
                } else {
                    var editLink = '<a href="/marketing/content/editMediaLibrary?ids[0]=' + $(this).data('media-id') + '" class="btn btn-default"><i class="fa fa-edit"></i>&nbsp; Edit Image</a>';
                }
            } else {
                var editLink = '';
            }
        } else {
            var editLink = '';
        }  var editLink = '';
        }

        if (title || description) {

            var content = '';

            if (title) {
                content = '<p><strong>' + title + '</strong></p>';
            }

            if (description) {
                content += '<p>' + description + '</p>';
            }


            var misc = '<div style="background: #f7f7f7 none repeat scroll 0 0;display: block; margin: 6px 6px 0; padding: 5px;">' +
                content +
                '</div>';
        }

        var content = '<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
            <div class="modal-dialog" style="/**width: 652px !important;**/">\
                <div class="modal-content">\
                    <div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\
                        <h4 class="modal-title" id="myModalLabel">Viewing Larger Image</h4>\
                    </div>\
                    <div class="modal-body" style="text-align: center; padding: 5px !important; ">\
                        <img style="max-height: 700px; max-width: 100%;" src="'+imageUrl+'" id="imagepreview"  >\
                        ' + misc + '\
                    </div>\
                    <div class="modal-footer">\
                        ' + editLink + '\
                        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times-circle"></i> &nbsp;Close Window</button>\
                    </div>\
                </div>\
            </div>\
        </div>';


        $('#imagemodal').remove();
        $('body').append(content);

        $('#imagemodal').modal('show');

        e.preventDefault();
        return false;
    });
});