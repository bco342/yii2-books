<?php

namespace app\services;

use Yii;
use app\models\User;
use app\models\Book;
use app\models\Author;
use app\models\BookAuthors;
use app\models\Subscription;
use Faker\Factory;
use yii\db\Exception;

/**
 * Database seeder with faker data
 * Used in console command: `yii seed`
 */
class DatabaseSeeder
{
    private array $carBrands = [
        'BMW', 'Mercedes-Benz', 'Audi', 'Volkswagen', 'Porsche', 'Toyota', 'Lexus',
        'Лада', 'ГАЗ', 'УАЗ', 'КамАЗ', 'ЗИЛ', 'Москвич', 'Волга'
    ];

    private array $bookTypes = [
        'Руководство по ремонту', 'Техническое обслуживание', 'История марки',
        'Каталог запчастей', 'Тюнинг и модификации'
    ];

    private array $carParts = [
        'двигателя', 'трансмиссии', 'подвески', 'тормозной системы',
        'электрооборудования', 'кузова'
    ];

    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }

    private function generateCarBookTitle(): string
    {
        $brand = $this->carBrands[array_rand($this->carBrands)];
        $type = $this->bookTypes[array_rand($this->bookTypes)];
        $part = rand(0, 1) ? ' ' . $this->carParts[array_rand($this->carParts)] : '';
        return "$type: $brand$part";
    }

    private function generateBookImage(): string
    {
        $width = 800;
        $height = 600;
        $imageId = rand(1, 1000);
        return "https://picsum.photos/id/{$imageId}/{$width}/{$height}";
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    private function createUser(string $username, string $password): bool
    {
        $user = new User([
            'username' => $username,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash($password),
        ]);

        if (!$user->save()) {
            return false;
        }

        echo "Created user {$username}\n";
        return true;
    }

    /**
     * @throws Exception
     */
    public function cleanTables(): void
    {
        echo "Cleaning tables...\n";
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $tables = ['subscriptions', 'book_authors', 'books', 'authors', 'user'];
        foreach ($tables as $table) {
            Yii::$app->db->createCommand()->truncateTable($table)->execute();
        }

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
        echo "Tables cleaned\n";
    }

    /**
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function createUsers(int $count): int
    {
        echo "Creating users...\n";
        $usersCount = 0;

        if ($this->createUser('demo', 'demo')) {
            $usersCount++;
        }

        for ($i=1; $i < $count; $i++) {
            if ($this->createUser($this->faker->userName(), 'password123')) {
                $usersCount++;
            }
        }
        return $usersCount;
    }

    /**
     * @throws Exception
     */
    public function createAuthors(int $count): array
    {
        echo "Creating authors...\n";
        $authorIds = [];
        $usedAuthors = [];

        for ($i=0; $i < $count; $i++) {
            $gender = $this->faker->randomElement(['male', 'male', 'female']);

            do {
                $fullName = $this->faker->lastName($gender)
                    . ' ' . $this->faker->firstName($gender)
                    . ' ' . $this->faker->middleName($gender);
            } while (in_array($fullName, $usedAuthors));

            $author = new Author([
                'full_name' => $fullName,
            ]);
            $usedAuthors[] = $author->full_name;

            if ($author->save()) {
                $authorIds[] = $author->id;
                echo "Created author {$author->full_name}\n";
            }
        }
        return $authorIds;
    }

    /**
     * @throws Exception
     */
    public function createBooks(int $count, array $authors): array
    {
        echo "Creating books...\n";
        $numAuthorsLimit = min(3, count($authors));
        $bookIds = [];

        for ($i=0; $i < $count; $i++) {
            $book = new Book([
                'title' => $this->generateCarBookTitle(),
                'description' => $this->faker->realText(200),
                'isbn' => $this->faker->isbn13,
                'year' => $this->faker->numberBetween(2000, 2025),
                'image' => $this->generateBookImage()
            ]);

            if ($book->save()) {
                $bookIds[] = $book->id;
                echo "Created book '{$book->title}'\n";

                // Assign 1-3 random authors to each book
                $numAuthors = rand(1, $numAuthorsLimit);
                $selectedAuthors = (array)array_rand(array_flip($authors), $numAuthors);

                foreach ($selectedAuthors as $authorId) {
                    $bookAuthor = new BookAuthors([
                        'book_id' => $book->id,
                        'author_id' => $authorId
                    ]);
                    $bookAuthor->save();
                }
            }
        }
        return $bookIds;
    }

    /**
     * @throws Exception
     */
    public function createSubscriptions(array $authors): int
    {
        echo "Creating subscriptions...\n";
        $count = 0;
        $numSubscriptions = rand(3, 10);
        $guestAuthors = (array)array_rand(array_flip($authors), $numSubscriptions);

        foreach ($guestAuthors as $authorId) {
            $subscription = new Subscription([
                'guest_phone' => '+79' . rand(100000000, 999999999),
                'author_id' => $authorId
            ]);

            if ($subscription->save()) {
                $count++;
            }
        }
        return $count;
    }
}
