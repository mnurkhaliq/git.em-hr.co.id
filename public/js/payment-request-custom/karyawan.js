
var general_el;
var validate_form = false;
var period_ca_pr;
var calculate_amount  = function(){
    var total = 0;
    $('.amount').each(function(){
        if($(this).val() != ""){
            // total += parseInt($(this).val());
            var value = $(this).val();
            total += parseInt(value.split('.').join(''));
        }
    });

    $('.total_amount').html(numberWithComma(total).replace(/,/g, "."));
}

calculate_amount();

var calculate_estimation  = function(){
    var total = 0;
    $('.estimation').each(function(){
        if($(this).val() != ""){
            total += parseInt($(this).val());
        }
    });

    $('.total_estimation').html(numberWithComma(total).replace(/,/g, "."));
}

$("#add_overtime").click(function(){

    var el = "";

    $("input[name=overtime_item]:checked").each(function(){
        el += '<input type="hidden" name="overtime[]" value="'+ $(this).val() +'" />';
    });

    general_el.parent().parent().find('.content_overtime').html(el);

    $("#modal_overtime").modal('hide');
});

$("#add_modal_bensin").click(function(){

    var cost = $('.modal-cost').val();

    general_el.parent().find("input[name='amount[]']").val(cost);

    var tanggal     = $('.modal_tanggal_struk_bensin').val();
    var odo_from    = $('.modal_odo_from').val();
    var odo_to      = $('.modal_odo_to').val();
    var liter       = $('.modal_liter').val();
    var cost        = $('.modal_cost').val();

    var el = '<div class="bensin"><a class="btn btn-info btn-xs" onclick="info_bensin(this)"><i class="fa fa-info"></i></a><input type="hidden" name="bensin[tanggal][]" value="'+ tanggal +'" />';
        el += '<input type="hidden" name="bensin[odo_from][]" value="'+ odo_from +'" />';
        el += '<input type="hidden" name="bensin[odo_to][]" value="'+ odo_to +'" />';
        el += '<input type="hidden" name="bensin[liter][]" value="'+ liter +'" />';
        el += '<input type="hidden" name="bensin[cost][]" value="'+ cost +'" /></div>';

    general_el.parent().parent().find('.content_bensin').html(el);
    general_el.parent().parent().parent().find("input[name='description[]']").val('Bensin');
    general_el.parent().parent().parent().find("input[name='quantity[]']").val(liter);
    general_el.parent().parent().parent().find("input[name='amount[]']").val(cost);

    $("#form_modal_bensin").trigger('reset');
    $("#modal_bensin").modal("hide");

    calculate_amount();
});


function info_bensin(el)
{
    $("#modal_bensin").modal('show');

    var el = $(el).parent();

    var tanggal = el.find("input[name='bensin[tanggal][]']").val();
    var odo_from = el.find("input[name='bensin[odo_from][]']").val();
    var odo_to = el.find("input[name='bensin[odo_to][]']").val();
    var liter = el.find("input[name='bensin[liter][]']").val();
    var cost = el.find("input[name='bensin[cost][]']").val();

    $('.modal_tanggal_struk_bensin').val(tanggal);
    $('.modal_odo_from').val(odo_from);
    $('.modal_odo_to').val(odo_to);
    $('.modal_liter').val(liter);
    $('.modal_cost').val(cost);

    general_el = el.parent().parent().parent().find("select[name='type[]']");
}

function form_validate() {
    var validate = true;
    $('.oninput input').each(function(){

        if(($(this).val() == "" || $(this).val() <= 0) && ($(this).prop('required')))
        {
            $(this).parent().addClass('has-error');
            validate = false;
        }
    });
    $('.oninput select').each(function(){

        if($(this).val() == null || $(this).val() == "" && ($(this).prop('required')))
        {
            $(this).parent().addClass('has-error');
            validate = false;
        }
    });
    return validate;
}

$('#submit_payment').click(function(){
    var tujuan          = $("textarea[name='tujuan']").val();
    var payment_method  = $("input[name='payment_method']:checked").val();

    $('.oninput').find('td').removeClass("has-error");
    $('.oninput').find('div').removeClass("has-error");
    var jumlah = $('.table-content-lembur tr').length;

    if(jumlah <= 0)
    {
        bootbox.alert('Form not completed. Please check and resubmit.');
        validate = false;
        return;
    }
    var validate_form = form_validate();

    // validate form 
    $('.oninput').find('td').removeClass("has-error");
    var jumlah = $('.table-content-lembur tr').length;

    if(jumlah <= 0)
    {
        bootbox.alert('Form not completed. Please check and resubmit.');
        validate = false;
        return;
    }
    var validate_form = form_validate();

    if(!tujuan || !payment_method || !validate_form)
    {
        bootbox.alert('Form must be completed!');
        return false;
    }

    bootbox.confirm("Do you want process this Payment Request?", function(result) {
        if(result)
        {
            $("#form_payment").submit();
        }
    });
});

show_hide_add();
cek_button_add();

function show_hide_add()
{       
    validate_form = true;
    
    $('.input').each(function(){
     
        if($(this).val() == "" && ($(this).prop('required')))
        {
            validate_form = false;
        }
    });

}

function cek_button_add()
{
    $('.input').each(function(){
        $(this).on('keyup',function(){
            show_hide_add();
        })
        $(this).on('change',function(){
            show_hide_add();
        })
    });
    var rowCount = $(".table-content-lembur tr").length;
    if(rowCount == 1) {
        $("#showhide").hide();
    }
    else{
        $("#showhide").show();
    }
}

$("#btn_cancel_bensin, #btn_cancel_overtime").click(function(){

    $(general_el).val(""); // set default value
});

function select_type_(el)
{
    if($(el).val() == 'Transport(Overtime)')
    {
        $("#modal_overtime").modal("show");
    }else if($(el).val() == 'Gasoline')
    {
        $("#modal_bensin").modal("show");
    }else {
        $(el).parent().parent().find('.bensin').remove();
    }

    var plafond_value = $(el).find(":selected").data('plafond');

    $.ajax({
        type: 'GET',
        url: '/karyawan/payment-request-type/get-plafond',
        data: {
            'type' : $(el).val()
        },
        dataType: 'json',
        success: function (data) {
            period_ca_pr = data.period_ca_pr
            data = data.data
            if(data.hasOwnProperty('sisa_plafond')){
                $(el).parent().parent().parent().find('.sisa_plafond_value').val(numberWithDot(data.sisa_plafond));
                $(el).parent().parent().parent().find('.amount').attr("max",data.sisa_plafond)
                cek_plafond_all($(el).val(),$(el).closest("tr").index())
            }
            else{
                if(data.plafond != null){
                    $(el).parent().parent().parent().find('.sisa_plafond_value').val(numberWithDot(data.plafond));
                    $(el).parent().parent().parent().find('.amount').attr("max",data.plafond)
                    if(period_ca_pr == 'yes'){
                        cek_plafond_all($(el).val(),$(el).closest("tr").index())
                    }
                }
                else{
                    $(el).parent().parent().parent().find('.sisa_plafond_value').val('');
                }
            }
            $(el).parent().parent().parent().find('.plafond_value').val(numberWithDot(plafond_value));
        }
    });

    $(".estimation").on('input', function(){
        calculate_estimation();
    });

    $(".amount").on('input', function(){
        calculate_amount();
    });

    general_el = $(el);
}
$(".amount").on('input', function(){
    calculate_amount();
});

function cek_plafond_all(type, index){
    var total = 0;
    $('.type_form').each(function(index2){
        if($(this).val() == type){
            sisa_plafond = $(this).closest("tr").find('.sisa_plafond_value').val().split('.').join('')
            amount = $(this).closest("tr").find('.amount').val().split('.').join('')
            sisa = parseInt(sisa_plafond) - parseInt(amount)
            if(amount){
                total = parseInt(total) + parseInt(amount)
            }
            // console.log(sisa_plafond, amount, total, sisa_plafond-total, sisa_plafond-amount);
            if(index != 0 && index == $(this).closest("tr").index() && sisa_plafond-total >= 0){
                if(amount > 0){
                    $(this).closest("tr").find('.sisa_plafond_value').val(numberWithDot(sisa_plafond-(total-amount)))
                    $(this).parent().parent().parent().find('.amount').attr("max",sisa_plafond-(total-amount))
                }
                else{
                    $(this).parent().parent().parent().find('.sisa_plafond_value').val(numberWithDot(sisa_plafond-total))
                    $(this).parent().parent().parent().find('.amount').attr("max",sisa_plafond-total)
                }
                
            }
            else{
                $(this).closest("tr").find('.sisa_plafond_value').val(numberWithDot(sisa_plafond))
                // $(this).parent().parent().parent().find('.sisa_plafond_value').val(numberWithDot(sisa_plafond))
            }

            if(sisa < 0 || total > sisa_plafond  && index == $(this).closest("tr").index()){
                value = sisa_plafond-(total-amount) >=0 ? sisa_plafond-(total-amount) : 0
                // console.log(value)
                $(this).closest("tr").find('.sisa_plafond_value').val(numberWithDot(value))
                $(this).closest("tr").find('.amount').val(numberWithDot(value))
            }
            // console.log(sisa_plafond)
        }
    });
}


function cek_amount(el){
    value = $(el).val().split('.').join('')
    index = $(el).closest("tr").index();

    if(period_ca_pr=='yes'){
        if(parseInt($(el).attr('max')) <= parseInt(value)){
            $(el).val(numberWithDot($(el).attr('max')))
            // $(el).parent().parent().find('.sisa_plafond_value').val(0);
        }
        else if(parseInt($(el).attr('max')) > parseInt(value)){
            sisa = parseInt($(el).attr('max')) - parseInt(value)
            // $(el).parent().parent().find('.sisa_plafond_value').val(numberWithDot(sisa));
        }
        cek_amount_plafond($(el).parent().parent().find('.sisa_plafond_value').val().split('.').join(''), $(el).parent().parent().find('.type_form').val(),$(el).closest("tr").index())
    }
    calculate_amount();

}

function cek_amount_plafond(sisa_plafond, type, index){
    var total = 0;
    console.log(sisa_plafond)
    $('.type_form').each(function(index2){
        if($(this).val() == type){
            // sisa_plafond = $(this).closest("tr").find('.sisa_plafond_value').val().split('.').join('')
            amount = $(this).closest("tr").find('.amount').val().split('.').join('')
            sisa = parseInt(sisa_plafond) - parseInt(amount)
            if(amount && $(this).closest("tr").index() >= index){
                total = parseInt(total) + parseInt(amount)
            }
            console.log(sisa_plafond, amount, total, sisa_plafond-total, sisa_plafond-amount);
            if($(this).closest("tr").index() > index && sisa_plafond > 0){
                $(this).closest("tr").find('.sisa_plafond_value').val(numberWithDot(sisa_plafond-(total-amount)))
                $(this).closest("tr").find('.amount').attr("max",sisa_plafond-(total-amount))
                if(sisa_plafond-(total-amount) < amount){
                    $(this).closest("tr").find('.amount').val(numberWithDot(sisa_plafond-(total-amount)))
                    if(sisa_plafond-(total-amount) <= 0){
                        $(this).closest("tr").find('.sisa_plafond_value').val(numberWithDot(0))
                        $(this).closest("tr").find('.amount').attr("max",0)
                        $(this).closest("tr").find('.amount').val(numberWithDot(0))
                    }
                }
            }
        }
    });
}

function delete_item(el)
{
    if(confirm('Delete this item?'))
    {
        $(el).parent().parent().hide("slow", function(){
            if(period_ca_pr=='yes'){
                cek_amount_plafond(parseInt($(el).parent().parent().find('.sisa_plafond_value').val().split('.').join(''))+parseInt($(el).parent().parent().find('.amount').val().split('.').join('')), $(el).parent().parent().find('.type_form').val(),$(el).closest("tr").index())
            }

            $(el).parent().parent().remove();

            setTimeout(function(){
                show_hide_add();
                cek_button_add();
            });
        });

        var rowCount = $(".table-content-lembur tr").length - 1;
        if(rowCount == 1) {
            $("#showhide").hide();
        }
        else{
            $("#showhide").show();
        }

        $('.nomor').each(function(index2){
            if(index2 > $(el).closest("tr").index()){
                $(this).html(index2)
            }
        });
    }
}

