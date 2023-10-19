$(function(){
    console.log('datatable-export.js');
    
    var buttonCommon = {
        init: function (dt, node, config) {
            var table = dt.table().context[0].nTable;
            if (table) config.title = $(table).data('export-title')
        },
        title: 'default title'
    };
    
    $.extend($.fn.dataTable.defaults, {
        dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            $.extend(true, {}, buttonCommon, {
                extend: 'excelHtml5',
                footer: true,
                exportOptions: {
                    columns: [':visible :not(:last-child)']
                }
            }),
            $.extend(true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                footer: true,
                exportOptions: {
                    columns: [':visible :not(:last-child)']
                },
                orientation: 'landscape',
                pageSize: 'A4',
                customize: function (doc) {        
                    doc.defaultStyle.fontSize = 10;}
            }),
            $.extend(true, {}, buttonCommon, {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [':visible :not(:last-child)']
                }
            })
        ],
        initComplete: function () {
            var $buttons = $('.dt-buttons').hide();
            $('.export-dropdown a').click(function (e) {
                e.preventDefault();
                var btnClass = $(this).attr('id') ? '.buttons-' + $(this).attr('id') : null;
                if (btnClass) $buttons.find(btnClass).click();
            })
        }
    })
})