// public/user-ui/js/mi-tienda-bridge.js
// This file connects your custom UI DOM to the Laravel endpoints.
// Example wiring: finds elements by data-attrs and POSTs to /user/cards

(function () {
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $all(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

  // Map fields (edit as needed to match your DOM)
  const map = {
    title: '[data-card="title"]',
    slug: '[data-card="slug"]',
    name: '[data-card="name"]',
    job_title: '[data-card="job"]',
    company: '[data-card="company"]',
    phone: '[data-card="phone"]',
    email: '[data-card="email"]',
    website: '[data-card="website"]',
    bio: '[data-card="bio"]',
  };

  function collectPayload() {
    const payload = {};
    Object.entries(map).forEach(([key, sel]) => {
      const el = $(sel);
      if (el) payload[key] = el.value || el.textContent || '';
    });

    // Example: collect socials from links
    const socials = {};
    $all('[data-social]').forEach(a => {
      const k = a.getAttribute('data-social');
      const v = a.value || a.getAttribute('href') || '';
      if (k && v) socials[k] = v;
    });
    payload.social_links = socials;

    return payload;
  }

  async function postJSON(url, data, method = 'POST') {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token || ''
      },
      body: JSON.stringify(data)
    });
    if (!res.ok) {
      const t = await res.text();
      throw new Error(t || ('HTTP ' + res.status));
    }
    return res.json();
  }

  // Create card
  const createBtn = $('[data-action="save-card"]');
  if (createBtn) {
    createBtn.addEventListener('click', async () => {
      try {
        const payload = collectPayload();
        const resp = await postJSON('/user/cards', payload, 'POST');
        if (resp?.card?.id) {
          window.location.href = `/user/cards/${resp.card.id}/edit`;
        } else {
          alert('Saved, but no card returned');
        }
      } catch (e) {
        console.error(e);
        alert('Save failed: ' + e.message);
      }
    });
  }

  // Update card (if page embeds card id in a tag)
  const updateBtn = $('[data-action="update-card"]');
  if (updateBtn) {
    const cardId = updateBtn.getAttribute('data-card-id');
    updateBtn.addEventListener('click', async () => {
      if (!cardId) return alert('No card id');
      try {
        const payload = collectPayload();
        const resp = await postJSON(`/user/cards/${cardId}`, payload, 'POST'); // method spoof handled by middleware if needed
        if (resp?.ok) {
          alert('Updated');
        }
      } catch (e) {
        console.error(e);
        alert('Update failed: ' + e.message);
      }
    });
  }
})();
