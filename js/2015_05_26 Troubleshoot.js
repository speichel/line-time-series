$(function () {
"use strict";
  var chart;
   $('#resizer').resizable({
    // On resize, set the chart size to that of the
    // resizer minus padding. If your chart has a lot of data or other
    // content, the redrawing might be slow. In that case, we recommend
    // that you use the 'stop' event instead of 'resize'.
    resize: function () {
     chart.setSize(
      this.offsetWidth - 20,
      this.offsetHeight - 20,
      false
     );
    }
   });
 
  $('#container').highcharts({
   chart: {
    zoomType: 'x'
   },
   title: {
    text: 'Voltage & Power'
   },
   subtitle: {
    text: document.ontouchstart === undefined ?
      'Click and drag in the plot area to zoom in' :
      'Pinch the chart to zoom in'
   },
   xAxis:  {
      type: 'datetime',
      labels: {
               enabled:false
              }
     },
   yAxis: [{ // Primary yAxis
     labels: {
      format: '{value} KW'
       },
     title: {
      text: 'Power'
       },
     plotBands: [{
      
      from: 94000,
      to: 95000,
      color: 'yellow',
      label : {
        text : 'Last quarter\'s value range'
        }
     }],
     opposite: true

     }, { // Secondary yAxis
       gridLineWidth: 0,
       title: {
        text: 'Voltage'
       },
       labels: {
        format: '{value} V'
       }

      }, { // Tertiary yAxis
       gridLineWidth: 0,
       title: {
        text: 'Vars'
       },
       labels: {
        format: '{value} VAR'
       }

      }, { // Fourth yAxis
     labels: {
      format: '{value}'
       },
     title: {
      text: 'PF'
       },
     opposite: true

     }
      ],
      tooltip: {
            shared: true
        },
      
      
   legend: {
    layout: 'vertical',
    
    align: 'left',
    x: 200,
    verticalAlign: 'top',
    y: 0,
    floating: true,
    backgroundColor:'rgba(255, 255, 255, 0.1)'
   },
   
   
   
   
   plotOptions: {
    area: {
     fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, Highcharts.getOptions().colors[0]],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
     marker: {
      radius: 2
     },
     lineWidth: 1,
     states: {
      hover: {
       lineWidth: 1
      }
     },
     threshold: null
     
    }
   },
   
   series: [{
    type: 'area',
    name: 'Voltage',
    color: '#800000',
    yAxis: 1,
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#800000'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [  ]
   },
   {
    type: 'area',
    name: 'Power (KW)',
    color: '#008000',
    yAxis:2,
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#008000'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [ ]
   },
 
   {
    type: 'area',
    name: 'Var Set Point',
    visible: false,
    color: '#81d8d0',
    yAxis:2,
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#81d8d0'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [   ]
   },
   {
    type: 'area',
    name: 'Point of Interconnection Vars',
    visible: false,
    color: '#cc0000',
    yAxis:2,
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#cc0000'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [ ]
   },
   {
    type: 'area',
    name: 'Capacitor Bank',
    visible: false,
    color: '#999999',
    yAxis:2,
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#999999'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [  ]
   }, 
   {
    type: 'area',
    name: 'Meeting the VAR requirement for Given Power?',
    visible: false,
    color: '#468499',
    yAxis:3, 
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#468499'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [  ]
   },
   {
    type: 'area',
    name: 'Power Factor (PF)',
    visible: false,
    color: '#468499',
    fillColor: {
      linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
      stops: [
       [0, '#468499'],
       [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
      ]
     },
    data:  [  ]
   }

   
      ]
  });
  
  chart = $('#container').highcharts();
  
  
 });