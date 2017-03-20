

<div class="row schedule-row">
    <label class="control-label col-sm-1">Task</label>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-sm-4">
                <label class="sr-only">Action</label>
                {!! Form::select('schedule['.$counter.'][command_base]', config('schedule.allowed'), (empty($job->command_base) ? null : $job->command_base), ['class' => 'form-control']) !!}
            </div>
            <div class="col-sm-4">
                <label class="sr-only">Date</label>
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text('schedule['.$counter.'][date]', (empty($job->date) ? null : $job->date->format('d/m/Y')), ['class' => 'form-control', 'placeholder' => 'Date']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <label class="sr-only">Time</label>
                <div class="input-group time bootstrap-timepicker">
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                    {!! Form::text('schedule['.$counter.'][time]', (empty($job->date) ? null : $job->date->format('h:i a')), ['class' => 'form-control', 'placeholder' => 'Time']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
