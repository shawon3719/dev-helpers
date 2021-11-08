<script>
    $(function () {
    // On change item Select 2
    $('.item-select').on('change', function() {
      
        var item_id = $('#item_id').val();

      $.ajax({
        type: "GET",
        url: "/diagnostic-centre/items"+'/'+item_id,
        beforeSend: function () {
        //   $('.service-info-loader').removeClass('d-none');
        },
        success: function (data) {
            if(data.item){
                var price = data.item.offer_price > 0 ? data.item.offer_price : data.item.price;
                var tr = '<tr>'+
                            '<td><a type="button" class="remove btn btn-sm btn-danger"><i class="fas fa-times"></i></a></td>'+
                            '<td><input type="hidden" placeholder="item" name="item_id[]" value='+data.item.id+' class="form-control price">'+
                            '<input readonly type="text" placeholder="item" value='+data.item.name+' class="form-control price"></td>'+
                            '<td><input readonly type="text" placeholder="price" name="price[]" value='+price+' class="item_price price form-control"></td>'+
                            '<td><input type="text" placeholder="quantity" name="quantity[]" value="1" class="quantity form-control"></td>'+
                            '<td><input type="text" placeholder="discount %" name="discount_percentage[]" class="discount-percentage form-control"></td>'+
                            '<td><input readonly type="text" placeholder="discount amt." name="discount_amount[]" class="discount-amount form-control"></td>'+
                            '<td><input readonly type="text" placeholder="total" name="total[]" value='+price+' class="total-amount form-control"></td>'+
                            '<td></td>'+
                        '</tr>';

                $(".bills-table tbody").find("tr:nth-last-child(7)").prev().after(tr);
                grandCalculation();

            }
        },
        error: function (data) {
            console.log('Error:', data);
        }
      });
    });


    // On click Create New patient button
    $('.add-patient').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        type: "GET",
        url: "/admin/patients/create",
        beforeSend: function () {
        //   $('.service-info-loader').removeClass('d-none');
        },
        success: function (data) {

            $('#commonModalHeading').text('Create New Patient');
            $('#commonModalBody').html(data);
            $('.common-date').datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                "format": "MM/DD/YYYY",
            });

        },
        error: function (data) {
            console.log('Error:', data);
        }
      });

      $('#commonModal').modal('show');
    });

    // On click Modal Close

    $('.closeModal').on('click', function(e) {
      e.preventDefault();
      $('#commonModal').modal('hide')
    });

    // On click Create New Item button
    $('.add-new-item').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        type: "GET",
        url: "/diagnostic-centre/items/create",
        beforeSend: function () {
        //   $('.service-info-loader').removeClass('d-none');
        },
        success: function (data) {

            $('#commonModalHeading').text('Create New item');
            $('#commonModalBody').html(data);
            $('.common-date').datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                "format": "MM/DD/YYYY",
            });

        },
        error: function (data) {
            console.log('Error:', data);
        }
      });

      $('#commonModal').modal('show');
    });

    // On click Create New Referrer button
    $('.add-referrer').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        type: "GET",
        url: "/admin/doctors/create",
        beforeSend: function () {
        //   $('.service-info-loader').removeClass('d-none');
        },
        success: function (data) {

            $('#commonModalHeading').text('Create New Referrer');
            $('#commonModalBody').html(data);
            $('.common-date').datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                "format": "MM/DD/YYYY",
            });

        },
        error: function (data) {
            console.log('Error:', data);
        }
      });

      $('#commonModal').modal('show');
    });

     // dynamic form Submit
     $(document).on('submit', '.dynamicFormSubmit', function (e) {
         e.preventDefault();
         let thisModal = $(this).closest('.modal');
            let postUrl = $('.dynamicFormSubmit').attr('action');
            let formData = new FormData($('.dynamicFormSubmit')[0]);
            $.ajax({
                type: "POST",
                url: postUrl,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                // dataType: "json",
                success: function (data) {

                    if(data.patient){
                        var $options = $("select[name='patient_id']").empty();
                            if (data.patients === null || data.length === 0) {
                                $options.append($("<option />").val('').text('No patients Found'));
                            } else {
                                $.each(data.patients, function () {
                                    $options.append($("<option />").val(this.id).text(this.name));
                                });
                            }
                        $('#patient_id').val(data.patient.id).trigger('change');
                    }
                    if(data.referrer){
                        var $options = $("select[name='referrer_id']").empty();
                            if (data.referrers === null || data.length === 0) {
                                $options.append($("<option />").val('').text('No referrers Found'));
                            } else {
                                $.each(data.referrers, function () {
                                    $options.append($("<option />").val(this.id).text(this.name));
                                });
                            }
                        $('#referrer_id').val(data.referrer.id).trigger('change');
                    }

                    if(data.item){
                        console.log(data);
                        var $options = $("select[name='item_id']").empty();
                            if (data.items === null || data.length === 0) {
                                $options.append($("<option />").val('').text('No items Found'));
                            } else {
                                $.each(data.items, function () {
                                    $options.append($("<option />").val(this.id).text(this.name));
                                });
                            }
                        $('#item_id').val(data.item.id).trigger('change');
                        grandCalculation();
                    }


                    $options.select2();
                    thisModal.modal('hide');
                    Toastify({
                        text: 'Successfully added!',
                        duration: 5000,
                        close:true,
                        gravity:"top",
                        position: "center",
                        backgroundColor: 'green',
                    }).showToast();
                    

                },
                error: function (errorThrown) {
                    $.LoadingOverlay("hide");
                    alert('Request failed :' + errorThrown);
                }
            });
        });


    //on change quantity
     $(document).on('keyup', '.quantity', function (e) {

        e.preventDefault();
        
        var thisRow = $(this).closest('tr');
        var quantity = $(this).val();
        var price = thisRow.find('.item_price').val();

        var total = quantity*price;

        thisRow.find('.total-amount').val(total);

        grandCalculation();

     });

     //on change quantity
     $(document).on('keyup', '.discount-percentage', function (e) {

        e.preventDefault();

        var thisRow = $(this).closest('tr');
        var discount_percentage = $(this).val();
        var price = thisRow.find('.item_price').val();
        var quantity = thisRow.find('.quantity').val();
        var total_price = price * quantity;
        var discount_amount = total_price * (discount_percentage/100); 
        var total = (quantity*price) - discount_amount;

        thisRow.find('.discount-amount').val(discount_amount);
        thisRow.find('.total-amount').val(total);

        grandCalculation();

    });

    $(document).on('keyup', '.tax', function (e) {
        var sub_total = $('.sub-total').val();
        if(sub_total > 0){
            grandCalculation();
        }
        
    });
    $(document).on('keyup', '.discount', function (e) {
        var sub_total = $('.sub-total').val();
        if(sub_total > 0){
            grandCalculation();
        }
    });

    $(document).on('click', '.remove', function (e) {
        $(this).closest('tr').remove();
        grandCalculation();
    });
     

     function grandCalculation(){
        var total = 0;
        $('.total-amount').each(function() {
            total += Number($(this).val());
        });

        var tax = $('.tax').val();
        var discount = $('.discount').val();

        var paid = $('.paid-amount').val();

        var net_payable = total-tax-discount-paid;

        $('.sub-total-text').text(total); 
        $('.sub-total').val(total); 

        $('.net-payable-text').text(net_payable); 
        $('.net-payable').val(net_payable);  
     }



});

</script>
<script>

    function selectRefresh() {
        $('.select2').select2();
    }

    $(document).ready(function() {
        selectRefresh();
    });

</script>