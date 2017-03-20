            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <h5>Forms <small>Any forms that need to be displayed at the bottom of the page</small></h5>
                </div>

                <div class="ibox-content">
                    <?php
                        $description = 'Optional. This field can be used to set a specific page title.';
                    ?>
                    <?php
                        if ($entity) {
                            $form = $entity->forms()->first();
                        }
                        $default = ! empty($form) ? $form->id : null;
                    ?>
                    {!! FormField::forms(['type' => 'select', 'class' => 'input-lg', 'label' => 'Forms', 'default' => $default, 'options' => $options, 'field-description' => $description]) !!}
                </div>
            </div>


