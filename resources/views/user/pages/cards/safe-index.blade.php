<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Safe Cards</title>
<style>body{font:14px/1.4 system-ui;margin:20px} table{border-collapse:collapse;width:100%} th,td{border:1px solid #ddd;padding:8px}</style>
</head><body>
<h1>Cards (Safe Mode)</h1>
<p><a href="/user/cards/create">Create New</a></p>
<table><thead><tr><th>Title</th><th>Actions</th></tr></thead><tbody>
@foreach($cards as $card)
<tr>
  <td>{{ $card->title }}</td>
  <td>
    <a href="{{ route('safe.cards.builder', $card->id) }}">Modify (Safe)</a>
  </td>
</tr>
@endforeach
</tbody></table>
<p><a href="/safe/health">Health</a></p>
</body></html>
