            <div class="ibox float-e-margins collapsed">

                <div class="ibox-title">
                    <h5>Social <small>Meta data consumed by social media shares</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">

                    <?php
                        $description = 'Optional. This field can be used to set a specific page title.';
                    ?>
                    {!! FormField::meta_social_title(['type' => 'text', 'label' => 'Title', 'field-description' => $description]) !!}

                    <?php
                        $description = 'Optional. This field can be used to give search engines a useful description of the content of this page.';
                    ?>
                    {!! FormField::meta_social_description(['type' => 'textarea', 'rows' => 2, 'label' => 'Description', 'field-description' => $description]) !!}

                    <div class="form-group">

                        {!! Form::label('meta_social_image', 'Image', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            @include('admin.attachments.dropzone', [
                                'id' => uniqid(),
                                'into' => '#meta-social-image',
                                'files' => '.jpg,.png,.gif',
                            ])

                            @if ( ! empty($entity->meta_social_image))
                                {!! Form::hidden('meta_social_image', $entity->meta_social_image->id, ['id' => 'meta-social-image']) !!}
                                <img src="{{ $entity->meta_social_image->file->url('medium') }}">
                            @else
                                {!! Form::hidden('meta_social_image', null, ['id' => 'meta-social-image']) !!}
                            @endif
                        </div>
                    </div>

                </div>
            </div>


