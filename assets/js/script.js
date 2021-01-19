$(document).ready(function() {
    $('#menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });
    
    $('table').bootstrapTable('destroy').bootstrapTable({
        pagination: true,
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