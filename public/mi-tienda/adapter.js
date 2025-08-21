(function(){
  function qs(n){ try { return new URLSearchParams(location.search).get(n); } catch(e){ return null; } }
  var cardId = Number(qs('card_id'));
  var slug = qs('slug') || '';
  var isPublic = (qs('public') === '1' || qs('mode') === 'public');
  var hasCard = Number.isFinite(cardId) && cardId > 0;

  var KEY_PREFIXES = ['mi-', 'mitienda', 'my-store', 'mystore', 'store', 'ms-'];
  function needsNamespace(key){
    var k = (key||'').toLowerCase();
    for (var i=0;i<KEY_PREFIXES.length;i++){ if (k.indexOf(KEY_PREFIXES[i]) === 0) return true; }
    return false;
  }

  var NS_PRIVATE = hasCard ? ('mitienda:state:v2:'+cardId+':') : null;
  var NS_PUBLIC  = slug ? ('mitienda:public:v1:'+slug+':') : null;
  var NS = isPublic ? NS_PUBLIC : NS_PRIVATE;

  var L = window.localStorage, S = window.sessionStorage;
  function wrapStorage(storage){
    var get = storage.getItem.bind(storage);
    var set = storage.setItem.bind(storage);
    var rem = storage.removeItem.bind(storage);
    return {
      getItem: function(key){
        if (NS && needsNamespace(key)) { return get(NS+key); }
        return get(key);
      },
      setItem: function(key, val){
        if (isPublic) return; // never persist in public mode
        if (NS && needsNamespace(key)) { return set(NS+key, val); }
        return set(key, val);
      },
      removeItem: function(key){
        if (NS && needsNamespace(key)) { return rem(NS+key); }
        return rem(key);
      }
    };
  }
  try {
    window.__RAW_LS__ = L; window.__RAW_SS__ = S;
    window.localStorage = wrapStorage(L);
    window.sessionStorage = wrapStorage(S);
  } catch(e){}

  function saveMirror(obj){
    try { if (!NS || isPublic) return; window.__RAW_LS__.setItem(NS+'__mirror__', JSON.stringify(obj||{})); } catch(e){}
  }
  function loadMirror(){
    try { if (!NS) return {}; var s = window.__RAW_LS__.getItem(NS+'__mirror__'); return s?JSON.parse(s):{}; } catch(e){ return {}; }
  }

  function fetchPublic(cb){
    var u1 = '/api/mi-tienda/public/state/' + encodeURIComponent(slug||'');
    var u2 = '/api/card/state/' + encodeURIComponent(slug||'');
    var xhr = new XMLHttpRequest(); xhr.open('GET', u1, true);
    xhr.onreadystatechange = function(){ if (xhr.readyState===4){
      if (xhr.status===200){ cb(null, xhr.responseText); }
      else {
        var x2 = new XMLHttpRequest(); x2.open('GET', u2, true);
        x2.onreadystatechange = function(){ if (x2.readyState===4){
          if (x2.status===200) cb(null, x2.responseText); else cb(new Error('Public state 404'), null);
        }}; x2.send();
      }
    }}; xhr.send();
  }
  function fetchPrivate(cb){
    var u = '/user/api/mi-tienda/state' + (hasCard ? ('?card_id='+encodeURIComponent(cardId)) : '');
    var xhr = new XMLHttpRequest(); xhr.open('GET', u, true);
    xhr.withCredentials = true;
    xhr.onreadystatechange = function(){ if (xhr.readyState===4){ cb(xhr.status===200?null:new Error('state failed'), xhr.responseText); } };
    xhr.send();
  }
  function postPrivate(payload, cb){
    if (isPublic || !hasCard) return cb && cb(null);
    var u = '/user/api/mi-tienda/state' + (hasCard ? ('?card_id='+encodeURIComponent(cardId)) : '');
    var xhr = new XMLHttpRequest(); xhr.open('POST', u, true);
    xhr.setRequestHeader('Content-Type','application/json;charset=utf-8');
    xhr.withCredentials = true;
    xhr.onreadystatechange = function(){ if (xhr.readyState===4){ cb && cb(null); } };
    xhr.send(JSON.stringify(Object.assign({}, payload || {}, { card_id: cardId })));
  }

  function fromDOM(){
    var o = {}; try {
      document.querySelectorAll('[data-field]').forEach(function(el){
        var k = el.getAttribute('data-field'); if (!k) return;
        o[k] = (el.value != null ? el.value : el.textContent) || '';
      });
    } catch(e){} return o;
  }

  function safelyParse(json){ try { return JSON.parse(json||'{}'); } catch(e){ return {}; } }

  if (isPublic) {
    fetchPublic(function(err, text){
      var data = safelyParse(text); window.__MI_TIENDA_STATE__ = data;
      try { document.dispatchEvent(new CustomEvent('mi-tienda:state:loaded', {detail:data})); } catch(e){}
    });
  } else {
    fetchPrivate(function(err, text){
      var data = err ? loadMirror() : safelyParse(text);
      window.__MI_TIENDA_STATE__ = data || {}; saveMirror(window.__MI_TIENDA_STATE__);
      try { document.dispatchEvent(new CustomEvent('mi-tienda:state:loaded', {detail:window.__MI_TIENDA_STATE__})); } catch(e){}
    });
  }

  if (!isPublic && hasCard){
    var t=null; document.addEventListener('input', function(){
      clearTimeout(t); t = setTimeout(function(){
        var payload = Object.assign({}, window.__MI_TIENDA_STATE__||{}, fromDOM());
        saveMirror(payload); postPrivate(payload, function(){});
      }, 500);
    });
  }
})();