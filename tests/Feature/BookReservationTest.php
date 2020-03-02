<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Book;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testBookCanBeAddedToLibrary()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'myTitle',
            'author' => 'myAuthor'
        ]);

        $response->assertOk();

        $this->assertCount(1, Book::all());
    }

    public function testBookTitleIsRequired()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'myAuthor'
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function testBookAuthorIsRequired()
    {
        $response = $this->post('/books', [
            'title' => 'myTitle',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }
}
