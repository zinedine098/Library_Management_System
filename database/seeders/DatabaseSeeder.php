<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@libloan.com',
            'password' => Hash::make('password'),
        ]);

        // Create Categories
        $categories = [
            'Fiction',
            'Science',
            'History',
            'Technology',
            'Biography',
            'Children',
            'Comics',
            'Education',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        // Create Sample Books
        $books = [
            [
                'category_id' => 1,
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'publisher' => 'J. B. Lippincott & Co.',
                'isbn' => '978-0-06-112008-4',
                'published_year' => 1960,
                'stock_total' => 5,
            ],
            [
                'category_id' => 1,
                'title' => '1984',
                'author' => 'George Orwell',
                'publisher' => 'Secker & Warburg',
                'isbn' => '978-0-452-28423-4',
                'published_year' => 1949,
                'stock_total' => 3,
            ],
            [
                'category_id' => 2,
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'publisher' => 'Bantam Dell',
                'isbn' => '978-0-553-38016-3',
                'published_year' => 1988,
                'stock_total' => 4,
            ],
            [
                'category_id' => 4,
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'isbn' => '978-0-13-235088-4',
                'published_year' => 2008,
                'stock_total' => 6,
            ],
            [
                'category_id' => 4,
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'publisher' => 'Addison-Wesley',
                'isbn' => '978-0-201-61622-4',
                'published_year' => 1999,
                'stock_total' => 4,
            ],
            [
                'category_id' => 5,
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'publisher' => 'Simon & Schuster',
                'isbn' => '978-1-4516-4853-9',
                'published_year' => 2011,
                'stock_total' => 3,
            ],
            [
                'category_id' => 3,
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Harper',
                'isbn' => '978-0-06-231609-7',
                'published_year' => 2011,
                'stock_total' => 5,
            ],
            [
                'category_id' => 8,
                'title' => 'Learning PHP, MySQL & JavaScript',
                'author' => 'Robin Nixon',
                'publisher' => "O'Reilly Media",
                'isbn' => '978-1-4919-1866-1',
                'published_year' => 2018,
                'stock_total' => 4,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // Create Sample Members
        $members = [
            [
                'membership_number' => '2024001',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '081234567890',
            ],
            [
                'membership_number' => '2024002',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '081234567891',
            ],
            [
                'membership_number' => '2024003',
                'name' => 'Bob Wilson',
                'email' => 'bob.wilson@example.com',
                'phone' => '081234567892',
            ],
            [
                'membership_number' => '2024004',
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'phone' => '081234567893',
            ],
            [
                'membership_number' => '2024005',
                'name' => 'Charlie Davis',
                'email' => 'charlie.davis@example.com',
                'phone' => '081234567894',
            ],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
