$(document).ready(function() {
    $('#menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });

    $('.switch-language').on('click', function(e) {
        e.preventDefault();
        $.post('/inc/_switch_language.php',{ language:$(this).attr('data-language'),
            rand:Math.random()} ,function(data) {;
            if (data == 'switch') {
                location.reload();
            }
        });
    });
    
    $('table:not([data-paging="no"]):not(.optional)').bootstrapTable('destroy').bootstrapTable({
        pagination: true,
        search: true
    });

    $('table.news').bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-borderless',
        pagination: true,
        search: true,
        pageSize: 6,
        showHeader: false
    });

    $('table[data-paging="no"]').bootstrapTable('destroy').bootstrapTable({
        pagination: false,
        search: true
    });

    $('input[type="file"]').on('change',function(e){
        //get the file name
        var fileName;

        if (e.target.files.length > 0) {
            fileName = e.target.files[0].name;
        } else {
            fileName = '';
        }
        //replace the 'Choose a file' label
        $(this).next('.custom-file-label').html(fileName);
    });

    //$('[required]').closest('.form-group').addClass('form-required');
});