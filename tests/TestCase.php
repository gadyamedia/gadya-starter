<?php

namespace Tests;

use Gadya\Cms\Content\SiteContentRepository;
use Gadya\Cms\Models\Revision;
use Gadya\Cms\Services\PublishSiteContent;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Put a document in place as the live site. Most tests care about what
     * a visitor sees, which is the published copy, not the draft.
     *
     * @param  array<string, mixed>  $document
     */
    protected function publishDocument(array $document): void
    {
        $repository = app(SiteContentRepository::class);

        $repository->saveDraft($document);
        app(PublishSiteContent::class)->handle();
        $repository->flushPublishedCache();

        Revision::query()->delete();
    }
}
