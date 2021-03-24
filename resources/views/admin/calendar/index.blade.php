@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Calendar</h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Reservation Calendar</h4>
            </div>
            <div class="card-body">
              <div id='loading'>loading...</div>
              <div id='calendar'></div>
            </div>
          </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      initialDate: "{{ date('Y-m-d') }}",
      editable: true,
      navLinks: true, // can click day/week names to navigate views
      dayMaxEvents: true, // allow "more" link when too many events
      events: {
        url: "{{ route('calendar.getEvent') }}",
        failure: function() {
          document.getElementById('script-warning').style.display = 'block'
        }
      },
      loading: function(bool) {
        document.getElementById('loading').style.display =
          bool ? 'block' : 'none';
      }
    });

    calendar.render();
  });



</script>
@endsection