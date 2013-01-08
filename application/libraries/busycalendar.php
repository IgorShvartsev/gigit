<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');      

class BusyCalendar
{
    public $month = null;
    
    public $year  = null;
    
    public $settings = array(
         'week_start' => 0 
    );
    
    public function __construct($config = array())
    {
        $this->month = isset($config['month']) && !empty($config['month']) ? (int) $config['month']  :  date('n');
        $this->year  = isset($config['year']) && !empty($config['year']) ? (int) $config['year']  :  date('Y'); 
    }
    
    /**
     * Returns an array of the names of the days, using the current locale.
     *
     * @param   integer  left of day names
     * @return  array
     */
    public function days($length = TRUE)
    {
        // strftime day format
        $format = ($length === TRUE OR $length > 3) ? '%A' : '%a';

        // Days of the week
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        
        if ($this->settings['week_start'] > 0)
        {
            for ($i = 0; $i < $this->settings['week_start']; $i++)
            {
                array_push($days, array_shift($days));
            }
        }

        // This is a bit awkward, but it works properly and is reliable
        foreach ($days as $i => $day)
        {
            // Convert the English names to i18n names
            $days[$i] = strftime($format, strtotime($day));
        }

        if (is_int($length) OR ctype_digit($length))
        {
            foreach ($days as $i => $day)
            {
                // Shorten the days to the expected length
                $days[$i] = substr($day, 0, $length);
            }
        }

        return $days;
    }
    
    
    /**
     * Returns an array for use with a view. The array contains an array for
     * each week. Each week contains 7 arrays, with a day number and status:
     * TRUE if the day is in the month, FALSE if it is padding.
     *
     * $param array $busy_dates - list of dates that should be marked as busy (usually are taken from DB)
     * @return  array
     */
    public function weeks($busy_dates = array())
    {
        $today  = mktime(1, 0, 0, date("m")  , date("d"), date("Y"));

        // First day of the month as a timestamp
        $first = mktime(1, 0, 0, $this->month, 1, $this->year);

        // Total number of days in this month
        $total = (int) date('t', $first);

        // Last day of the month as a timestamp
        $last  = mktime(1, 0, 0, $this->month, $total, $this->year);

        $month = $week = array();

        // Number of days added. When this reaches 7, start a new week
        $days = 0;
        $week_number = 1;

        if (($w = (int) date('w', $first) - $this->settings['week_start']) < 0)
        {
            $w = (7 - $this->settings['week_start']) + date('w', $first);
        }

        if ($w > 0)
        {
            // Number of days in the previous month
            $n = (int) date('t', mktime(1, 0, 0, $this->month - 1, 1, $this->year));

            // i = number of day, t = number of days to pad
            for ($i = $n - $w + 1, $t = $w; $t > 0; $t--, $i++)
            {
                $dt = mktime(1, 0, 0, $this->month - 1  , $i, $this->year);
                $date = date('Y-m-d', $dt);
                // Add previous month padding days
                $week[$date] = array(
                    'available'  => in_array($date, $busy_dates) ? 0 : 1,
                    'class' => 'prev_days ' . ($dt < $today ? 'inactive' : '')
                );
                $days++;
            }
        }

        // i = number of day
        for ($i = 1; $i <= $total; $i++)
        {
            if ($days % 7 === 0)
            {
                // Start a new week
                $month[] = $week;
                $week = array();

                $week_number++;
            }
            $dt = mktime(1, 0, 0, $this->month, $i, $this->year);
            $date = date('Y-m-d', $dt); 
            // Add days to this month
            $week[$date] = array(
                'available'  => in_array($date, $busy_dates) ? 0 : 1,
                'class' => 'cur_days '. ($dt < $today ? 'inactive' : '')
            );
            $days++;
        }
        
        if (($w = (int) date('w', $last) - $this->settings['week_start']) < 0)
        {
            $w = (7 - $this->settings['week_start']) + date('w', $last);
        }

        if ($w >= 0)
        {
            // i = number of day, t = number of days to pad
            for ($i = 1, $t = 6 - $w; $t > 0; $t--, $i++)
            {
                $dt = mktime(1, 0, 0, $this->month + 1  , $i, $this->year);
                $date = date('Y-m-d', $dt); 
                // Add next month padding days
                $week[$date] = array(
                    'available'  => in_array($date, $busy_dates) ? 0 : 1,
                    'class' => 'next_days '  . ($dt < $today ? 'inactive' : '')
                );
            }
        }

        if ( ! empty($week))
        {
            // Append the remaining days
            $month[] = $week;
        }

        return $month;
    }
    
    /**
     * Get the URL for a previous month link
     *
     * @return  string
     */
    public function prev_month_url()
    {
        $today = mktime(0, 0, 0, date("m")  , 1, date("Y"));
        $date  = mktime(0, 0, 0, $this->month - 1, 1, $this->year);
        $month = date('n', $date);
        $year  = date('Y', $date);   
        return $date >= $today ? array('m' => $month, 'y' => $year) : array();
    }
    
    /**
     * Get the URL for a next month link
     *
     * @return  string
     */
    public function next_month_url()
    {
        $date  = mktime(0, 0, 0, $this->month + 1, 1, $this->year);
        $month = date('n', $date);
        $year  = date('Y', $date);
        return array('m' => $month, 'y' => $year);
    }
    
}