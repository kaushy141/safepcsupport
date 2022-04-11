<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <div class="card" id="card-detail-print">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i> Event calender</b>
        <div class="card-actions"> <a data-title="Reload" title="Reload" href="<?php echo $app->siteUrl('eventcalender');?>"><i class="fa fa-history  fa-lg m-t-2"></i></a> </div>
      </div>
      <div class="card-block">
        <div class="row">
        <div class="col-xs-12">            
            <div id="bootstrapModalFullCalendar"></div>
        </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<script>
        $(document).ready(function() { //alert(moment().subtract('days',14));
            $('#bootstrapModalFullCalendar').fullCalendar({
                header: {
                    left: '',
                    center: 'prev title next',
                    right: 'month,agendaWeek,agendaDay'
                },
                eventClick:  function(event, jsEvent, view) {
					setPopup(event.id, event.title);
                    modal.Body(event.description);
					modal.Footer('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <a class="btn btn-primary text-white" href="'+event.url+'" target="_blank">Event Page</a>');
                    $('#appModal').modal();
                    return false;
                },
                events: {url:sitePath+'event.php?tz='+new Date().toString().match(/([A-Z]+[\+-][0-9]+)/)[1]}
            });
        });
    </script>