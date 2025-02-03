<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscriptions}}`.
 */
class m250131_140856_create_subscriptions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscriptions}}', [
            'author_id' => $this->integer(),
            'guest_phone' => $this->string(20)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'PRIMARY KEY(guest_phone, author_id)',
        ]);

        // Add unique index for guest_phone
        $this->createIndex('idx-subscription-guest_phone', '{{%subscriptions}}', 'guest_phone');

        // add foreign key for table `{{%authors}}`
        $this->addForeignKey(
            '{{%fk-subscriptions-author_id}}',
            '{{%subscriptions}}',
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
        // drops foreign key for table `{{%authors}}`
        $this->dropForeignKey(
            '{{%fk-subscriptions-author_id}}',
            '{{%subscriptions}}'
        );

        $this->dropTable('{{%subscriptions}}');
    }
}
