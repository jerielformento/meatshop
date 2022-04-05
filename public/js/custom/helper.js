var page = "";
var data = "";
var header = "";
var validatorErrorList = {
	Empty: "Field is required."
};
	
function startLoader( elem ) {
	var html = $(elem).html();
	var id = $(elem).attr('id');

	$(elem).attr('disabled','disabled');

	var res = [id, html];
	return res;
}

function stopLoader( elemId, html ) {
	$('#' + elemId).removeAttr('disabled');
}

function ajxLoader() {
	return "<thead><tr><td colspan='8' style='text-align:center'><div class='gty-ajxldr'></div></td></tr></thead>";
}

function ajxLoaderPanel() {
	return "<div class='gty-ajxldr'></div>";
}	

function sortingDT( id, cols, sortCol ) {
	if($.fn.dataTable.isDataTable(id)) {
		$('#' + id).DataTable({
			bPaginate: false,
			bFilter: false,
			bLengthChange: false,
			bInfo: false,
			bAutoWidth: false,
			columns: cols,
			orders: [[sortCol,"asc"]]
		});
	} else {
		$('#' + id).DataTable({
			bPaginate: false,
			bFilter: false,
			bLengthChange: false,
			bInfo: false,
			bAutoWidth: false,
			destroy: true,
			columns: cols,
			order: [[sortCol,"asc"]]
		});
	}
	
} 

function uPagination( limitPage, currentPage ) {
	var returnString = "";
	var limitView = 1;
    
	if(limitPage !== 0) {
		
		returnString += '<ul class="pagination">';
		returnString += (currentPage === 1) ? '<li class="paginate_button page-item disabled"><a class="page-link" href="#" aria-label="Previous">' : '<li class="paginate_button page-item"><a class="page-link" href="#" aria-label="Previous" onclick="managePages(' + 1 + ')">';
		returnString += '<span aria-hidden="true">&larr;</span>';
		returnString += '</a>';
		returnString += '</li>';
		returnString += (currentPage === 1) ? '<li class="paginate_button page-item disabled"><a class="page-link" href="#" aria-label="Previous">' : '<li class="paginate_button page-item"><a class="page-link" href="#" aria-label="Previous" onclick="managePages(\'prev\')">';
		returnString += '<span aria-hidden="true">&laquo;</span>';
		returnString += '</a>';
		returnString += '</li>';		
        
        for(i = currentPage; i <= limitPage; i++) {
            if(limitView > 5) {
                break;
            } else {
                returnString += '<li class="paginate_button page-item ' + ((i == currentPage) ? 'active' : '') + '"><a href="#" class="page-link" onclick="managePages(\'' + i + '\')">' + i + '</a></li>';
            }
            
            limitView++;
        }
        
        returnString += (limitPage === currentPage) ? '<li class="paginate_button page-item disabled"><a class="page-link" href="#" aria-label="Next">' : '<li class="paginate_button page-item"><a class="page-link" href="#" aria-label="Next" onclick="managePages(\'next\')">';
        returnString += '<span aria-hidden="true">&raquo;</span>';
        returnString += '</a>';
        returnString += '</li>';  
		
		returnString += (limitPage === currentPage) ? '<li class="paginate_button page-item disabled"><a class="page-link" href="#" aria-label="Next">' : '<li class="paginate_button page-item"><a class="page-link" href="#" aria-label="Next" onclick="managePages(' + limitPage + ')">';
		returnString += '<span aria-hidden="true">&rarr;</span>';
		returnString += '</a>';
		returnString += '</li>';
		returnString += '</ul>';
	}
	
	$('#pagi-num-rows').html('<span class="badge badge-secondary pull-right" style="font-size:11px; margin-right:10px;">Page ' + currentPage + ' of ' + limitPage + '</span>');
	$('#data-pagi').html(returnString);
}

function uPaginationPause( limitPage, currentPage ) {
	var returnString = "";
	
	if(limitPage !== 0) {
		returnString += '<ul class="pagination pagination-sm">';
		returnString += (currentPage === 1) ? '<li class="disabled"><a href="#" aria-label="Previous">' : '<li><a href="#" aria-label="Previous" onclick="managePagesPause(' + 1 + ')">';
		returnString += '<span aria-hidden="true">&larr;</span>';
		returnString += '</a>';
		returnString += '</li>';
		returnString += (currentPage === 1) ? '<li class="disabled"><a href="#" aria-label="Previous">' : '<li><a href="#" aria-label="Previous" onclick="managePagesPause(\'prev\')">';
		returnString += '<span aria-hidden="true">&laquo;</span>';
		returnString += '</a>';
		returnString += '</li>';		
		returnString += '<li class="active"><a href="#">' + currentPage + '</a></li>';
		returnString += (limitPage === currentPage) ? '<li class="disabled"><a href="#" aria-label="Next">' : '<li><a href="#" aria-label="Next" onclick="managePagesPause(\'next\')">';
		returnString += '<span aria-hidden="true">&raquo;</span>';
		returnString += '</a>';
		returnString += '</li>';
		returnString += (limitPage === currentPage) ? '<li class="disabled"><a href="#" aria-label="Next">' : '<li><a href="#" aria-label="Next" onclick="managePagesPause(' + limitPage + ')">';
		returnString += '<span aria-hidden="true">&rarr;</span>';
		returnString += '</a>';
		returnString += '</li>';
		returnString += '</ul>';
	}
	
	$('#pagi-num-rows-p').html('<span class="badge badge-secondary pull-right" style="font-size:11px; margin-right:10px;">Page ' + currentPage + ' of ' + limitPage + '</span>');
	$('#data-pagi-pause').html(returnString);
}
	
function uAjx( route, page, data, appendTo, dispType ) {
	$('#' + appendTo).html("<option disabled selected>Loading ..</option>");
	
	$.ajax({
		url: route + '/' + page,
		type: 'post',
		data: data,
		success: function(response) {
			if(dispType === 1) { 
				$("#" + appendTo).html(response);
			} else {
				$("#" + appendTo).append(response);
			}
		}
	});
}
var alertNum = 1;
	
function alertBox( arrData, appendTo, aClass, aString, returnType, animate ) {
	
	var returnString = "";
	if(returnType === "json") {
		var json = $.parseJSON(arrData);
		
		returnString += '<div class="alert alert-' + json['message'][0] + ' alert-dismissible" role="alert" id="' + alertNum + '">';
		returnString += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		
		if(json['message'][0] == "success") {
			returnString += '<span class="fa fa-fw fa-check fa-lg"></span> &nbsp;' + json['message'][1];
		} else {
			returnString += '<span class="fa fa-fw fa-remove fa-lg"></span> &nbsp;' + json['message'][1];
		}
		
		returnString += '</div>';
	} else {
		
		returnString += '<div class="alert alert-' + aClass + ' alert-dismissible" role="alert" id="' + alertNum + '">';
		returnString += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		
		if(aClass == "success") {
			returnString += '<span class="fa fa-fw fa-check"></span> &nbsp;' + aString;
		} else if(aClass == "danger") {
			returnString += '<span class="fa fa-fw fa-remove"></span> &nbsp;' + aString;
		} else if(aClass == "warning") {
			returnString += '<span class="fa fa-fw fa-ban"></span> &nbsp;' + aString;
		}
		
		returnString += '</div>';
	}
	
	$('#' + appendTo).append(returnString);
	if(animate === "fade") {
		var storeAlert = alertNum;
		$('#' + storeAlert).animate({
			opacity: 0,
		}, 10000, function() {
			$('#' + storeAlert).remove();
		});
	}
	$("html, body").animate({
		scrollTop: 0
	}, 200);
	
	alertNum += 1;
	
}

function confirmBox( boxId, boxTitle, boxMsg, bId, bName, bClass, appendTo ) {
	returnString = "";
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body">' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised" data-dismiss="modal">Close</button>';
	returnString += (bName === "close") ? "" : '<button id="' + bId + '" type="button" class="btn btn-' + bClass + ' raised">' + bName + '</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
	confBoxEvents();
}

function customBox( boxId, boxTitle, boxMsg, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body">' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised" data-dismiss="modal">Close</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
	confBoxEvents();
}

function confirmBoxLarge( boxId, boxTitle, boxMsg, bId, bName, bClass, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-lg">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body">' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised" data-dismiss="modal">Close</button>';
	returnString += (bName === "close") ? "" : '<button id="' + bId + '" type="button" class="btn btn-' + bClass + ' raised">' + bName + '</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
	confBoxEvents();
}

function infoBox( boxId, boxTitle, boxMsg, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body"><span class="fa fa-fw fa-info-circle" style="color:#5CB7E8;"></span> ' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised mc" data-dismiss="modal">Close</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
        backdrop: 'static',
		keyboard: false
	});
    
    $('button.mc').on('click', function() {
         $('.modal-backdrop').remove();
    });
	confBoxEvents();
}

function errorBox( boxId, boxTitle, boxMsg, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body"><span class="fa fa-fw fa-times-circle" style="color:#e56962;"></span> ' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised" data-dismiss="modal">Close</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
    
    $('button.mc').on('click', function() {
         $('.modal-backdrop').remove();
    });
	confBoxEvents();
}

function successBox( boxId, boxTitle, boxMsg, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body"><span class="fa fa-fw fa-check-circle" style="color:#5cb85c;"></span> ' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised" data-dismiss="modal">Close</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
    
    $('button.mc').on('click', function() {
         $('.modal-backdrop').remove();
    });
	confBoxEvents();
}

function warningBox( boxId, boxTitle, boxMsg, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body"><span class="fa fa-fw fa-remove" style="color:#dd3030;"></span> ' + boxMsg + '</div>';	
	returnString += '<div class="modal-footer">';	
	returnString += '<button type="button" class="btn btn-secondary raised" data-dismiss="modal">Close</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
    
    $('button.mc').on('click', function() {
         $('.modal-backdrop').remove();
    });
	confBoxEvents();
}

function boxLoader( boxId, boxTitle, appendTo ) {
	returnString = "";
	
	returnString += '<div id="' + boxId + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'
	returnString += '<div class="modal-dialog modal-sm">';
	returnString += '<div class="modal-content">';
	returnString += '<div class="modal-header">'
	//returnString += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	returnString += '<h5 class="modal-title"><strong>' + boxTitle + '</strong></h5>';
	returnString += '</div>';
	returnString += '<div class="modal-body" id="modal-body"><div style="text-align:center;">Please wait ... <div class=\'gty-ajxldr\'></div></div></div>';	
	returnString += '<div class="modal-footer">';	
	//returnString += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
	//returnString += (bName === "close") ? "" : '<button id="' + bId + '" type="button" class="btn btn-' + bClass + '">' + bName + '</button>';
	returnString += '</div>';
	returnString += '</div>';
	returnString += '</div>';

	$('#' + appendTo).html(returnString);
	$('#' + boxId).modal({
		backdrop: 'static',
		keyboard: false
	});
    
    $('button.mc').on('click', function() {
         $('.modal-backdrop').remove();
    });
	confBoxEvents();
}


function isNull( val ) {
	return (val === null || val === "" || val === undefined) ? '' : val;
}

function isEmpty( val ) {
	return (val === null || val === "") ? '' : val;
}

function isNA( val ) {
	return (val === "") ? 'N/A' : val;
}

function rmSpace( val ) {
	trimVal = val.replace(/^\s+/, '').replace(/\s+$/, '');
	return trimVal;
}

function maxLength( string, limit ) {
	return ( string.length <= limit ) ? string : '';
}

function vError( elemID ) {

	for(var elem in elemID) {
		var vld = elemID[elem];
		console.log(vld.id);
		var vnull = isNull($('#' + vld.id).val());
		var vempty = isEmpty($('#' + vld.id).val());

		//console.log("vnull:" + vnull + "|vempty:" + vempty);
		if(vnull == "" && vempty == "" ) {
			$('#' + vld.id).parent().find('input, textarea, select').addClass('is-invalid');
			//$('#' + vld.id).parent().find('span').addClass('control-label err-span').attr('for', vld.id).html(validatorErrorList.Empty);

			/*$('#' + vld.id).on('focus', function() {
				$(this).parent().removeClass('has-error');
				$(this).parent().find('span').removeClass('err-span').addClass('inf-span');
			}).blur(function() {
				$(this).parent().addClass('has-error');
				$(this).parent().find('span').removeClass('inf-span').addClass('err-span');
			});*/
		} else {
			$('#' + vld.id).parent().find('input, textarea, select').removeClass('is-invalid');
			//$('#' + vld.id).parent().find('span').removeClass('control-label err-span').html('');

			/*$('#' + vld.id).on('focus', function() {
				$(this).parent().removeClass('has-error');
				$(this).parent().find('span').removeClass('inf-span').html('');
			}).blur(function() {
				$(this).parent().removeClass('has-error');
				$(this).parent().find('span').removeClass('err-span').html('');
			});*/
		}
	}
}

function createDatePicker( id ) {
	$('#' + id).datepicker({
		changeMonth: true,
		changeYear: true,
		inline: true,  
		showOtherMonths: true,  
		dateFormat: 'yy-mm-dd',
		dayNamesMin: ['&nbsp;Sun', '&nbsp;Mon', '&nbsp;Tue', '&nbsp;Wed', '&nbsp;Thu', '&nbsp;Fri', '&nbsp;Sat'],  
	});
}

function dropdownTimeMaker(time, date, title) {
	var returnString = (time == '') ? "<option disabled selected>-- " + title + " --</option>" : "<option disabled>-- " + title + " --</option>";
	var createTime = "";
	
	for(var i = 0; i <= 23; i++) {
		for(var j = 0; j <= 45; j+=15) {
			if(i <= 9) {
				if(j == 0) {
					createTime = "0" + i + ":" + j + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				} else {
					createTime = "0" + i + ":" + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				}
			} else {
				if(j == 0) {
					createTime = i + ":" + j + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				} else {
					createTime = i + ":" + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				}
			}
		}
	}

	return returnString;
}

function dropdownTimeMakerInterval(time, date, title, increment, limit) {
	var returnString = (time == '') ? "<option disabled selected>-- " + title + " --</option>" : "<option disabled>-- " + title + " --</option>";
	var createTime = "";
	
	for(var i = 0; i <= 23; i++) {
		for(var j = 0; j <= limit; j+=increment) {
			if(i <= 9) {
				if(j == 0) {
					createTime = "0" + i + ":" + j + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				} else {
					createTime = "0" + i + ":" + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				}
			} else {
				if(j == 0) {
					createTime = i + ":" + j + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				} else {
					createTime = i + ":" + j + ":00";
					if(createTime == time) {
						returnString += "<option value=\"" + createTime +  "\" selected>" + convertTime(date + " " + createTime) + "</option>";
					} else {
						returnString += "<option value=\"" + createTime +  "\">" + convertTime(date + " " + createTime) + "</option>";
					}
				}
			}
		}
	}

	return returnString;
}

function convertTime( arg ) {
	var dateStr = arg;
	var returnFormat = "";
	var conv = "";
	var dateSplit = "";
	var timeSplit = "";
	var date = "";
	
	if(arg === "N/A") {
		returnFormat = arg;
	} else {
		conv = dateStr.split(" ");
		dateSplit = conv[0].split("-");
		timeSplit = conv[1].split(":");
		date = new Date(dateSplit[0], (dateSplit[1]-1), dateSplit[2], timeSplit[0], timeSplit[1], timeSplit[2]);
		returnFormat = date.toLocaleTimeString();
	}
	/*
	if(arg === "N/A") {
		returnFormat = arg;
	} else {
		console.log(arg);
		var convDate = new Date(arg);
		var returnFormat = convDate.toLocaleTimeString();
	}
	*/
	return returnFormat;
}

function htmlDecode( input ) {
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

/* On load */

function loadFilters() {
	$('#drop-camp').html('<option disabled selected> Loading ..</option>');
	$('#drop-team').html('<option disabled selected> Loading ..</option>');
	
	$.ajax({
		url: 'ajax/fill_campaign.php',
		success: function(response) {
			$('#drop-camp').html(response);
			$.ajax({
				url: 'ajax/fill_team.php',
				success: function(response) {
					$('#drop-team').html(response);
				}
			});
		}
	});
}
