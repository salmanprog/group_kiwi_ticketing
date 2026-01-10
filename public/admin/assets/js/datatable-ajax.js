$(document).ready( function(){

    var ids;
    var action;
    var reorder_obj={};
    var reorder_row = typeof default_reorder_row == 'undefined' ? false : reorder_row;

    var table = $('#_ajax_datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "bLengthChange": false,
        "rowReorder": reorder_row,
        "ajax":{
            url : ajax_listing_url,
            type: "GET",
            beforeSend : function(){

            },
            data : function(d) {
                d.reorder = reorder_obj,
                d.keyword = $('input[name="keyword"]').val();
                d.start_date = $('input[name="start_date"]').val();
                d.end_date = $('input[name="end_date"]').val();
                d.status = $('select[name="status"]').val();
            },
            error: function(){  // error handling

            }
        },
        drawCallback: function (settings) {
            // other functionality
                 if (settings.json && settings.json.recordsFiltered !== undefined) {
                    $('#total-count').html(settings.json.recordsFiltered);
                } else {
                    $('#total-count').html(0);
                }
        }
    });

    table.on( 'row-reorder', function ( e, diff, edit ) {

        let reorderStart   = edit.triggerRow.data()[0];
        let row_start_slug = $(reorderStart).attr('value');

        reorder_obj.slug        = row_start_slug;
        reorder_obj.newPosition = diff[0].newPosition
        reorder_obj.oldPosition = diff[0].oldPosition;

        console.log('row_start_slug',row_start_slug);
        console.log('diff[0].newPosition',diff[0].newPosition)
        console.log('diff[0].oldPosition',diff[0].oldPosition);

    });

    $(document).on( 'click','._delete_record',function(e){
        e.preventDefault();
        // var slug = $(this).parent().parent().find('.record_id').val();
        var slug = $(this).data('slug');
        // alert(slug);
        alertify.confirm('Confirmation Alert', 'Are you sure you want to delete this record?', function(){
           //confirm
           let request_url = window.location.href + '/delete-record';
           ajaxRequest(request_url,'DELETE',{slug:slug}).then( function(res){
                $.toast({
                    heading: 'Success',
                    text: res.message,
                    icon: 'success',
                    position:'top-right',
                })
               table.ajax.reload();
           }).catch(err => alert(err.message))

        } , function(){
            //cancel
        });
    })

    $('#search_form').submit( function(e){
        e.preventDefault();
        table.ajax.reload();
    })

    $('#filter_form').submit( function(e){
        e.preventDefault();
            table.ajax.reload();
        
    })



    $(document).on('click','.checked_all',function(){
        if( $(this).is(':checked') ){
            $('.record_id').prop('checked',true);
        } else {
            $('.record_id').prop('checked',false);
        }
    })

    $('.bulk_delete').click(function(e){
        e.preventDefault();
        var slug = []
        $('.record_id:checked').each( function(){
            slug.push( $(this).val() )
        });
        if( slug.length > 0 ){
            alertify.confirm('Confirmation Alert', 'Are you sure you want to delete records?', function(){
                //confirm
                let request_url = window.location.href + '/delete-record';
                ajaxRequest(request_url,'DELETE',{slug:slug}).then( function(res){
                    $.toast({
                        heading: 'Success',
                        text: res.message,
                        icon: 'success',
                        position:'top-right',
                    })
                })
                table.ajax.reload();
            } , function(){
                //cancel
            });
        } else {
            alertify.alert('Alert ','Kindly select a record', () => {});
        }
    })
})

