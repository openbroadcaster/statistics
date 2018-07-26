OBModules.DataAndStatistics = new function()
{

  this.init = function()
  {
    OB.Callbacks.add('ready',0,OBModules.DataAndStatistics.initMenu);
    if(!document.getElementById('obmodules-chart-js')) $('head').append('<script id="obmodules-chart-js" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>');
  }

  this.initMenu = function()
  {
    OB.UI.addSubMenuItem('admin','Data & Statistics','data_and_statistics',OBModules.DataAndStatistics.open,140,'data_statistics_module');
  }
  
  this.chartColors = ['#3366CC','#DC3912','#FF9900','#109618','#990099','#3B3EAC','#0099C6','#DD4477','#66AA00','#B82E2E','#316395','#994499','#22AA99','#AAAA11','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC'];
  
  this.open = function()
  {
    OB.UI.replaceMain('modules/data_and_statistics/data_and_statistics.html');
    
    OB.API.post('dataandstatistics', 'get_all', {}, function(response)
    {
      var media_metadata = ['media_categories','media_genres','media_countries','media_languages'];
      var media_status = ['media_status','media_approved','media_owner'];
      var media_formats = ['media_types','media_audio_formats','media_video_formats','media_image_formats'];
      var playlist_status = ['playlist_type','playlist_status'];
    
      $.each(response.data, function(index,data)
      {
        if(media_metadata.indexOf(index)>=0) var container = 'media_metadata';
        else if(media_status.indexOf(index)>=0) var container = 'media_status';
        else if(media_formats.indexOf(index)>=0) var container = 'media_formats';
        else if(playlist_status.indexOf(index)>=0) var container = 'playlist_status';
        else return;
      
        // skip sections with no data
        if(data.length==0) return;
      
        var heading = index.replace(/_/g,' ');
      
        var $section = $('<div class="data_and_statistics-section" id="data_and_statistics-'+index+'"></div>');
        $section.append('<h3><a href="javascript:OBModules.DataAndStatistics.modal(\''+index+'\');">'+heading+'</a></h3>');
        $section.append('<canvas width="1024" height="768"><canvas>');
        $('#data_and_statistics-'+container+'_container').append($section);
        
        $('#data_and_statistics-'+index).data('heading',heading);
        $('#data_and_statistics-'+index).data('data',data);
        
        var values = [];
        var labels = [];
        $.each(data, function(index2,data2)
        {
          values.push(data2.count);
          labels.push(data2.name);
          if(values.length==5) return false;
        });

        var ctx = $('#data_and_statistics-'+index+' canvas')[0].getContext('2d');
        var data = {
          datasets: [{
              data: values,
              backgroundColor: OBModules.DataAndStatistics.chartColors
          }],
          labels: labels
        };
        
        new Chart(ctx, {
          responsive:true,
          maintainAspectRatio: true,
          type: 'pie',
          data: data,
          options: { 
            animation: {duration: 0}, 
            cutoutPercentage: 0, 
            legend: {display: true, labels: { fontColor: '#ffffff'}} 
          }
        });
      });
    });    
  }
  
  this.modal = function(what)
  {
    OB.UI.openModalWindow('modules/data_and_statistics/modal.html');
    $('#data_and_statistics-modal_heading').text( $('#data_and_statistics-'+what).data('heading') );

    var data = $('#data_and_statistics-'+what).data('data');

    $thead = $('#data_and_statistics-modal_table thead tr');
    $tbody = $('#data_and_statistics-modal_table tbody');

    // get total count and total duration
    var total_count = 0;
    var total_duration = 0.0;
    $.each(data, function(index,row)
    {
      total_count+=parseInt(row['count']);
      if(row['duration']) total_duration+=parseFloat(row['duration']);
    });

    // output headings
    $.each(data[0], function(name,value)
    {
      if(what=='media_image_formats' && name=='duration') return true;
      $thead.append( $('<th></th>').text(name) );
    });
    
    // output data
    $.each(data, function(index,row)
    {
      var $row = $('<tr></tr>');
      $.each(row, function(name, value)
      {      
        if(what=='media_image_formats' && name=='duration') return true;
        if(name=='duration' && total_duration>0 && value>0) value=secsToTime(value,'hms')+' ('+Math.round(parseInt(value)*10000/total_duration,2)/100+'%)';
        if(name=='count') value+=' ('+Math.round(parseInt(value)*10000/total_count,2)/100+'%)';
        $row.append( $('<td></td>').text(value) );
      });
      $tbody.append($row);
    });
  }
}
  