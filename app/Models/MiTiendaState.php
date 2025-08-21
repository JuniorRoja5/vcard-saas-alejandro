<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiTiendaState extends Model
{
    protected $table = 'mi_tienda_states';
    protected $fillable = [
        'user_id','card_id','state_json','fields_json','assets_manifest',
        'ui_version','last_client_url','user_agent','client_ts','save_count','size_bytes'
    ];
}
