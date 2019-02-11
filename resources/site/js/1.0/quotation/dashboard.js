$(function(){	
	var uploads = [[0, 2], [1, 9], [2,5], [3, 13], [4, 6], [5, 13], [6, 8]];
	var downloads = [[0, 0], [1, 6.5], [2,4], [3, 10], [4, 2], [5, 10], [6, 4]];
	jQuery("#basicflot").attr("style","width: 100%; height: 300px; margin-bottom: 20px; padding: 0px; position: relative;");
	var plot = jQuery.plot(jQuery("#basicflot"),[
		{ data: uploads,
         label: "Uploads",
         color: "#1CAF9A"
        },
        { data: downloads,
          label: "Downloads",
          color: "#428BCA"
        }
      ],
      {
		series: {
				lines: {
					show: false
				},
				splines: {
					show: true,
					tension: 0.5,
					lineWidth: 1,
					fill: 0.45
				},
				shadowSize: 0
			},
			points: {
				show: true
			},
		  legend: {
          position: 'nw'
        },
		  grid: {
          hoverable: true,
          clickable: true,
          borderColor: '#ddd',
          borderWidth: 1,
          labelMargin: 10,
          backgroundColor: '#fff'
        },
		  yaxis: {
          min: 0,
          max: 15,
          color: '#eee'
        },
        xaxis: {
          color: '#eee'
        }
	});
		
	 var previousPoint = null;
	 jQuery("#basicflot").bind("plothover", function (event, pos, item) {
      jQuery("#x").text(pos.x.toFixed(2));
      jQuery("#y").text(pos.y.toFixed(2));
			
		if(item) {
		  if (previousPoint != item.dataIndex) {
			 previousPoint = item.dataIndex;
						
			 jQuery("#tooltip").remove();
			 var x = item.datapoint[0].toFixed(2),
			 y = item.datapoint[1].toFixed(2);
	 			
			 showTooltip(item.pageX, item.pageY,
				  item.series.label + " of " + x + " = " + y);
		  }
			
		} else {
		  jQuery("#tooltip").remove();
		  previousPoint = null;            
		}
		
	 });
		
	 jQuery("#basicflot").bind("plotclick", function (event, pos, item) {
		if (item) {
		  plot.highlight(item.series, item.datapoint);
		}
	 });
});