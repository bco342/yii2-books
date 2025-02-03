<?php

namespace app\commands;

use yii\console\Controller;
use app\services\DatabaseSeeder;
use yii\console\ExitCode;

class SeedController extends Controller
{
    private DatabaseSeeder $seeder;

    public function init(): void
    {
        parent::init();
        $this->seeder = new DatabaseSeeder();
    }

    public function actionIndex(int $cleanNeeded = 0, int $count = 12): int
    {
        try {
            if ($cleanNeeded) {
                $this->seeder->cleanTables();
            }
            $usersCount = $this->seeder->createUsers($count);
            $authors = $this->seeder->createAuthors($count);
            $books = $this->seeder->createBooks($count, $authors);
            $subscriptions = $this->seeder->createSubscriptions($authors);

            echo "\nSeeding completed successfully!\n";
            echo "Summary:\n";
            echo "- Users created: " . $usersCount . "\n";
            echo "- Authors created: " . count($authors) . "\n";
            echo "- Books created: " . count($books) . "\n";
            echo "- Subscriptions created: {$subscriptions}\n";
            
            return ExitCode::OK;

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
