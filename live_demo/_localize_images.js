/* One-off builder: replace blocked external Unsplash hotlinks in the new
   live-demo HTML files with self-contained inline SVG gradient placeholders,
   so the demos render on any network with no external domain dependency.
   Run: node _localize_images.js
   Safe to re-run (idempotent — only replaces https://images.unsplash.com/...). */
const fs = require('fs');
const path = require('path');

// Art-directed palettes per demo vibe (pairs: dark -> light gradient)
const PALETTES = {
  'hotel.html': [
    ['#b0876a', '#e6cdb2'], ['#2c2420', '#6b5545'], ['#8a9a7b', '#d3ddc7'],
    ['#c98a5e', '#efd0a8'], ['#4a3b32', '#a08d78'], ['#b85a4a', '#e6b0a0'],
    ['#7a6a55', '#cdbda3'], ['#3a4a52', '#9db4bd']
  ],
  'residence.html': [
    ['#201d18', '#5a5346'], ['#a88d68', '#3a3226'], ['#2f3b33', '#7b8d7e'],
    ['#4a3f2a', '#9c8355'], ['#20242a', '#5a6672'], ['#5a4a30', '#b09763'],
    ['#2a2a2a', '#616161'], ['#37312a', '#7a6a52']
  ],
  'agency.html': [
    ['#d4815a', '#f2cca9'], ['#17140f', '#4a4038'], ['#8a6436', '#ccab7f'],
    ['#2f4030', '#82a286'], ['#c98a5e', '#eecfa9'], ['#3a3a55', '#8585a8'],
    ['#b48a7a', '#e6cdbe'], ['#5a3f2a', '#b09070']
  ]
};

const BRAND = { 'hotel.html': 'Casa Sala', 'residence.html': 'Arbor Living', 'agency.html': 'FORMA' };

function makeSvg(c1, c2, brand, w, h) {
  const svg =
`<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}" viewBox="0 0 ${w} ${h}">`
    + `<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">`
    + `<stop offset="0" stop-color="${c1}"/><stop offset="1" stop-color="${c2}"/>`
    + `</linearGradient></defs>`
    + `<rect width="${w}" height="${h}" fill="url(#g)"/>`
    + `<circle cx="${w * 0.72}" cy="${h * 0.32}" r="${h * 0.34}" fill="rgba(255,255,255,0.10)"/>`
    + `<circle cx="${w * 0.2}" cy="${h * 0.8}" r="${h * 0.22}" fill="rgba(0,0,0,0.10)"/>`
    + `<path d="M0 ${h} L${w} 0" stroke="rgba(255,255,255,0.08)" stroke-width="${h * 0.02}"/>`
    + `<text x="${w * 0.05}" y="${h * 0.9}" font-family="Georgia,serif" font-size="${Math.round(h * 0.05)}" fill="rgba(255,255,255,0.9)">&#9670; ${brand}</text>`
    + `</svg>`;
  return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
}

function localize(file) {
  const full = path.join(__dirname, file);
  let html = fs.readFileSync(full, 'utf8');
  const pals = PALETTES[file];
  const brand = BRAND[file];
  let i = 0;
  const before = html;
  // Replace every unsplash url whether in src="..." or JS '...' — match up to quote/paren/space
  html = html.replace(/https:\/\/images\.unsplash\.com\/[^"'()\s]+/g, () => {
    const p = pals[i % pals.length];
    // hero/break images (w=1600/1900/1200) get 16:9 tiles, cards get 4:3
    const wide = p && true;
    const uri = makeSvg(p[0], p[1], brand, 1200, (i % 3 === 0 ? 800 : 900));
    i++;
    return uri;
  });
  if (html !== before) {
    fs.writeFileSync(full, html, 'utf8');
    console.log(`✓ ${file}: replaced ${i} unsplash hotlinks with inline SVG placeholders`);
  } else {
    console.log(`· ${file}: nothing to replace`);
  }
}

['hotel.html', 'residence.html', 'agency.html'].forEach(localize);
