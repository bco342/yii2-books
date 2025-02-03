<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_authors}}`.
 */
class m250131_140855_create_book_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_authors}}', [
            'book_id' => $this->integer(),
            'author_id' => $this->integer(),
            'PRIMARY KEY(book_id, author_id)',
        ]);

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-book_authors-book_id}}',
            '{{%book_authors}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%authors}}`
        $this->addForeignKey(
            '{{%fk-book_authors-author_id}}',
            '{{%book_authors}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-book_authors-book_id}}',
            '{{%book_authors}}'
        );

        // drops foreign key for table `{{%authors}}`
        $this->dropForeignKey(
            '{{%fk-book_authors-author_id}}',
            '{{%book_authors}}'
        );

        $this->dropTable('{{%book_authors}}');
    }
}
