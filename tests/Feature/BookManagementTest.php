<?php

namespace Tests\Feature;

use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Book;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function testBookCanBeCreated()
    {
        $response = $this->post('/books', $this->data());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function testBookTitleIsRequired()
    {
        $response = $this->post('/books', array_merge($this->data(), ['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function testBookAuthorIsRequired()
    {
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    public function testBookCanBeUpdated()
    {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(), array_merge($this->data(), [
            'title' => 'newTitle',
            'author_id' => 'newAuthor'
        ]));

        $this->assertEquals('newTitle', Book::first()->title);
        $this->assertEquals(Author::where('name', 'newAuthor')->first()->id, Book::first()->author_id);

        $response->assertRedirect($book->fresh()->path());
    }

    public function testBookCanBeDeleted()
    {
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    public function testNewAuthorIsAddedAutomatically()
    {
        $this->post('/books', $this->data());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }
    private function data()
    {
        return [
            'title' => 'myTitle',
            'author_id' => 'myAuthor'
        ];
    }
}
