<?php
	$dl_value = !empty($section['fields']->download) ? $section['fields']->download : null;
	$dl_file  = $dl_value ? $section['attachments'][$dl_value] : null;
	$dl_url   = $dl_value ? $dl_file->file->url() : '';

	$classes = [];
	foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
	    if (get_property($section['fields'], $property)) {
	        $classes[] = $section['fields']->{$property};
	    }

	}

	GlobalClass::add('body', 'has-download');

	$innerClass = ['color-changer'];
	if (!empty($section['fields']->color)) {
	    $innerClass[] = $section['fields']->color;
	}

	if ($dl_file) {
	    if (preg_match('/pdf$/i', $dl_file->file_file_name)) {
	        $icon = 'fa-file-pdf-o';
	    } elseif (preg_match('/(doc|docx)$/i', $dl_file->file_file_name)) {
	        $icon = 'fa-file-word-o';
	    } elseif (preg_match('/(xls|xlsx)$/i', $dl_file->file_file_name)) {
	        $icon = 'fa-file-excel-o';
	    }
	}

?>
<section class="multiple-section multiple-field download {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row {{ implode(' ', $innerClass) }}">

            {{--
                Not sure if this col-sm-12 is needed,
                Insets the .inner to match up the bg color to the grid,
                but might mess with columns.

                Probably remove it?
            --}}

                <div class="col-sm-12">

                    <div class="download-element">
                        <div class="col-sm-1 text-right file-icon-container">
                            <i class="file-icon fa {{ $icon or 'fa-file-text-o' }}"></i>
                        </div>
                        <div class="col-sm-6 vcenter">
                            <h3>{{ $section['fields']->title }}</h3>
                            <p>{{ $section['fields']->description }}</p>
                        </div>
                        <div class="col-sm-5 download-link vcenter">
                            <a href="{{ $dl_url or '#' }}" target="_blank">{{ $section['fields']->action }} <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
