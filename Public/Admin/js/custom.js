$(document).ready(function() {
	"use strict";
	// ------ DO NOT CHANGE ------- //
	$(".flot-bar,.flot-pie,.flot,.flot-multi").bind("plothover", function (event, pos, item) {
		if (item) {
			var y;
			if(event.currentTarget.className === 'flot flot-bar'){
				y = Math.round(item.datapoint[1]);
			} else if(event.currentTarget.className === 'flot flot-pie') {
				y = Math.round(item.datapoint[0])+"%";
			} else if(event.currentTarget.className === 'flot flot-line'){
				y = (Math.round(item.datapoint[1] * 1000)/1000);
			} else {
				y = (Math.round(item.datapoint[1]*1000)/1000)+"€";
			}
			$("#tooltip").remove();
			showTooltip(pos.pageX, pos.pageY,"Value = "+y);
		}
		else {
			$("#tooltip").remove();         
		}
	});

	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
			top: y + 5,
			left: x + 10
		}).appendTo("body").show();
	}

	$(".animateRow").click(function(e){
		e.preventDefault();
		var el = $(this).parents('tr');
		var target = $($(this).data('target'));
		var defaultColor = target.find('a.dropdown-toggle').css('color');
		var titleindex = parseInt($(this).data('title'), 10)-1;
		var userindex = parseInt($(this).data('user'), 10)-1;
		var dateindex = parseInt($(this).data('date'), 10)-1;
		var title = el.find('td:eq('+titleindex+')').html();
		var user = el.find('td:eq('+userindex+') a').html();
		var userContent = el.find('td:eq('+userindex+') a').data('content');
		var date = el.find('td:eq('+dateindex+')').html();
		el.css({
			position:'absolute',
			left:el.position().left,
			top:el.position().top
		});
		el.animate({
			left:target.position().left,
			top:target.position().top,
			width:target.width(),
			height:target.height()
		}, 1000, function(){
			el.hide();
			var value = parseInt(target.find('a.dropdown-toggle .label').html(), 10);
			if(isNaN(value)){
				value = 0;
			}
			target.find('a.dropdown-toggle .label').html(value+1);
			if(target.find('.label').is(":hidden")){
				target.find('.label').show();
			}
			target.find('a.dropdown-toggle').stop().animate({
				backgroundColor:target.find('a.dropdown-toggle .label').css('backgroundColor'),
				color:'#fff'
			},300, function(){
				target.find('a.dropdown-toggle').animate({
				backgroundColor:target.css('background-color'),
				color:defaultColor
			}, 200, function(){
				target.find('a.dropdown-toggle').css('background-color', '').css('color', '');
			});
			});
		});
		target.find('.dropdown-menu').append('<li class="custom"><div class="title">'+title+'<span>'+date+' by <a href="#" class="pover" data-title="'+user+'" data-content="'+userContent+'">'+user+'</a></span></div><div class="action"><div class="btn-group"><a href="#" class="tip btn btn-mini" title="Show order"><img src="images/icons/fugue/magnifier.png" alt=""></a><a href="#" class="tip btn btn-mini" title="Delete order"><img src="images/icons/fugue/cross.png" alt=""></a></div></div></li>');
		$(".pover").popover();$(".tip").tooltip();
	});


	$('.main-nav > li.active > a').click(function(e){
		if($(window).width() <= 767){
			e.preventDefault();
			if($(this).hasClass('open') && (!$(this).hasClass('toggle-collapsed'))){
				$(this).removeClass('open');
				$(this).parents('.main-nav').find('li').each(function(){
					$(this).find('.collapsed-nav').addClass('closed');
					$(this).hide();
				});
				$(this).parent().show();
				$(this).parent().removeClass('open');
			} else {
				if($(this).hasClass('toggle-collapsed')){
					$(this).parent().addClass('open');
				}
				if($(this).hasClass("open")){
					$(this).parents('.main-nav').find('li').not('.active').each(function(){
						$(this).find('.collapsed-nav').addClass('closed');
						$(this).hide();
					});
					$(this).removeClass("open");
				} else {
					$(this).addClass('open');
					$(this).parents('.main-nav').find('li').show();
				}
			}
		}
	});

	$('.toggle-collapsed').each(function(){
		if($(this).hasClass('on-hover')){
			$(this).click(function(e){e.preventDefault();});
			$(this).parent().hover(function(){
				$(this).addClass("open");
				$(this).find('.collapsed-nav').slideDown();
				$(this).find('img').attr("src", '/Public/Admin/images/toggle-subnav-up-white.png');
			}, function(){
				$(this).removeClass("open");
				$(this).find('.collapsed-nav').slideUp();
				$(this).find('img').attr("src", '/Public/Admin/images/toggle-subnav-down.png');
			});
		} else {
			$(this).click(function(e){
				e.preventDefault();
				if($(this).parent().find('.collapsed-nav').is(":visible")){
					$(this).parent().removeClass("open");
					$(this).parent().find('.collapsed-nav').slideUp();
					$(this).find('img').attr("src", '/Public/Admin/images/toggle-subnav-down.png');
				} else {
					$(this).parent().addClass("open");
					$(this).parent().find('.collapsed-nav').slideDown();
					$(this).find('img').attr("src", '/Public/Admin/images/toggle-subnav-up-white.png');
				}
			});
		}
	});

	$('.collapsed-nav li a').hover(function(){
		if(!$(this).parent().hasClass('active')){
			$(this).stop().animate({
				marginLeft: '5px'
			}, 300);
		}
	}, function(){
	$(this).stop().animate({
			marginLeft: '0'
		}, 100);
	});
	$('a.preview').on('mouseover mouseout mousemove click',function(e){
			if(e.type === 'mouseover'){
				$('body').append('<div id="image_preview"><img src="'+$(this).attr('href')+'" width="150"></div>');
				$("#image_preview").fadeIn();
			} else if(e.type === 'mouseout') {
				$("#image_preview").remove();
			} else if(e.type === 'mousemove'){
				$("#image_preview").css({
					top:e.pageY+10+"px",
					left:e.pageX+10+"px"
				});
			} else if(e.type === 'click'){
				$("#image_preview").remove();
			}
		});

	// ------ END DO NOT CHANGE ------- //

	// ------ PLUGINS ------- //
	// - CALENDAR
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	if($('.calendar').length > 0){
		$('.calendar').fullCalendar({
			header: {
				left: 'prev,next,today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
            selectable: true,
			selectHelper: true,
			select: function(start, end) {
				var title = prompt('Event Title:');
				var eventData;
				if (title) {
					eventData = {
						title: title,
						start: start,
						end: end
					};
					$('.calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
				}
				$('.calendar').fullCalendar('unselect');
			},
            lang: 'zh-cn',
			events: [
			{
				title: 'All Day Event',
				start: new Date(y, m, 1)
			},
			{
				title: 'Long Event',
				start: new Date(y, m, d-5),
				end: new Date(y, m, d-2)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d-3, 16, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+4, 16, 0),
				allDay: false
			},
			{
				title: 'Meeting',
				start: new Date(y, m, d, 10, 30),
				allDay: false
			},
			{
				title: 'Lunch',
				start: new Date(y, m, d, 12, 0),
				end: new Date(y, m, d, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false
			},
			{
				title: 'Click for Google',
				start: new Date(y, m, 28),
				end: new Date(y, m, 29),
				url: '../../google.com/default.htm'
			}
			]
		});
	}

	// - tooltips
	$(".tip").tooltip();

	// - fancybox
	if($('.fancy').length > 0){
		$('.fancy').on('mouseenter',function(){
			$('.fancy').fancybox();
		});
	}

	// - quickstats
	if($('.small-chart').length > 0){
		$('.small-chart').each(function(){
			var color = "#" + $(this).data('color');
			var stroke = "#" + $(this).data('stroke');
			var type = $(this).data('type');
			$(this).peity(type, {
				colour: color,
				colours: ['#dddddd', color],
				diameter: 32,
				strokeColour: stroke,
				width: 95,
				height:52
			});
		});
	}
});
