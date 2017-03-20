

<script type="text/javascript">
    $(function(){


        var updateOutput = function(e) {
            var list = e.length ? e : $(e.target),
                serial = JSON.stringify(list.nestable('serialize'));

            updateStarted();

            Pace.track(function(){
                $.ajax({
                    url: "{{ $route }}",
                    method: "PATCH",
                    beforeSend: updateStarted,
                    success: updateSuccess,
                    error: updateFailed,
                    complete: updateComplete,
                    data: {'serial': serial, '_token': _token},
                    dataType: 'json',
                    jsonp: false
                });
            });
        };

        var updateStarted = function(){
            $('#submit-sort').removeClass('btn-primary').addClass('btn-warning');
            $('#submit-sort .fa').removeClass('fa-save').addClass('fa-refresh');
        };

        var updateSuccess = function(data) {
            toastr.success(data.success, null, {timeOut: 1400, positionClass: "toast-top-right"});
        };

        var updateFailed = function() {
            toastr.error('Sorting has failed.', null, {timeOut: 15000, progressBar: true, positionClass: "toast-top-right"});
        };

        var updateComplete = function() {
            $('#submit-sort').removeClass('btn-warning').addClass('btn-primary');
            $('#submit-sort .fa').removeClass('fa-refresh').addClass('fa-save');
        };

        $('#nestable').nestable({group: 1, maxDepth: {{ $maxDepth or 10 }}});

        $('#submit-sort').on('click', function(e){
            e.preventDefault();

            updateOutput($('#nestable'));
        })
    })
</script>

