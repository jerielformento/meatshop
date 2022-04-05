var route = {
	page: window.location.origin + '/students'
};

var selected = "";

function confBoxEvents() {
    $('#course-add').unbind().on('click', function() {
        if(selected !== "") {
            var elemInfo = startLoader($(this));

            $.ajax({
                url: route.page + '/addStudentCourse',
                type: 'post',
                data: { 
                    course: selected,
                    student:  $('input[name=id]').val()
                },
                success: function(response) {
                    var json = $.parseJSON(response);
                    alertBox(response, 'msg-alert', '', '', 'json', 'fade');
                    if(json['message'][0] === 'success') {
                        $('table[name=student_courses] > tbody').append("<tr><td>"+json['course'][0]+"</td><td>"+json['course'][1]+"</td></tr>");
                    }
                    
                    $('#course-modal').modal('hide');
                    stopLoader(elemInfo[0],elemInfo[1]);
                }
            });
        } else {
            alertBox('', 'msg-alert', 'warning', 'Invalid course selected.', '', 'fade');
        }
    });

    $('#note-add').unbind().on('click', function() {
        var elemInfo = startLoader($(this));

        $.ajax({
            url: route.page + '/addStudentNote',
            type: 'post',
            data: { 
                student:  $('input[name=id]').val(),
                note: $('#note').val()
            },
            success: function(response) {
                var json = $.parseJSON(response);
                alertBox(response, 'msg-alert', '', '', 'json', 'fade');
                if(json['message'][0] === 'success') {
                    $('table[name=student_notes] > tbody').append("<tr><td>"+json['note'][0]+"</td><td>"+json['note'][1]+"</td></tr>");
                }
                
                $('#notes-modal').modal('hide');
                stopLoader(elemInfo[0],elemInfo[1]);
            }
        });
    });

    $('#payment-add').unbind().on('click', function() {
        var elemInfo = startLoader($(this));

        $.ajax({
            url: route.page + '/addStudentPayment',
            type: 'post',
            data: { 
                student:  $('input[name=id]').val(),
                enrolled_id: $('#enrolled-courses').val(),
                details: $('#details').val(),
                amount: $('#amount').val()
            },
            success: function(response) {
                var json = $.parseJSON(response);
                alertBox(response, 'msg-alert', '', '', 'json', 'fade');
                if(json['message'][0] === 'success') {
                    $('table[name=student_payment] > tbody').append("<tr><td>"+json['payment'][0]+"</td><td>"+json['payment'][1]+"</td><td>"+json['payment'][2]+"</td><td>"+json['payment'][3]+"</td></tr>");
                }
                
                $('#payment-modal').modal('hide');
                stopLoader(elemInfo[0],elemInfo[1]);
            }
        });
    });
}

function fillCourseInfo( setVal, appendTo ) {
	$.ajax({
		url: route.page + '/getCourseInfo',
		type: 'post',
		data: { selected: setVal },
		success: function(response) {
            var json = $.parseJSON(response);
            var returnString = "<div class='p-2 border rounded-top mb-2'><p class='font-weight-bold bg-secondary mb-2 mt-n2 ml-n2 mr-n2 p-1 rounded-top text-gray-200'><i class='fa fa-fw fa-eye'></i> Course Details</p>";
            returnString += "<div class='row'>";
			json['course'].forEach(function(ul) {
                returnString += "<div class='col'>";
                returnString += "<span class='font-weight-bold'>Course ID:</span> " + ul['course_id'] + "<br/>";
                returnString += "<span class='font-weight-bold'>Type:</span> " + ul['type'] + "<br/>";
                returnString += "<span class='font-weight-bold'>Date From:</span> " + ul['date_from'] + "<br/>";
                returnString += "<span class='font-weight-bold'>Date To:</span> " + ul['date_to'] + "<br/>";
                returnString += "<span class='font-weight-bold'>Time Schedule:</span> " + ul['time'] + "<br/>";
                returnString += "</div>";
                returnString += "<div class='col'>";
                returnString += "<span class='font-weight-bold'>Tuition Fee:</span> " + ul['tuition_fee'] + "<br/>";
                returnString += "<span class='font-weight-bold'>Downpayment:</span> " + ul['downpayment'] + "<br/>";
                returnString += "<span class='font-weight-bold'>Handout:</span> " + ul['handout'];
                returnString += "</div>";
            });

            returnString += "</div>";
            returnString += "</div>";

            $('#course-info').show(2000).html(returnString);
		}
	});
}

/* On change */
$('select[name=enroll]').on('change', function() {
    var cid = $(this).val();
    if($.trim(cid) !== "") {
        selected = cid;
        fillCourseInfo(cid, '');
    } else {
        $('#course-info').empty();
    }
});

function fillStudentEnrolledCourses( setVal, appendTo ) {
	$.ajax({
		url: route.page + '/fillStudentEnrolledCourses',
		type: 'post',
		data: { id: $('input[name=id]').val() },
		success: function(response) {
			$('#' + appendTo).html(response);
		}
	});
}

/* On load */
$('input[name=birthdate]').datepicker({
    changeMonth: true, 
    changeYear: true, 
    dateFormat: "mm/dd/yy",
    yearRange: "-90:+00"
});

$('button[name=add-course]').on('click', function() {
    if($.trim($('select[name=enroll]').val()) !== "") {
        confirmBox('course-modal','Course Enrollment',"Course ID: <span class='text-primary font-weight-bold'>" + $('select[name=enroll] option:selected').text() + "</span><br/>Enroll student into course?",'course-add','Enroll','success','show-box');
    } else {
        console.log("alert");
        alertBox('', 'msg-alert', 'warning', 'Please select course.', '', 'fade');
    }
});

$('button[name=add-note]').on('click', function() {
    returnString = '<div class="row"><div class="col">';
    returnString += '<div class="form-group mb-2">';
    returnString += '<label>Description <label class="text-danger" style="font-size:16px;">*</label></label>';
    returnString += '<textarea id="note" rows=3 cols=30 style="resize:none;" class="form-control input-sm" placeholder="Add your note here.."></textarea>';
    returnString += '</div>';
    returnString += '</div></div>';
    
    confirmBox('notes-modal','Add Note',returnString,'note-add','Add','success','show-box');
});

$('button[name=add-payment]').on('click', function() {
    returnString = '<div class="row"><div class="col-md-6">';
    returnString += '<label class="font-weight-bold text-primary">Payment Form</label>';
    returnString += '<div class="form-group mb-2">';
    returnString += '<label>Enrolled Course(s) <label class="text-danger" style="font-size:16px;">*</label></label>';
    returnString += '<select id="enrolled-courses" class="form-control input-sm"></select>';
    returnString += '</div>';
    returnString += '<div class="form-group mb-2">';
    returnString += '<label>Details <label class="text-danger" style="font-size:16px;">*</label></label>';
    returnString += '<textarea id="details" rows=3 cols=30 style="resize:none;" class="form-control input-sm" placeholder="Type details here.."></textarea>';
    returnString += '</div>';
    returnString += '<div class="form-group mb-2">';
    returnString += '<label>Amount <label class="text-danger" style="font-size:16px;">*</label></label>';
    returnString += '<input type="text" id="amount" class="form-control input-sm" placeholder="0">';
    returnString += '</div>';
    returnString += '</div>';
    returnString += '<div class="col-md-6">';
    returnString += '<table class="table table-bordered">';
    returnString += '<thead><th class="font-weight-bold text-primary">Course Details (<span id="sel-info">No Selected</span>)</th></thead>';
    returnString += '<tbody id="course-payment-info">';
    returnString += '</tbody>';
    returnString += '</table>';
    returnString += '</div></div>';
    
    confirmBoxLarge('payment-modal','Add Payment',returnString,'payment-add','Confirm Payment','success','show-box');
    fillStudentEnrolledCourses('','enrolled-courses');

    $('#enrolled-courses').unbind().on('change', function() {
        var sel_course = $(this).val();

        if(sel_course !== "") {
            $('#sel-info').html($('#enrolled-courses option:selected').text());

            $.ajax({
                url: route.page + '/getPaymentCourseInfo',
                type: 'post',
                data: { course: sel_course },
                success: function(response) {
                    var json = $.parseJSON(response);
                    var returnString = "<tr><td>";
                    returnString += "<label class='font-weight-bold'>Course Type:</label> " + json['course'][0]['type'] + "<br/>";
                    returnString += "<label class='font-weight-bold'>Date From:</label> " + json['course'][0]['date_from'] + "<br/>";
                    returnString += "<label class='font-weight-bold'>Date To:</label> " + json['course'][0]['date_to'] + "<br/>";
                    returnString += "<label class='font-weight-bold'>Time Schedule:</label> " + json['course'][0]['time'] + "<br/>";
                    returnString += "<label class='font-weight-bold'>Tuition Fee:</label> " + json['course'][0]['tuition_fee'] + "<br/>";
                    returnString += "<label class='font-weight-bold'>Downpayment:</label> " + json['course'][0]['downpayment'] + "<br/>";
                    returnString += "<label class='font-weight-bold'>Handout:</label> " + json['course'][0]['handout'];
                    returnString += "</td></tr>";
    
                    $('#course-payment-info').html(returnString);
                }
            });
        } else {
            $('#sel-info').html("No Selected");
            $('#course-payment-info').empty();
        }
    });

    $("#amount").val(function(index, value) {
        return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            ;
    });
    
    $("#amount").unbind().keyup(function(event) {
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

var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
};