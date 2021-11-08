<script type="text/javascript">
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
  });

    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: delete_data.url,
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                massDestroy(config.url,ids);
            }
        }

    dtButtons.push(deleteButton)


    var load_url = $('#server-side-datatable').data('url');

  // On Load Table
  var table = $('#server-side-datatable').DataTable({
    buttons: dtButtons,
    "processing": true,
    "retrieve": true,
    "serverSide": true,
    'paginate': true,
    'searchDelay': 100,
    "bDeferRender": true,
    "responsive": true,
    "autoWidth": true,
    "pageLength": 10,
    "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    ajax: load_url,
    columns: coloumn_data
  });


    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });


    //On click Delete
    $('body').on('click', '.delete', function () {
    var url = $(this).data("url");
    var alert_text = $(this).data("title");
        deleteThis(url,alert_text);
    });

});

//Common Delete with sweet-alert
function deleteThis(url,text){
    Swal.fire({
        text: text,
        icon: "warning",
        confirmButtonText: "Yes, delete it!",
        cancel: true,
        showCloseButton: true,
        showCancelButton: true,
    }).then(
        function (result) {
            if (result.value) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: {"_token": "{{ csrf_token() }}"},
                success: function (data) {
                    Swal.close();
                    showAlertMessage('success', data.message);
                    setTimeout(function(){ 
                        window.location.href  = data.redirect_url;
                    }, 2000);
                },
                error: function (data) {
                    console.log('Error:', data);
                    location.reload();
                }
            });
        }
    });
}



// mass Delete with sweet-alert
function massDestroy(delete_url,selected_ids){
    Swal.fire({
        text: "Are you sure, you want to delete all the selected data?",
        icon: "warning",
        confirmButtonText: "Yes, delete them!",
        cancel: true,
        showCloseButton: true,
        showCancelButton: true,
    })
    .then(
        function (result) {
            if (result.value) {
                $.ajax({
                    type: 'DELETE',
                    url: delete_url,
                    data: { ids: selected_ids, _method: 'DELETE', _token: "{{ csrf_token() }}" }
                }).done(function () { 
                    showAlertMessage('success', 'Selected data has been deleted.');
                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                });
            }
        }
    );
}


</script>