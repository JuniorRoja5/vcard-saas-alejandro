<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        // Resolve duplicates by appending -{id}
        $dupes = DB::table('cards')
            ->select('slug', DB::raw('COUNT(*) as c'))
            ->groupBy('slug')->havingRaw('c > 1')->pluck('slug')->toArray();
        if (!empty($dupes)) {
            foreach ($dupes as $slug) {
                $rows = DB::table('cards')->where('slug',$slug)->orderBy('id')->get();
                $first = true;
                foreach ($rows as $r) {
                    if ($first) { $first=false; continue; }
                    $new = $slug.'-'.$r->id;
                    DB::table('cards')->where('id',$r->id)->update(['slug'=>$new]);
                }
            }
        }
        // Add unique index if missing
        if (!Schema::hasColumn('cards', 'slug')) return;
        // Laravel doesn't expose "has index" easily; try-catch create unique
        try {
            Schema::table('cards', function(Blueprint $t){ $t->unique('slug'); });
        } catch (\Throwable $e) {
            // ignore if already unique or DB-specific failure
        }
    }
    public function down(): void {
        try {
            Schema::table('cards', function(Blueprint $t){ $t->dropUnique(['slug']); });
        } catch (\Throwable $e) {}
    }
};