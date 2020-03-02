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

    public function testBookCanBeUpdated()
    {
        $this->post('/books', [
            'title' => 'myTitle',
            'author' => 'myAuthor'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
            'title' => 'newTitle',
            'author' => 'newAuthor'
        ]);

        $this->assertEquals('newTitle', Book::first()->title);
        $this->assertEquals('newAuthor', Book::first()->author);
    }

    public function testBookCanBeDeleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'myTitle',
            'author' => 'myAuthor'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' . $book->id);

        $this->assertCount(0, Book::all());
    }
}
