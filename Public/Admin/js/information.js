$(document).ready(function() {
	if($('.flot').length > 0){
	  	var sin = [], cos = [], tmp = [];
		for (var i = 0; i < 16; i += 0.5) {
			sin.push([i, Math.sin(i)]);
			cos.push([i, Math.cos(i)]);
		}

		var options = {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			yaxis: { min: -1.1, max: 1.1 },
			colors: [ '#2872bd', '#666666', '#feb900', '#128902', '#c6c12f']
		};
		var options2 = {
			series: {
				pie: { 
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 1,
						formatter: function(label, series){
							return '<div style="font-size:12px;text-align:center;padding:2px;color:white;font-weight:bold">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
						},
						background: { opacity: 0.8 }
					}
				}
			},
			legend:{
				show:false
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			colors: [ '#2872bd', '#666666', '#feb900', '#128902', '#c6c12f']
		};

		if($('.flot.flot-line').length > 0){
			$.plot($(".flot.flot-line"), [ {label: "Active guests", data: sin}, {label: "Active members", data: cos} ] , options);
		}

		if($(".flot-pie").length > 0){
			$.plot($(".flot-pie"), [ {label: "Active guests", data: 5}, {label: "Active members", data: 10},{label: "Label #3", data: 3},{label: "Label #4", data: 7} ] , options2);
		}
		var d1 = [];
		for (var i = 0; i <= 10; i += 1)
			d1.push([i, parseInt(Math.random() * 30)]);

		var d2 = [];
		for (var i = 0; i <= 10; i += 1)
			d2.push([i, parseInt(Math.random() * 30)]);

		var d3 = [];
		for (var i = 0; i <= 10; i += 1)
			d3.push([i, parseInt(Math.random() * 30)]);

		var ds = new Array();

		ds.push({
			data:d1,
			bars: {
				show: true, 
				barWidth: 0.2, 
				order: 1,
				lineWidth : 2
			}
		});
		ds.push({
			data:d2,
			bars: {
				show: true, 
				barWidth: 0.2, 
				order: 2
			}
		});
		ds.push({
			data:d3,
			bars: {
				show: true, 
				barWidth: 0.2, 
				order: 3
			}
		});

		if($('.flot-bar').length > 0){
			$.plot($(".flot-bar"), ds, {grid: {
				hoverable: true,
				clickable: true
			},colors: [ '#2872bd', '#666666', '#feb900', '#128902', '#c6c12f']});
		}

		if($('.flot-live').length > 0){
			$(function () {
				var data = [], totalPoints = 300;
				function getRandomData() {
					if (data.length > 0)
						data = data.slice(1);

					while (data.length < totalPoints) {
						var prev = data.length > 0 ? data[data.length - 1] : 50;
						var y = prev + Math.random() * 10 - 5;
						if (y < 0)
							y = 0;
						if (y > 100)
							y = 100;
						data.push(y);
					}

					var res = [];
					for (var i = 0; i < data.length; ++i)
						res.push([i, data[i]])
					return res;
				}

				var updateInterval = 30;


				var options = {
				series: { shadowSize: 0 },
				yaxis: { min: 0, max: 100 },
				xaxis: { show: false }
				};
				var plot = $.plot($(".flot-live"), [ getRandomData() ], options);

				function update() {
					plot.setData([ getRandomData() ]);
					plot.draw();

					setTimeout(update, updateInterval);
				}

				update();
			});
		}
	}
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
});
