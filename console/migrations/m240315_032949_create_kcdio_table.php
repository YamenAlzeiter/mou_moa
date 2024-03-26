<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%kcdio}}`.
 */
class m240315_032949_create_kcdio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%kcdio}}', [
            'id' => $this->primaryKey(),
            'kcdio' => $this->string(100),
            'tag' => $this->string(),
        ]);

        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Engineering', 'tag' => 'KOE']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Islamic Revealed Knowledge and Human Sciences', 'tag' => 'KIRKHS']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Economics and Management Sciences', 'tag' => 'KENMS']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Ahmad Ibrahim Kulliyyah of Laws', 'tag' => 'AIKOL']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Architecture and Environmental Design', 'tag' => 'KAED']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Education', 'tag' => 'KOED']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Information and Communication Technology', 'tag' => 'KICT']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Medicine', 'tag' => 'KOM']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Science', 'tag' => 'KOS']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Allied Health Sciences', 'tag' => 'KAHS']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Dentistry', 'tag' => 'KOD']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Pharmacy', 'tag' => 'KOP']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Nursing', 'tag' => 'KON']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Kulliyyah of Languages and Management', 'tag' => 'KLM']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Institute of Islamic Banking and Finance', 'tag' => 'IIiBF']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'International Institute for Halal Research and Training', 'tag' => 'INHaRT']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'International Institute of Muslim Unity', 'tag' => 'IIMU']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Centre for Languages and Pre-University Academic Development', 'tag' => 'CELPAD']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Entrepreneurship Development Centre', 'tag' => 'EDC']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Centre for Strategic Continuing Education and Training', 'tag' => 'CRESCENT']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Centre for Islamisation', 'tag' => 'CENTRIS']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Office of Deputy Rector (Internationalisation and Glabal Network)', 'tag' => 'DRIGN']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Office of International Affairs', 'tag' => 'IO']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Alumni and Global Networking Division', 'tag' => 'ARD']);
        $this->insert('{{%kcdio}}', ['kcdio' => 'Office of Promotion and Marketing', 'tag' => 'OPM']);

    }



    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%kcdio}}');
    }
}
