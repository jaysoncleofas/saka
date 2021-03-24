<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\Transaction;
use DB;

class CalendarController extends Controller
{
    public $title;
    public $allDay; // a boolean
    public $start; // a DateTime
    public $end; // a DateTime, or null
    public $properties = array(); // an array of other misc properties

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin.calendar.index');
    }

    function parseDateTime($string, $timeZone=null) {
        $date = new DateTime($string);

        return $date;
      }

    public function getEvent(Request $request) {
        // Short-circuit if the client did not give us a date range.
        if (!isset($_GET['start']) || !isset($_GET['end'])) {
            die("Please provide a date range.");
        }
        
        // Parse the start/end parameters.
        // These are assumed to be ISO8601 strings with no time nor timeZone, like "2013-12-29".
        // Since no timeZone will be present, they will parsed as UTC.
        $range_start =  $this->parseDateTime($_GET['start']);
        $range_end =  $this->parseDateTime($_GET['end']);
        
        
        // Read and parse our events JSON file into an array of event data arrays.
        $transactions = Transaction::orderBy('checkIn_at')->get();
        $jsontest = DB::table('transactions')
                        ->select(DB::raw('CONCAT(firstName,", ",rooms.name,", ",type,", ",status) AS title'), 'checkIn_at as start', 'checkOut_at as end')
                        ->leftJoin('guests', 'transactions.guest_id', '=', 'guests.id')
                        ->leftJoin('rooms', 'transactions.room_id', '=', 'rooms.id')
                        ->whereBetween('checkIn_at', [$range_start, $range_end])
                        ->where('transactions.room_id', '!=', null)
                        ->get();
        
        $jsontest1 = DB::table('transactions')
                        ->select(DB::raw('CONCAT(firstName,", ",cottages.name,", ",type,", ",status) AS title'), 'checkIn_at as start', 'checkOut_at as end')
                        ->leftJoin('guests', 'transactions.guest_id', '=', 'guests.id')
                        ->leftJoin('cottages', 'transactions.cottage_id', '=', 'cottages.id')
                        ->whereBetween('checkIn_at', [$range_start, $range_end])
                        ->where('transactions.cottage_id', '!=', null)
                        ->get();

        $jsontest2 = DB::table('transactions')
                        ->select(DB::raw('CONCAT(firstName,", Exclusive, ",type,", ",status) AS title'), 'checkIn_at as start', 'checkOut_at as end')
                        ->leftJoin('guests', 'transactions.guest_id', '=', 'guests.id')
                        ->whereBetween('checkIn_at', [$range_start, $range_end])
                        ->where('transactions.is_exclusive', 1)
                        ->get();

        $json =  json_decode(json_encode($jsontest), true); 
        $json1 =  json_decode(json_encode($jsontest1), true); 
        $json2 =  json_decode(json_encode($jsontest2), true); 
        
        $input_arrays = array_merge($json, $json1, $json2);
        
        // Accumulate an output array of event data arrays.
        $output_arrays = array();
        foreach ($input_arrays as $array) {
        
            // Convert the input array into a useful Event object
            $event = $this->GetEventt($array);
        
            // If the event is in-bounds, add it to the output
            if ($this->isWithinDayRange($range_start, $range_end)) {
            $output_arrays[] = $this->toArray();
            }
        }
        
        // Send JSON to the client.
        echo json_encode($output_arrays);
    }

    public function toArray() {

        // Start with the misc properties (don't worry, PHP won't affect the original array)
        $array = $this->properties;
    
        $array['title'] = $this->title;
    
        // Figure out the date format. This essentially encodes allDay into the date string.
        if ($this->allDay) {
          $format = 'Y-m-d'; // output like "2013-12-29"
        }
        else {
          $format = 'c'; // full ISO8601 output, like "2013-12-29T09:00:00+08:00"
        }
    
        // Serialize dates into strings
        $array['start'] = $this->start->format($format);
        if (isset($this->end)) {
          $array['end'] = $this->end->format($format);
        }
    
        return $array;
      }

    public function GetEventt($array) {

        $this->title = $array['title'];
    
        if (isset($array['allDay'])) {
          // allDay has been explicitly specified
          $this->allDay = (bool)$array['allDay'];
        }
        else {
          // Guess allDay based off of ISO8601 date strings
          $this->allDay = preg_match('/^\d{4}-\d\d-\d\d$/', $array['start']) &&
            (!isset($array['end']) || preg_match('/^\d{4}-\d\d-\d\d$/', $array['end']));
        }
    
        // if ($this->allDay) {
          // If dates are allDay, we want to parse them in UTC to avoid DST issues.
          $timeZone = null;
        // }
    
        // Parse dates
        $this->start = $this->parseDateTime($array['start'], $timeZone);
        $this->end = isset($array['end']) ? $this->parseDateTime($array['end'], $timeZone) : null;
    
        // Record misc properties
        foreach ($array as $name => $value) {
          if (!in_array($name, array('title', 'allDay', 'start', 'end'))) {
            $this->properties[$name] = $value;
          }
        }
      }

      public function isWithinDayRange($rangeStart, $rangeEnd) {

        // Normalize our event's dates for comparison with the all-day range.
        $eventStart = $this->stripTime($this->start);
    
        if (isset($this->end)) {
          $eventEnd = $this->stripTime($this->end); // normalize
        }
        else {
          $eventEnd = $eventStart; // consider this a zero-duration event
        }
    
        // Check if the two whole-day ranges intersect.
        return $eventStart < $rangeEnd && $eventEnd >= $rangeStart;
      }

      function stripTime($datetime) {
        return new DateTime($datetime->format('Y-m-d'));
      }
}
