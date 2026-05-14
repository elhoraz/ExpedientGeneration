<?php

namespace App\Models;

use CodeIgniter\Model;

class CelestialModel extends Model
{
    protected $table = 'celestial_cards';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numeral', 'symbol', 'name', 'meaning'];
}
