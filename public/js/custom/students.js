/*  */ 

var route = {
	page: 'students'
};

var rHandler = "";

function ajxDispList( page, data, appendTo, startPage, endPage, currPage ) { 

	$("#" + appendTo).html(ajxLoader());
	
	page = '/' + route.page + '/' + page;
	
	$.post(page, data, function(response) {
		var counter = 1;
		var user = "";
		var limit = 0;
		// get response
		
		var json = $.parseJSON(response);
		var names = Object.getOwnPropertyNames(json);
		var fields = Object.keys(names).length;
		var count = Object.keys(json["studentlist"]).length;
		console.log(json);
		var returnString = "";
		
		returnString = "<thead>";
		returnString += "<tr>";
		returnString += "<th class=\"active\"><span>Photo</span></th>";	
		returnString += "<th class=\"active\"><span>Full Name</span></th>";	
		returnString += "<th class=\"active\"><span>Mobile Number</span></th>";	
		returnString += "<th class=\"active\"><span>Email Address</span></th>";	
		returnString += "<th class=\"active\"><span>Status</span></th>";	
		returnString += "<th class=\"active\"><span>Actions</span></th>";	
		returnString += "</tr>" +
		"</thead>" +
		"<tbody>";

		json['studentlist'].forEach(function(ul) {
			returnString += "<tr>";
			returnString += "<td><img src='uploads/get_image.php?image=" + ul['photo_path'] + "' width=50 height=50 class='bg-gray-200'/></td>";
			returnString += "<td>" + ul['full_name'] + "</td>";
			returnString += "<td>" + ul['mobile_number'] + "</td>";
			returnString += "<td>" + ul['email_address'] + "</td>";
			var status = (ul['status'] == "Active") ? "<span class='badge badge-success'>" + ul['status'] + "</span>" : "<span class='badge badge-secondary'>" + ul['status'] + "</span>";
			returnString += "<td>" + status + "</td>";
			returnString += "<td style=\"text-align:center;\">";
            returnString += "<a class=\"btn btn-warning btn-sm mr-2 u-edit\" href=\"students/edit?id=" + ul['id'] + "\" title=\"Edit\"><span class=\"fa fa-fw fa-edit\" style=\"cursor:pointer;\"></span></a>";
            returnString += "<button class=\"btn btn-danger btn-sm mr-2 u-del\" id=\"" + ul['id'] + "\" cid=\"" + ul['course_id'] + "\" title=\"Remove\"><span class=\"fa fa-fw fa-trash\" style=\"cursor:pointer;\"></span></button>";
            returnString += "</td>";
			returnString += "</tr>"; 
		});
		
		returnString += "</tbody>";
		$("#" + appendTo).html(returnString);
		listAction();
		uPagination(json['limit']['pages'], currPage);
		
	}).fail(function() {
		console.log( "Posting data failed." );
	});
	
}

function managePages( val ) {
	var range = 15;
	var getCurp = $('#data-pagi li.active a').text();
	var opt = 0;
	
	if(val === 'next') {
		opt = (+getCurp === 0) ? 0 : +getCurp + 1;
	} else if(val === 'prev') {
		opt = (+getCurp === 0) ? 1 : +getCurp - 1;
	} else {
		opt = +val;
	}
	
	var end = range * opt;
	var start = end - range;

	//ajxDispList('view_campaigns', { key: $('#sr-list').val() }, 'camp-list-grid', start, end, opt);
	ajxDispList('fetchStudentList', { start: start, end: end }, 'student-list-grid', start, end, opt);
}

function formAction() {
    
    $(document).ready( function() {
		$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
			console.log(numFiles);
			console.log(label);
			content = "<span class='fa fa-fw fa-check' style='color:#22E45C'></span> &nbsp;" + label;
			$('#sel-file').html(content);
		});
	});

	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	});
    
    $("#upform").submit(function(evt) {	 
		evt.preventDefault();
		var elemInfo = startLoader($('#sub-upl'));
		var elemInfo1 = startLoader($('.btn-file'));
		var elemInfo2 = startLoader($('#upl-users'));

		$('#sub-upl').val('Uploading...');
		var formData = new FormData($(this)[0]);
		
		$.ajax({
			url: route.page + '/uploadAddUsers',
			type: 'post',
			data: formData,
			cache: false,
			contentType: false,
			enctype: 'multipart/form-data',
			processData: false,
			success: function (response) {
				var json = $.parseJSON(response);
                
                if(json['message'][0] === 'success') {
                    infoBox('msg-alrt','Message',json['message'][1],'show-box');
                } else {
                    if(json['failed'] !== undefined)  {
                        infoBox('msg-alrt','Message',json['message'][1] + '<hr/><span class="font-weight-bold text-danger">' + json['failed'][0][1] + '</span><br/>File error in row ' + json['failed'][0][2] + '<br/>Affected: ' + json['failed'][0][0],'show-box');   
                    } else {
                        infoBox('msg-alrt','Message',json['message'][1],'show-box');
                    }
                }
                
                stopLoader(elemInfo2[0], elemInfo2[1]);
			}
		});

	   return false;
	});
}

function confBoxEvents() {

	$('#add-new-user').on('click', function() {
		var vobj = {
			0: { id: "u-name" },
			1: { id: "u-fname" },
			2: { id: "u-lname" },
			3: { id: "u-password" },
			4: { id: "u-password-conf" },
			5: { id: "sel-upriv" }
		};

		vError(vobj);

		var uname = isEmpty($('#u-name').val());
		var fname = isEmpty($('#u-fname').val());
		var mname = isEmpty($('#u-mname').val());
		var lname = isEmpty($('#u-lname').val());
		var email = isEmpty($('#u-email').val());
		var pass = isEmpty($('#u-password').val());
		var pass_conf = isEmpty($('#u-password-conf').val());
		var priv = isEmpty($('#sel-upriv').val());

		if(uname != "" && priv != "" && fname != "" && lname != "" && pass != "" && pass_conf != "") {
            if(pass === pass_conf) {
                var elemInfo = startLoader($(this));

                $.ajax({
                    url: route.page + '/add',
                    type: 'post',
                    data: { 
                        username: uname,
                        first_name: fname,
                        middle_name: mname,
                        last_name: lname,
                        email_address: email,
                        password: pass,
                        confirm_password: pass_conf,
                        privilege: priv
                    },
                    success: function(response) {
                        var json = $.parseJSON(response);
                        alertBox(response, 'msg-alert', '', '', 'json', 'fade');
                        if(json['message'][0] === 'success') {
                            ajxDispList('fetchStudentList', { start: 0, end: 15 }, 'student-list-grid', 0, 15, 1);
                            $('#user-added').modal('hide');
                        }

                        stopLoader(elemInfo[0],elemInfo[1]);
                    }	
                });    
            } else {
                alertBox('', 'msg-box', 'danger', 'Password confirmation doesn\'t match.', '', 'fade');    
            }
		} else {
			alertBox('', 'msg-box', 'danger', 'Please fill out all fields.', '', 'fade');
		}
	});
    
    $('#update-user').unbind().on('click', function() {
         var vobj = {
			0: { id: "u-name" },
			1: { id: "u-fname" },
			2: { id: "u-lname" },
			3: { id: "u-password" },
			4: { id: "u-password-conf" },
			5: { id: "sel-upriv" }
		};

		vError(vobj);

		var id = isEmpty($('#u-id').val());
		var uname = isEmpty($('#u-name').val());
		var fname = isEmpty($('#u-fname').val());
		var mname = isEmpty($('#u-mname').val());
		var lname = isEmpty($('#u-lname').val());
		var email = isEmpty($('#u-email').val());
		var pass = isEmpty($('#u-password').val());
		var pass_conf = isEmpty($('#u-password-conf').val());
		var priv = isEmpty($('#sel-upriv').val());

		if(id != "" && uname != "" && priv != "" && fname != "" && lname != "" && pass != "" && pass_conf != "") {
            if(pass === pass_conf) {
                var elemInfo = startLoader($(this));

                $.ajax({
                    url: route.page + '/update',
                    type: 'post',
                    data: { 
                        id: id,
                        uname: uname,
                        fname: fname,
                        mname: mname,
                        lname: lname,
                        email: email,
                        pass: pass,
                        pass_conf: pass_conf,
                        priv: priv
                    },
                    success: function(response) {
                        var json = $.parseJSON(response);
                        alertBox(response, 'msg-alert', '', '', 'json', 'fade');
                        if(json['message'][0] === 'success') {
                            ajxDispList('fetchStudentList', { start: 0, end: 15 }, 'student-list-grid', 0, 15, 1);
                            $('#user-added').modal('hide');
                        }

                        stopLoader(elemInfo[0],elemInfo[1]);
                    }	
                });    
            } else {
                alertBox('', 'msg-alert', 'danger', 'Password confirmation doesn\'t match.', '', 'fade');    
            }
		} else {
			alertBox('', 'msg-alert', 'danger', 'Please fill out all fields.', '', 'fade');
		}
    });

	$('#course-del').unbind().on('click', function() {
		if(rHandler != "") {
			var elemInfo = startLoader($(this));
			
			$.ajax({
				url: route.page + '/delete',
				type: 'post',
				data: { id: rHandler },
				success: function(response) {
					var json = $.parseJSON(response);
					alertBox(response, 'msg-alert', '', '', 'json', 'fade');
					if(json['message'][0] === 'success') {
						ajxDispList('fetchStudentList', { start: 0, end: 15 }, 'student-list-grid', 0, 15, 1);
						$('#del-course').modal('hide');
					}

					stopLoader(elemInfo[0],elemInfo[1]);
				}
			});
		} else {
			alertBox('', 'msg-box', 'danger', 'Deleting user has error.', '', 'fade');
		}
	});
}


function listAction() {
    $('td button.u-edit').unbind().on('click', function() {
        $.ajax({
            url: route.page + "/edit",
            data: { id: $(this).attr('usnid') },
            success: function(response)  {
                var json = $.parseJSON(response);
                var user = json['user'][0];
                var returnString = "";
                returnString += '<div id="msg-box"></div>';

                returnString += '<div class="row">';
                returnString += '<input type="hidden" id="u-id" value="' + user['id'] + '">';
                returnString += '<div class="col-sm-6 col-md-6">';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>Username <label class="text-danger" style="font-size:16px;">*</label></label>';
                returnString += '<input id="u-name" type="text" class="form-control input-sm" placeholder="Username" value="' + isNull(user['uname']) + '">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<hr/><label><small class="text-warning">Do not edit the password field if you want to use the current password.</small></label>';
                returnString += '<label>Update Password <label class="text-danger" style="font-size:16px;">*</label></label><br/>';
                returnString += '<input id="u-password" type="password" class="form-control input-sm" placeholder="Password" value="default">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>Confirm Update Password <label class="text-danger" style="font-size:16px;">*</label></label>';
                returnString += '<input id="u-password-conf" type="password" class="form-control input-sm" placeholder="Confirm Password" value="default">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>Email Address</label>';
                returnString += '<input id="u-email" type="text" class="form-control input-sm" placeholder="sample@email.com" value="' + isNull(user['email']) + '">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '</div>';

                returnString += '<div class="col-sm-6 col-md-6">';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>First Name <label class="text-danger" style="font-size:16px;">*</label></label>';
                returnString += '<input id="u-fname" type="text" class="form-control input-sm" placeholder="First Name" value="' + isNull(user['fname']) + '">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>Middle Name</label>';
                returnString += '<input id="u-mname" type="text" class="form-control input-sm" placeholder="Middle Name" value="' + isNull(user['mname']) + '">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>Last Name <label class="text-danger" style="font-size:16px;">*</label></label>';
                returnString += '<input id="u-lname" type="text" class="form-control input-sm" placeholder="Last Name" value="' + isNull(user['lname']) + '">';
                returnString += '<span></span>';
                returnString += '</div>';
                returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
                returnString += '<label>Privilege <label class="text-danger" style="font-size:16px;">*</label></label>';
                returnString += '<select id="sel-upriv" class="form-control input-sm" style="margin-bottom:5px;">';
                returnString += '<option selected disabled value=""> -- Select -- </option>';
                //returnString += getPrivilegeList();
                returnString += '</select>';
                returnString += '<span></span>'; 
                returnString += '</div>';
                returnString += '</div>';

                returnString += '</div>';

                confirmBoxLarge('user-added','<span class="fa fa-fw fa-edit"></span> Edit User',returnString,'update-user','Update','warning','show-box');
                fillDropdown(user['priv'], 'sel-upriv', 'getPrivilegeList');
            }
        });    
    });
    
	$('td button.u-del').unbind().on('click', function() {
		rHandler = isEmpty($(this).attr('id'));

		if(rHandler != "") {
			confirmBox('del-course','Remove',"Do you want to remove <span class='text-danger'>" + $(this).attr('cid') + "</span> user from the list?",'course-del','Remove','danger','show-box');
		}
	});
}

function fillActions( setVal, appendTo ) {
	$.ajax({
		url: route.page + '/fillActions',
		type: 'post',
		data: { selected: setVal },
		success: function(response) {
			$('#' + appendTo).html(response);
		}
	});
}

function fillDropdown( setVal, appendTo, page ) {
	$.ajax({
		url: route.page + '/' + page,
        type: 'post',
        data: { selected: setVal },
		success: function(response) {
			$('#' + appendTo).html(response);
		}
	});
}

/* On click */
$('#new-user').on('click', function() {

	var returnString = "";
				
	returnString += '<div id="msg-box"></div>';
	
    returnString += '<div class="row">';
    
    returnString += '<div class="col-sm-6 col-md-6">';
	returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Username <label class="text-danger" style="font-size:16px;">*</label></label>';
	returnString += '<input id="u-name" type="text" class="form-control input-sm" placeholder="Username">';
	returnString += '<span></span>';
	returnString += '</div>';
    returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Password <label class="text-danger" style="font-size:16px;">*</label></label>';
	returnString += '<input id="u-password" type="password" class="form-control input-sm" placeholder="Password">';
	returnString += '<span></span>';
	returnString += '</div>';
    returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Confirm Password <label class="text-danger" style="font-size:16px;">*</label></label>';
	returnString += '<input id="u-password-conf" type="password" class="form-control input-sm" placeholder="Confirm Password">';
	returnString += '<span></span>';
	returnString += '</div>';
    returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Email Address</label>';
	returnString += '<input id="u-email" type="text" class="form-control input-sm" placeholder="sample@email.com">';
	returnString += '<span></span>';
	returnString += '</div>';
	returnString += '</div>';

    returnString += '<div class="col-sm-6 col-md-6">';
    returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>First Name <label class="text-danger" style="font-size:16px;">*</label></label>';
	returnString += '<input id="u-fname" type="text" class="form-control input-sm" placeholder="First Name">';
	returnString += '<span></span>';
	returnString += '</div>';
    returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Middle Name</label>';
	returnString += '<input id="u-mname" type="text" class="form-control input-sm" placeholder="Middle Name">';
	returnString += '<span></span>';
	returnString += '</div>';
    returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Last Name <label class="text-danger" style="font-size:16px;">*</label></label>';
	returnString += '<input id="u-lname" type="text" class="form-control input-sm" placeholder="Last Name">';
	returnString += '<span></span>';
	returnString += '</div>';
	returnString += '<div class="form-group" style="margin-bottom:5px; font-size:12px;">';
	returnString += '<label>Privilege <label class="text-danger" style="font-size:16px;">*</label></label>';
	returnString += '<select id="sel-upriv" class="form-control input-sm" style="margin-bottom:5px;">';
	returnString += '<option selected disabled value=""> -- Select -- </option>';
	//returnString += fillDropdown('', 'sel-upriv', 'getPrivilegeList');
	returnString += '</select>';
	returnString += '<span></span>'; 
	returnString += '</div>';
	returnString += '</div>';
    
    returnString += '</div>';

	confirmBoxLarge('user-added','<span class="fa fa-fw fa-user-plus"></span> Add New User',returnString,'add-new-user','Add','primary','show-box');
    fillDropdown('', 'sel-upriv', 'getPrivilegeList');
});

$('#upl-users').on('click', function() {
    var content = '<form action="#" method="post" id="upform">';
    
    content += '<div class="form-group" style="margin-bottom:5px;">';
    content += '<label class="font-weight-bold">Upload File (Excel)</label>';
    content += '<span class="btn btn-primary raised btn-file" style="width:100%;">';
    content += '<span class="fa fa-fw fa-file-excel-o"></span> &nbsp;Browse file  <input type="file" name="file" id="file"></span><hr/>';
    content += '<span id="sel-file" style="margin:0 5px 0 0px; font-size:14px;"> &nbsp;No file selected ..</span><hr/>';
    content += '<input type="submit" class="btn btn-secondary raised" id="sub-upl" value="Upload" style="width:100%;">';
    content += '</div>';

    content += "</form>";

    confirmBox('up-user-form','Create Multiple User', content, 'sel-list','close','','show-box');
    formAction();
});

$('#sub-sr').on('click', function() {
	var search = isNull($('#sr-user').val());
	ajxDispList('fetchStudentList', { key: search, start: 0, end: 15 }, 'student-list-grid', 0, 15, 1);
});

$('#sr-user').keypress(function(e) { 
	if(e.which == 13) {
		var search = isNull($(this).val());
		ajxDispList('fetchStudentList', { key: search, start: 0, end: 15 }, 'student-list-grid', 0, 15, 1);
	}
});

/* On change */

/* On load */
ajxDispList('fetchStudentList', { start: 0, end: 15 }, 'student-list-grid', 0, 15, 1);