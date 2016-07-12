$(document).ready(function() {
    $('.nav-tabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    $('.transactions tbody tr').bind('click',function (e) {
        $('.transaction-select').removeClass('transaction-select');
        $(e.target).parent('tr').addClass('transaction-select');
        $('#delete').css({
            pointerEvents: 'auto',
            opacity: 1
        })
    });
    
    $('#delete-transaction').bind('click',function () {
        var transactionIndex = $('#transaction-table tbody').children('.transaction-select').index();
        $.ajax({
            type: 'POST',
            url: location.href,
            data: 'transactionIndex=' + transactionIndex,
            success: function (result) {
                if (result)
                    location.reload();
            }
        });
    });

    $('.delete-category').bind('click',function (e) {
        var categoryIndex = $(e.target).parent().parent().index();
        $.ajax({
            type: 'POST',
            url: location.href,
            data: 'categoryIndex=' + categoryIndex,
            success: function (result) {
                if (result)
                    location.reload();
            }
        })
    });
});
