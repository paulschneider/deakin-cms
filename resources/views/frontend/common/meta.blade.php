    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

@if (isset($entity))

    <?php
        $tags = [
            'meta_title'              => 'title',
            'meta_description'        => 'description',
            'meta_keywords'           => 'keywords',
            'meta_social_title'       => 'og:title',
            'meta_social_description' => 'og:description',
            'meta_social_image'       => 'og:image',
        ];
        $entity->meta_title = ! empty($entity->meta_title) ? $entity->meta_title : $entity->title;
        $entity->meta_social_title = ! empty($entity->meta_social_title) ? $entity->meta_social_title : $entity->title;
    ?>

    @foreach ($tags as $key => $tag)
        @if ( ! empty($entity->{$key}))
            <meta name="{{ $tag }}" content="{{ $entity->{$key} }}">
        @endif
    @endforeach

    @if (!empty($entity->meta_description))
        <meta name="ROBOTS" content="NOODP">
    @endif

@endif