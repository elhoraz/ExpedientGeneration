<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWaOptInToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'wa_notif_opt_in' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
                'comment'    => '1 = opt-in notifikasi WA, 0 = opt-out',
                'after'      => 'no_whatsapp',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'wa_notif_opt_in');
    }
}
