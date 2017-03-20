{!! GlobalClass::add('body', ['multiple-schedule']) !!}

<div class="ibox float-e-margins collapsed">

    <div class="ibox-title">
        <h5>Scheduled Tasks <small>Useful for date sensitive content.</small></h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>

    <?php
        // Get the old values or the values from the entity passed in
        $schedule = Request::old('schedule', []);

        if (empty($schedule) && isset($entity)) {
            if ($entity) {
                $exists = $entity->allSchedules($entity->revision_id);
                if ($exists->count()) {
                    $schedule = $exists;
                }
            }
        } else {
            foreach ($schedule as &$job) {
                if ( ! empty($job['date']) && ! empty($job['time'])) {
                    $job['date'] = Carbon::createFromFormat('d/m/Y h:i A', $job['date'].' '.$job['time']);
                }
            }
        }
    ?>

    {!! Form::hidden('schedule_form', 1) !!}

    <div class="ibox-content clearfix">
        <div class="dd multiple-fields">
            <ol class="dd-list col-md-12 schedule-multiple-fields">
                <?php $counter = 0; ?>
                @if ( ! empty($schedule))
                    @foreach ($schedule as $job)
                        {{-- Make one for each field --}}
                        <li class="dd-item multiple-field slim clearfix">

                            @include('admin.common.schedule_row', ['job' => $job, 'counter' => $counter])
                        </li>
                        <?php $counter++ ?>
                    @endforeach
                @else
                    {{-- If it's empty, still output one so that we can use it as the template --}}
                    <li class="dd-item multiple-field slim clearfix">
                        @include('admin.common.schedule_row', ['counter' => 0])
                    </li>

                @endif
            </ol>
        </div>
    </div>
</div>

