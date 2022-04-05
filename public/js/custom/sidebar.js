/* side nav actions */
var sideMenu = "";
var scrolled = false;
	
function getSideBar() {
    var base_url = window.location.origin;
	var get_url = window.location['pathname'];
	url = get_url.replace("/","");
    //console.log(base_url);

	$.ajax({
		url: base_url + '/index/getUsersNav',
		type: 'post',
		data: { url: url },
		success: function(response) {
			var json = $.parseJSON(response);
			sideMenu = json;
			var returnString = "";
			var pid = $('#page-top').attr('pid');
			
			returnString = '<a class="sidebar-brand d-flex align-items-center justify-content-center header-bg" href="/"><div class="sidebar-brand-icon m-1"><i class="fas fa-dumpster-fire fa-lg text-danger"></i></div><div class="sidebar-brand-text mx-1 text-gray-200">JL Frozen<!--<sup class="text-gray-200">sys</sup>--></div></a>';
			
      
			if(json['sbstatus'] == 1) {
				
				sideMenu['sidebar'].forEach(function(menu) {
					if(menu['child'] == 1) {
						// create child menu
						
						var check_active_sub = 0;
						var returnStringSub = "";
						menu['submenu'].forEach(function(child) {
							if(pid === child[0]) {
								returnStringSub += '<a class="collapse-item active" href="' + child[0] + '">' + child[1] + '</a>';	
								check_active_sub = 1;
							} else {
								returnStringSub += '<a class="collapse-item" href="' + child[0] + '">' + child[1] + '</a>';	
							}
						});
						
						if(check_active_sub == 1) {
							returnString += '<li class="nav-item active">';
							returnString += '<a class="nav-link text-gray-200" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">';
							returnString += '<i class="' + menu['class'] + ' fa-2x"></i>';
							returnString += '<span>' + menu['name'] + '</span></a>';
							returnString += '<div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#side-bar-menu">';
							returnString += '<div class="bg-white py-2 collapse-inner rounded">';
							returnString += '<h6 class="collapse-header">' + menu['name'] + ':</h6>';	
						} else {
							returnString += '<li class="nav-item">';
							returnString += '<a class="nav-link text-gray-200 collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">';
							returnString += '<i class="' + menu['class'] + '"></i>';
							returnString += '<span>' + menu['name'] + '</span></a>';
							returnString += '<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#side-bar-menu">';
							returnString += '<div class="bg-white py-2 collapse-inner rounded">';
							returnString += '<h6 class="collapse-header">' + menu['name'] + ':</h6>';	
						}
						
						returnString += returnStringSub;
						
						returnString += '</div></div></li><hr class="sidebar-divider">';
					} else {
						returnString += '<li class="nav-item text-gray-200 ' + ((menu['url'] == pid) ? ' active' : '') + '"><a class="nav-link" href="' + menu['url'] + '"><i class="text-gray-200 ' + menu['class'] + '"></i><span> ' + menu['name'] + '</span></a></li><hr class="sidebar-divider">';	
					}
				});
			}
			
			$('#side-bar-menu').prepend(returnString);

			if(json['sbstatus'] == 0) {
				$('#menu-container li a').css({'color':'transparent'});					
			}
			
			$('#hide-side-nav').on('click', function() {
				var control = $(this).attr('nav-ctr');
				actionSideBar(control);
			});
			
			
			$('#side-bar-menu li a').on('mouseenter', function() {
				$(this).find('span').animate({color: '#EEEEEE'}, "fast");
			}).on('mouseleave', function() {
				$(this).find('span').animate({color: '#EEEEEE'}, "fast");
			});
		}
	});
}
  
function actionSideBar( action ) {
	var  returnString = "";
	var nav = $('#menu-container');
	var body = $('#cont-container');
	var pid = $('#curr-page').attr('pid');
	
	if(action == "hide") {		
		sideBarStatus(0);	
		
		$(nav).addClass('hide-nav-ctr');
		$(nav).attr('nav-ctr','show');
		$(nav).animate({ width: '4.5%' }, 150);
		$('#menu-container li a').css('color','#23272D');
		returnString = '<li id="hide-side-nav" nav-ctr="show"><a href="#" title="Show"><span class="fa fa-fw fa-bars fa-lg"></span></a></li>';
		sideMenu['sidebar'].forEach(function(menu) {
			returnString += '<li ' + ((menu['url'] == pid) ? 'class="active"' : '') + '><a href="' + menu['url'] + '" title="' + menu['name'] + '" m-name="' + menu['name'] + '" sp-class="' + menu['class'] + '" ' + ((menu['url'] == pid) ? 'class="active"' : '') + '><span class="' + menu['class'] + '"></span> &nbsp;&nbsp;' + menu['name'] + ' </a></li>';
		});
		
		$(body)
				.removeClass('col-sm-10').addClass('col-sm-12')
				.removeClass('col-md-10').addClass('col-md-12')
				.removeClass('col-sm-offset-2').addClass('col-sm-offset-0')
				.removeClass('col-md-offset-2').addClass('col-md-offset-0')
				.addClass('align-body');

	} else if(action == "show") {
		sideBarStatus(1);
		$(nav).removeClass('hide-nav-ctr');
		$(nav).attr('nav-ctr','hide');
		$(nav).animate({ width: '16.6%' }, 150);
		returnString = '<li id="hide-side-nav" nav-ctr="hide"><a href="#"><span class="fa fa-fw fa-bars fa-lg"></span></a></li>';
		sideMenu['sidebar'].forEach(function(menu) {
			returnString += '<li ' + ((menu['url'] == pid) ? 'class="active"' : '') + '><a href="' + menu['url'] + '" title="' + menu['name'] + '" m-name="' + menu['name'] + '" sp-class="' + menu['class'] + '" ' + ((menu['url'] == pid) ? 'class="active"' : '') + '><span class="' + menu['class'] + '"></span> &nbsp;&nbsp;' + menu['name'] + ' </a></li>';
		});
		
		$(body)
				.removeClass('col-sm-12').addClass('col-sm-10')
				.removeClass('col-md-12').addClass('col-md-10')
				.removeClass('col-sm-offset-0').addClass('col-sm-offset-2')
				.removeClass('col-md-offset-0').addClass('col-md-offset-2')
				.removeClass('align-body');
				
	}	
	
	$('#side-bar-menu').html(returnString);

	
	if(action == "hide") {
		$('#menu-container li a').css({'color':'transparent'});			
	} else {
		$('#menu-container li a:not(.active)').css({'color':'#505050'});	
    }
	
	$('#hide-side-nav').on('click', function() {
		var control = $(this).attr('nav-ctr');
		actionSideBar(control);
	});
		
	
	$('#side-bar-menu li a').on('mouseenter', function(){
		$(this).find('span').animate({color: '#EEEEEE'}, "fast");
	}).on('mouseleave', function() {
		$(this).find('span').animate({color: '#EEEEEE'}, "fast");
	});
}

function sideBarStatus( val ) {
	$.ajax({
		url: 'index/sidebar',
		type: 'post',
		data: {
			action: val
		},
		success: function(response) {}
	});
}

function scrollUp() {
	$(window).on("scroll", function () {
		if($('.main').hasClass('col-sm-offset-2')) {
			if ($(this).scrollTop() > 30 && !scrolled) {
				$(".page-sign").animate({
					left: '250px', 
					borderRadius: '3px'
				}, "fast");

				$('.page-sign h5').append('<span class="glyphicon glyphicon-arrow-up pg-up" style="margin-left:5px;"></span>');
				scrolled = true;
			}

			if ($(this).scrollTop() < 30 && scrolled) {
				$(".page-sign").animate({
					left: '278px', 
					borderRadius: '0'
				}, "fast");

				scrolled = false;
				$('.pg-up').remove();
			}
		} else {
			if ($(this).scrollTop() > 30 && !scrolled) {
				$(".page-sign").animate({
					left: '76px', 
					borderRadius: '3px'
				}, "fast");

				$('.page-sign h5').append('<span class="glyphicon glyphicon glyphicon-arrow-up pg-up" style="margin-left:5px;"></span>');
				scrolled = true;
			}

			if ($(this).scrollTop() < 30 && scrolled) {
				$(".page-sign").animate({
					left: '100px', 
					borderRadius: '0'
				}, "fast");
				scrolled = false;
				$('.pg-up').remove();
			}
		}
	});
}

getSideBar();