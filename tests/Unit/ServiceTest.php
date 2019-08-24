<?php

namespace Tests\Unit;

use App\Repositories\StudyItemRepository;
use App\Services\StudyItemService;
use App\Study_item;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    //use WithoutMiddleware;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $studyItem = new StudyItemService(new StudyItemRepository(new Study_item()));
        $study = $studyItem->studyItemListing(10);
        $this->assertStringContainsString('Jamaica', $study);
    }
}
