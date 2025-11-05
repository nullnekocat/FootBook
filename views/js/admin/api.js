// /assets/js/admin/api.js
export async function getJSON(url, opts={}) {
  const res = await fetch(url, { method:'GET', ...opts });
  const txt = await res.text();
  let data; try { data = JSON.parse(txt); } catch { throw new Error(txt.slice(0,300)); }
  if (!res.ok) throw new Error(data.error || ('Error '+res.status));
  return data;
}

export async function postJSON(url, body, opts={}) {
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type':'application/json' },
    body: JSON.stringify(body),
    ...opts
  });
  const txt = await res.text();
  let data; try { data = JSON.parse(txt); } catch { throw new Error(txt.slice(0,300)); }
  if (!res.ok) throw new Error(data.error || ('Error '+res.status));
  return data;
}
