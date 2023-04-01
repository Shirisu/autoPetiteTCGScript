function create_toast(id, icon, title, text) {
    return $(
        '<div class="toast" id="toast-add-to-wishlist-' + id + '" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">' +
        '  <div class="toast-header">' +
        '    <i class="fas fa-' + icon + ' me-2"></i>' +
        '    <strong class="me-auto">' + title + '</strong>' +
        '    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' +
        '  </div>\n' +
        '  <div class="toast-body">' +
            text +
        '  </div>\n' +
        '</div>'
    );
}

function showingRowsText(pageFrom, pageTo, totalRows) {
    return '<ul class="pagination"><li class="page-item"><span class="page-link">' + pageFrom + ' - ' + pageTo + '</span></li> <li class="page-item"><span class="page-link">' + totalRows + '</span></li></ul>';
}

$(document).ready(function() {
    // switch language
    $('.switch-language').on('click', function(e) {
        e.preventDefault();
        $.post(tcgHostUrl+'/inc/_switch_language.php',{ language:$(this).attr('data-language'),
            rand:Math.random()} ,function(data) {;
            if (data == 'switch') {
                location.reload();
            }
        });
    });

    // use bootstrapTable for all tables without class "optional" or data-paging=no
    $('table:not([data-paging="no"]):not([data-search="no"]):not(.optional)').bootstrapTable('destroy').bootstrapTable({
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        sortable: true
    });

    // use bootstrapTable without paging
    $('table[data-paging="no"]').bootstrapTable('destroy').bootstrapTable({
        pagination: false,
        search: true,
        searchAccentNeutralise: true,
        sortable: true
    });

    // use bootstrapTable without paging
    $('table[data-search="no"]').bootstrapTable('destroy').bootstrapTable({
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: false,
        searchAccentNeutralise: true,
        sortable: true
    });

    // use boostrapTable with specific options
    $('table.news').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 6,
        showHeader: false
    });

    function initTradeCards() {
        $('table.profile-cards.trade-cards').bootstrapTable('destroy').bootstrapTable({
            classes: 'table table-borderless',
            pagination: true,
            paginationParts: ['pageList'],
            formatShowingRows: function(pageFrom, pageTo, totalRows) {
                return showingRowsText(pageFrom, pageTo, totalRows);
            },
            search: true,
            searchAccentNeutralise: true,
            pageSize: 60,
            showHeader: false
        });
    }
    initTradeCards();

    $('#filterTradeCards').on('click', function () {
        var $table = $('table.profile-cards.trade-cards');
        var data = $table.bootstrapTable('getData');
        $table.bootstrapTable('load', $.grep(data, function (row) {
            $table.closest('.bootstrap-table').find('.search').hide();
            return row.filtercard.split(' ').indexOf('needed') > -1;
        }));
    });

    $('#resetFilterTradeCards').on('click', function() {
        initTradeCards();
    });

    $('table.profile-cards.collect-cards').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 12,
        showHeader: false
    });

    $('table.profile-cards.master-cards').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 60,
        showHeader: false
    });

    $('table.cards-sorting-table.new-cards, table.cards-sorting-table.trade-cards').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 60,
        showHeader: false
    });

    $('table.cards-sorting-table.master-cards').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 60,
        showHeader: false
    });

    $('table.cards-sorting-table.collect-cards').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 12,
        showHeader: false
    });

    $('table.tradein').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        cardView: true,
        pagination: true,
        paginationParts: ['pageList'],
        formatShowingRows: function(pageFrom, pageTo, totalRows) {
            return showingRowsText(pageFrom, pageTo, totalRows);
        },
        search: true,
        searchAccentNeutralise: true,
        pageSize: 60,
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
        $.post(tcgHostUrl+'/inc/_manage_wishlist.php',{ action:'add',carddeck_id:$(this).attr('data-carddeck-id'),
            rand:Math.random()} ,function(data) {;
            var data_json = $.parseJSON(data);
            if ($('#'+ data_json.id).length == 0) {
                var $new_toast = create_toast(data_json.id, data_json.icon, data_json.title, data_json.text);
                $new_toast.appendTo('#toast-container');
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
        $.post(tcgHostUrl+'/inc/_manage_wishlist.php',{ action:'remove',carddeck_id:$(this).attr('data-carddeck-id'),
            rand:Math.random()} ,function(data) {;
            var data_json = $.parseJSON(data);
            if ($('#'+ data_json.id).length == 0) {
                var $new_toast = create_toast(data_json.id, data_json.icon, data_json.title, data_json.text);
                $new_toast.appendTo('#toast-container');
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

    $('.selectpicker').selectpicker({
        liveSearch: true,
    });
});