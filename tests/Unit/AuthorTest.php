<?php

namespace Tests\Unit;

use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function testOnlyNameIsRequiredToCreateAuthor()
    {
        Author::firstOrCreate([
            'name' => 'myName'
        ]);

        $this->assertCount(1, Author::all());
    }
}
