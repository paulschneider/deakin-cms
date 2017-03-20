<?php
namespace App\Attachments;

use Cache;
use App\Models\Attachment;
use Sunra\PhpSimple\HtmlDomParser;
use App\Repositories\AttachmentsRepository;

class AttachmentManager
{
    /**
     * The instance of AttachmentsRepository
     *
     * @var AttachmentsRepository
     */
    protected $attachment;

    /**
     * The nubmer of minutes the attachments should cache for
     *
     * @var integer
     */
    protected $cache_time;

    /**
     * DI Constructor
     *
     * @param BlocksRepository $block The instance of BlocksRepository
     */
    public function __construct(AttachmentsRepository $attachment)
    {
        $this->attachment = $attachment;
        $this->cache_time = config('cache.attachment_cache');
    }

    /**
     * Trawl through HTML to replace content.
     * This uses HTML DOM which is SLOW.
     *
     * @param  string   $source The html to check.
     * @return string
     */
    public function filter($source)
    {
        $html = HtmlDomParser::str_get_html($source);

        // HTML can be empty
        if (!$html) {
            return $source;
        }

        $brokens = $html->find('span[data-attachment-id]');

        foreach ($brokens as $attachment) {
            $replacement = $this->getHtml($attachment->attr['data-attachment-id'], $attachment->attr);
            $this->changeParentTag($attachment);
            $attachment->outertext = $replacement;
        }

        $source = $html->save();

        return $source;
    }

    /**
     * Loop the parents of a HTMLDom element to find a p tag.
     * Change it into a div with a p class.
     * @param  DOMelement &$attachment Item we're turning into an attachment.
     * @return void
     */
    private function changeParentTag(&$attachment)
    {
        $p       = false;
        $loops   = 0;
        $element = $attachment;

        if (isset($element->attr['data-attachment-type']) && stristr($element->attr['data-attachment-type'], 'image/')) {
            // Image
        } else {
            return;
        }

        while ($p === false && $loops < 3) {
            $parent = $element->parent();

            if ($parent && $parent->tag == 'p') {
                $p = $parent;
            } elseif ($parent) {
                $element = $parent;
            } else {
                break;
            }
            ++$loops;
        }

        // It could already be fixed by the time this runs.
        if (empty($p)) {
            return;
        }

        // Convert to a P tag
        $p->tag = 'div';
        $class  = 'p';
        if ($p->hasAttribute('class')) {
            $class .= ' ' . $p->getAttribute('class');
        }
        $p->setAttribute('class', $class);
    }

    /**
     * Return HTML replacement of a file for embedding in WYSIWYG etc.
     * @param  int    $file_id The Attaachment id.
     * @param  array  $options An array of options in data-* attributes..
     * @return View
     */
    public function getHtml($file_id, $options)
    {
        $attachment = $this->attachment->find($file_id);

        if (empty($attachment)) {
            return null;
        }

        $options = (array) $options;

        $options['isImage'] = stristr($attachment->file->contentType(), 'image/');

        if ($options['isImage']) {
            if (isset($options['data-attachment-mode']) && $options['data-attachment-mode'] == 'link') {
                $options['isImage'] = false;
            }
        }

        $class = $this->getClasses($options);
        $size  = 'original';

        if (!empty($options['data-attachment-size']) && $options['isImage']) {
            if (array_key_exists($options['data-attachment-size'], config('attachments.styles.sizes'))) {
                $size = $options['data-attachment-size'];
            }
        }

        return view('admin.attachments.embedded', compact('attachment', 'options', 'class', 'size'));
    }

    /**
     * Build a set of classes from the given options.
     * @param  array    $options data arrtibutes.
     * @return string
     */
    public function getClasses($options)
    {
        $class = ['attachment-content'];

        if ($options['isImage']) {
            $class[] = 'attachment-image';
        }

        if (!empty($options['data-attachment-align']) && $options['isImage']) {
            $align = $options['data-attachment-align'];
            $class = array_merge($class, config('attachments.styles.align.' . $align . '.class', []));
        }

        if (!empty($options['data-attachment-size']) && $options['isImage']) {
            if (array_key_exists($options['data-attachment-size'], config('attachments.styles.sizes'))) {
                $class[] = $options['data-attachment-size'];
            }
        }

        return implode(' ', $class);
    }

    /**
     * Return an attachment by a friendly URL.
     * @param  string $slug
     * @return mixed  attachment or null
     */
    public function findBySlug($slug)
    {
        $attachments = $this->attachment->findBy('slug', $slug);

        return $attachments;
    }

    /**
     * Get the attachment for the entity
     *
     * @param  integer      $attachment_id The attahment id
     * @return Attachment
     */
    public function getAttachment($attachment_id)
    {
        return $this->attachment->find($attachment_id);
    }
}
