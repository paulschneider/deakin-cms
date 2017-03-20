<?php
namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    /**
     * Change the table to something nicer.
     * @var string
     */
    protected $table = 'cron_jobs';

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['min', 'hour', 'day_month', 'month', 'day_week', 'command', 'online', 'once', 'year', 'entity_id', 'entity_type', 'parent_id', 'parent_type'];

    /**
     * Morph back to related entity
     * @return morphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * Helper function to string the values together.
     * @return string
     */
    public function getScheduleAttribute()
    {
        $output = null;

        $output .= $this->min . ' ';
        $output .= $this->hour . ' ';
        $output .= $this->day_month . ' ';
        $output .= $this->month . ' ';
        $output .= $this->day_week;

        return $output;
    }

    /**
     * Helper function to string the values together.
     * @return string
     */
    public function getCommandBaseAttribute()
    {
        $bits = explode(' ', $this->command);

        return reset($bits);
    }

    /**
     * Helper function to string the values together.
     * @return string
     */
    public function getDateAttribute()
    {
        $hour  = (!is_numeric($this->hour)) ? date('H') : $this->hour;
        $min   = (!is_numeric($this->hour)) ? date('i') : $this->min;
        $month = (!is_numeric($this->month)) ? date('m') : $this->month;
        $day   = (!is_numeric($this->day_month)) ? date('d') : $this->day_month;

        if (empty($this->year)) {
            $year = date('Y');
        } else {
            $year = $this->year;
        }

        $unix = mktime($hour, $min, 0, $month, $day, $year);

        return clone Carbon::createFromTimeStamp($unix);
    }
}
