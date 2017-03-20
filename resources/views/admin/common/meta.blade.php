            <div class="ibox float-e-margins collapsed">

                <div class="ibox-title">
                    <h5>Meta <small>Meta data used when displaying the content.</small></h5>
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
                    {!! FormField::meta_title(['type' => 'text', 'label' => 'Title', 'field-description' => $description]) !!}

                    <?php
                        $description = 'Optional. This field can be used to give search engines a useful description of the content of this page.';
                    ?>
                    {!! FormField::meta_description(['type' => 'textarea', 'rows' => 2, 'label' => 'Description', 'field-description' => $description]) !!}

                    <?php
                        $description = 'Optional. This field can be used to give search engines a useful set of keywords associated with the content of this page.';
                    ?>
                    {!! FormField::meta_keywords(['type' => 'textarea', 'rows' => 2, 'label' => 'Keywords', 'field-description' => $description]) !!}

                </div>
            </div>


