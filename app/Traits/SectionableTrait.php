<?php
namespace App\Traits;

use App\Models\Attachment;
use Exception;
use Illuminate\Support\Facades\Request;

trait SectionableTrait
{
    /**
     * Instance of SectionsRepository
     *
     * @var SectionsRepository
     */
    protected $section;

    /**
     * Get the SectionRepository
     *
     * @return App\Repositories\SectionsRepository
     */
    protected function getSectionRepository()
    {
        // Lazy instantiation
        if (empty($this->section)) {
            $this->section = app()->make('App\Repositories\SectionsRepository');
        }

        return $this->section;
    }

    /**
     * Relationship to widget blocks
     *
     * @return Blocks
     */
    public function sections()
    {
        return $this->morphMany('App\Models\Section', 'sectionable')
                    ->orderBy('weight', 'ASC');
    }

    /**
     * Boot method adds the events for the model
     */
    public static function bootSectionableTrait()
    {
        // Add the data to the index
        static::saved(function ($model) {
            // Only do this if the environment is a writer
            $model->sectionableSaveSections($model);
        });

        static::deleting(function ($model) {
            $model->sectionableDelete($model);
        });
    }

    /**
     * Attach the section with their fields
     *
     * @return mixed
     */
    public function appendSectionsWithFields()
    {
        $info    = config('sections.sections');
        $options = config('sections.options');

        $sections  = $this->sections;
        $extracted = [];

        foreach ($sections as $section) {
            $section_info = &$info[$section->template];
            $fields       = json_decode($section->content);
            $attachments  = [];

            // Get the attachment objects
            if (!empty($section_info['attachments'])) {
                foreach ($section_info['attachments'] as $attachemnt) {
                    if (!empty($fields->{$attachemnt})) {
                        $foundAttachment = Attachment::find($fields->{$attachemnt});
                        if ($foundAttachment) {
                            $attachments[$fields->{$attachemnt}] = $foundAttachment;
                        } else {
                            $fields->{$attachemnt} = null;
                        }
                    }
                }
            }

            $extracted[] = [
                'id'          => $section->id,
                'template'    => $section->template,
                'fields'      => $fields,
                'info'        => $section_info,
                'options'     => $options[$section->template],
                'attachments' => $attachments,
            ];
        }

        // Set the extracted fields as a new attribute
        $this->setAttribute('section_fields', $extracted);

        return $this;
    }

    /**
     * Save the sections for the model
     *
     * @param mixed $entity The sectionable model
     */
    public function sectionableSaveSections($entity)
    {
        $sections = Request::get('sections');

        // Check if the old ones have been removed
        $olds     = $entity->sections;
        $removing = $olds->modelKeys();
        $weight   = 0;

        if (!empty($sections)) {
            foreach ($sections as $section) {
                $fields = $this->sectionablePrepareSectionValue($section);
                $data   = $this->sectionableFormatSection($fields, $section, $weight);

                if ($section['id'] && $old = $olds->find($section['id'])) {
                    // Remove it from the removing array
                    $key = array_search($section['id'], $removing);

                    unset($removing[$key]);

                    $old->update($data);
                } else {
                    // It's a new section, add a new relationship
                    $entity->sections()->create($data);
                }

                ++$weight;
            }
        }

        // Remove any sections that don't need to be attache to this entity
        if (!empty($removing)) {
            $entity->sections()->whereIn('id', $removing)->delete();
        }
    }

    /**
     * Save a section
     *
     * @param    string $fields  The fields string
     * @param    array  $section The section data
     * @return
     */
    protected function sectionableFormatSection($fields, $item, $weight, $section = null)
    {
        $data = [
            'template' => $item['template'],
            'content'  => $fields,
            'weight'   => $weight,
        ];

        return $data;
    }

    /**
     * Prepare the field data before save
     *
     * @param  array    $section The section data
     * @return string
     */
    protected function sectionablePrepareSectionValue($section)
    {
        // Get the section definition
        $info = config("sections.sections.{$section['template']}");
        if (!$info) {
            throw new Exception("Section {$section['template']} not defined", 1);
        }

        $fields = [];

        foreach (array_keys($info['fields']) as $field) {
            if (!empty($section[$field])) {
                $fields[$field] = $section[$field];
            } else {
                $fields[$field] = null;
            }
        }

        return json_encode($fields);
    }

    /**
     * Render the sections for a sectionable element
     *
     * @return string
     */
    public function sectionableRenderSections()
    {
        $sections = [];
        $this->appendSectionsWithFields();

        // Render each section
        foreach ($this->section_fields as $section) {
            $sections[] = view($section['info']['public_template'], compact('section'))->render();
        }

        return $sections;
    }

    /**
     * Delete the secitonable entities
     *
     * @param mixed $model The entity being deleted
     */
    protected function sectionableDelete($model)
    {
        $repository = $this->getSectionRepository();
        $query      = $repository->query();

        $query->where('sectionable_type', '=', get_class($model))
              ->where('sectionable_id', '=', $model->id)
              ->delete();
    }

    /**
     * Check if any of the sections are forms
     *
     * @return boolean
     */
    public function hasForm()
    {
        foreach ($this->sections as $section) {
            if ($section->template === 'block_form') {
                return true;
            }
        }
    }
}
