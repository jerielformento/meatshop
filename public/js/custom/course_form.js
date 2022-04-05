$(function() {
        $('input[name=course_id]').keyup(function(){
            this.value = this.value.toUpperCase();
        });
    
        $('input[name=time_from], input[name=time_to]').timepicker();  
        $('input[name=date_from], input[name=date_to]').datepicker();  
      
        $("input[name='tuition_fee'], input[name='downpayment'], input[name='handout']").val(function(index, value) {
          return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            ;
        });

        $("input[name='tuition_fee'], input[name='downpayment'], input[name='handout']").keyup(function(event) {

          // skip for arrow keys
          if(event.which >= 37 && event.which <= 40) return;
        
          // format number
          $(this).val(function(index, value) {
            return value
              .replace(/\D/g, "")
              .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              ;
            });
        });
});