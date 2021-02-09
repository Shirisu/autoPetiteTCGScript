function create_toast(id, icon, title, text) {
    return $(
        '<div class="toast" id="toast-add-to-wishlist-' + id + '" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">' +
        '<div class="toast-header">' +
        '<i class="fas fa-' + icon + ' mr-2"></i>' +
        '<strong class="mr-auto">' + title + '</strong>' +
        '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>' +
        '<div class="toast-body">' +
        text +
        '</div>' +
        '</div>'
    );
}


$(document).ready(function() {
    // toggle sidebar
    $('#menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });

    // switch language
    $('.switch-language').on('click', function(e) {
        e.preventDefault();
        $.post('/inc/_switch_language.php',{ language:$(this).attr('data-language'),
            rand:Math.random()} ,function(data) {;
            if (data == 'switch') {
                location.reload();
            }
        });
    });


    // use bootstrapTable for all tables without class "optional" or data-paging=no
    $('table:not([data-paging="no"]):not(.optional)').bootstrapTable('destroy').bootstrapTable({
        pagination: true,
        search: true
    });

    // use bootstrapTable without paging
    $('table[data-paging="no"]').bootstrapTable('destroy').bootstrapTable({
        pagination: false,
        search: true
    });

    // use boostrapTable with specific options
    $('table.news').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        search: true,
        pageSize: 6,
        showHeader: false
    });


    // replace the "Choose a file" label
    $('input[type="file"]').on('change',function(e){
        var fileName;

        if (e.target.files.length > 0) {
            fileName = e.target.files[0].name;
        } else {
            fileName = '';
        }
        $(this).next('.custom-file-label').html(fileName);
    });

    // add carddeck to wishlist
    $(document).on('click', '.add-to-wishlist', function(e) {
        var current_target = e.currentTarget;
        $.post('/inc/_manage_wishlist.php',{ action:'add',carddeck_id:$(this).attr('data-carddeck-id'),
            rand:Math.random()} ,function(data) {;
            var data_json = $.parseJSON(data);
            if ($('#'+ data_json.id).length == 0) {
                var $new_toast = create_toast(data_json.id, data_json.icon, data_json.title, data_json.text);
                $new_toast.appendTo('#toast-wrapper');
                $new_toast.toast('show');
                $(current_target).removeClass('add-to-wishlist').addClass('remove-from-wishlist');
                $(current_target).find('.fa-plus').removeClass('fa-plus').addClass('fa-minus');

                var wishlist_text = $(current_target).next('.wishlist-text');
                if (wishlist_text.length > 0) {
                    wishlist_text.text(data_json.new_text);
                }
            }
        });
    });

    // remove carddeck from wishlist
    $(document).on('click', '.remove-from-wishlist', function(e) {
        var current_target = e.currentTarget;
        $.post('/inc/_manage_wishlist.php',{ action:'remove',carddeck_id:$(this).attr('data-carddeck-id'),
            rand:Math.random()} ,function(data) {;
            var data_json = $.parseJSON(data);
            if ($('#'+ data_json.id).length == 0) {
                var $new_toast = create_toast(data_json.id, data_json.icon, data_json.title, data_json.text);
                $new_toast.appendTo('#toast-wrapper');
                $new_toast.toast('show');
                $(current_target).removeClass('remove-from-wishlist').addClass('add-to-wishlist');
                $(current_target).find('.fa-minus').removeClass('fa-minus').addClass('fa-plus');

                var wishlist_text = $(current_target).next('.wishlist-text');
                if (wishlist_text.length > 0) {
                    wishlist_text.text(data_json.new_text);
                }
            }
        });
    });
});