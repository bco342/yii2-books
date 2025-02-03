<?php

namespace app\controllers;

use app\models\Book;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\models\Author;
use yii\db\Expression;

/**
 * ReportController implements reporting functionality.
 */
class ReportController extends Controller
{
    /**
     * Displays top 10 authors by number of books published in a year.
     * @param null $year
     * @return string
     */
    public function actionIndex($year = null): string
    {
        $query = Author::find()
            ->select([
                'authors.*',
                'name' => 'full_name',
                'books_count' => new Expression('COUNT(DISTINCT books.id)')
            ])
            ->leftJoin('book_authors', 'authors.id = book_authors.author_id')
            ->leftJoin('books', 'book_authors.book_id = books.id');

        if (!empty($year)) {
            $query->andWhere(['books.year' => $year]);
        }

        $authors = $query
            ->groupBy('authors.id')
            ->having(['>', 'books_count', 0])
            ->orderBy(['books_count' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $authors,
            'pagination' => false,
            'sort' => [
                'attributes' => ['name', 'books_count'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'years' => Book::getYears(),
            'year' => $year,
        ]);
    }
}
