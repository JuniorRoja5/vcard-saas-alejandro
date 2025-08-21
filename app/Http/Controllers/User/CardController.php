<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Support\BuildsSettings;

class CardController extends Controller
{
    use BuildsSettings;

    public function index(Request $r){
        $cards = Card::where('user_id', Auth::id())->latest('updated_at')->get();
        $settings = $this->buildSettings();
        return view('user.pages.cards.index', compact('cards', 'settings'));
    }

    public function create(){
        $settings = $this->buildSettings();
        return view('user.pages.cards.create', compact('settings'));
    }

    public function store(Request $r){
        $r->validate([
            'title' => ['nullable','string','max:191'],
            'slug'  => ['nullable','string','max:191','alpha_dash','unique:cards,slug']
        ]);

        $title = trim((string)($r->input('title') ?? '')) ?: 'Card '.now()->format('Ymd-His');
        $slug  = $r->input('slug');
        if(!$slug){
            $base = Str::slug($title) ?: 'card';
            $slug = $base; $i=1;
            while(Card::where('slug',$slug)->exists()){ $slug = "{$base}-{$i}"; $i++; }
        }

        $card = Card::create([
            'user_id' => Auth::id(),
            'title'   => $title,
            'slug'    => $slug,
            'status'  => 'active'
        ]);

        return redirect()->route('user.cards.builder', $card->id);
    }

    public function builder(Card $card){
        abort_unless($card->user_id === Auth::id(), 403);
        $settings = $this->buildSettings();
        $cardId = $card->id;
        return view('user.pages.cards.mi-tienda', compact('cardId','settings'));
    }
}