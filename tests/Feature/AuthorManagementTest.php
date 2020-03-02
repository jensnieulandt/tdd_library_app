<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthorCanBeCreated()
    {
        $this->post('/authors', [
            'name' => 'myName',
            'date_of_birth' => '05/14/1988'
        ]);

        $author = Author::all();

        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->date_of_birth);
        $this->assertEquals('1988/14/05', $author->first()->date_of_birth->format('Y/d/m'));
    }
}
