<?php

namespace Tests;

use App\Models\News;

class NewsTest extends TestCase
{
    /**
     * Test news store.
     */
    public function testNewsStore()
    {
        $news = new News();
        $news->title = 'Test title';
        $news->content = 'Test content';

        $this->assertTrue($news->save());

        // Check in Database.
        $news = News::findOne(['title' => 'Test title']);
        $this->assertNotNull($news);
    }

    /**
     * Test news update.
     */
    public function testNewsUpdate()
    {
        $news = new News();
        $news->title = 'Test title';
        $news->content = 'Test content';
        $news->save();

        $news = News::findOne(['title' => 'Test title']);
        $news->title = 'Updated title';

        $this->assertTrue($news->save());

        // Check in Database.
        $news = News::findOne(['title' => 'Updated title']);
        $this->assertNotNull($news);
    }

    /**
     * Test news delete.
     */
    public function testNewsDelete()
    {
        $news = new News();
        $news->title = 'Test title';
        $news->content = 'Test content';
        $news->save();

        $news = News::findOne(['title' => 'Test title']);

        $this->assertTrue($news->delete());

        // Check in Database.
        $news = News::findOne(['title' => 'Test title']);
        $this->assertFalse($news);
    }

    /**
     * Test news list.
     */
    public function testNewsList()
    {
        for ($i = 0; $i < 10; $i++) {
            $news = new News();
            $news->title = "Test title $i";
            $news->content = "Test content $i";
            $news->save();
        }

        $news = News::findAll();

        $this->assertIsArray($news);
    }
}
