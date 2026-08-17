# Asset Inventory

Everything the client supplied lives in `client_assets/` and is treated as
**read-only source material**. Nothing there is web-ready: files keep their
camera/WhatsApp names, are full-resolution, and are not compressed. Optimised
copies go into `assets/img/…` (build-time) or `uploads/` (uploaded through the
admin).

---

## 1. Logo

`client_assets/cgs-01.jpg.jpeg` — 540 × 540 JPEG. Blue-gradient "CGS" monogram
on a **white background**.

Copied to `assets/img/logo/cgs-logo.jpg` and `assets/img/logo/cgs-favicon.jpg` so
the build has something to render, but both are placeholders:

- **Ask the client for a vector (SVG/AI/EPS) or transparent PNG.** A white-boxed
  JPEG cannot sit on the dark navy footer or on a photographic hero.
- A **knockout/white version** is needed for the footer (`settings.logo_light`).
- The favicon should be a square PNG (32/180/512 px), not a JPEG.
- The monogram alone has no wordmark. Confirm whether the header should pair it
  with "Carriage Global (S) Pte Ltd" set in type.

Brand colours in `assets/css/cgs.css` were sampled from this file. The JPEG
carries a colour profile that shifts the raw pixel values, so the tokens are
eyeball-matched to the rendered logo, then additionally tuned against
Maersk.com's real, live-sampled palette (2026-08-17) as industry validation for
the deep-navy / bright-accent-blue / no-warm-colour direction — see the Brand
section in `CLAUDE.md` for the exact values and reasoning. **Re-derive them from
the vector** when it arrives.

## 2. Video

`client_assets/kling_20260817_VIDEO_Cinematic__3110_0.mp4` — 6.2 MB, AI-generated
("kling"), cinematic. Copied to `assets/video/cgs-home-intro.mp4`.

This is the 20-second post-hero video band the brief asks for. Before go-live:

- Confirm with the client that an AI-generated clip is acceptable, given the rest
  of the site is real operations photography. Real footage would be stronger.
- Transcode to a web profile (H.264 baseline, ~2 Mbps) plus a WebM fallback, and
  export a poster frame — 6.2 MB is heavy for an autoplaying band.
- Autoplay must be `muted` + `playsinline` or mobile browsers will block it.

## 3. Operations photography — 105 images

### `client_assets/pic/` — 80 images (16 MB)

Unlabelled WhatsApp exports from 2026-08-14. Spot-checked content: heavy-lift
crane operations at port, oversized cargo on CGS-branded low-bed trailers,
geared-vessel and barge loading alongside, tugs, lashed project cargo. CGS
branding ("CARRIAGE GLOBAL (S) PTE LTD · TEL: 6515 6106", the CGS logo on
trailers) is visible in several frames, which makes these the strongest
hero/gallery candidates.

**These need categorising before they are usable.** WhatsApp filenames carry no
meaning, so someone has to sort them into: Heavy Lift · Barge & Tug · RoRo ·
Lashing · Yard · Air Freight · General. That mapping belongs in the `gallery`
table (`category`, `service_id`), loaded via the admin gallery uploader.

### `client_assets/email-assets/` — 25 images (5.4 MB), pre-labelled by email

| Folder | Count | Maps to |
|---|---|---|
| `Fw_ Breakbulk chartering -1 pics/` | 5 | Heavy Lift & Semi-Geared Chartering |
| `Fw_ Breakbulk pics_/` | 7 | Heavy Lift & Semi-Geared Chartering |
| `Fw_ Breakbulk pictures 2 email/` | 6 | Heavy Lift & Semi-Geared Chartering |
| `Fw_ Services page by Air Page/` | 7 | Air Freight |

The three breakbulk folders are the client's own selection for the heavy-lift
service page — use those first. The air-freight folder is the only imagery
supplied for that page, and it arrived **without any copy**.

### Coverage gaps

No images are labelled for **Tug & Barge Chartering**, **Roll On / Roll Off**,
**In-house Lashing**, or the **Open Yard**. Some will be findable inside
`pic/` once it is sorted; anything still missing must be requested.

---

## 4. Image pipeline

1. Sort and rename source files descriptively (`heavy-lift-vessel-loading-01.jpg`).
2. Resize to the largest size actually rendered — 1920 px wide for heroes,
   1200 px for content, 800 px for cards. Nothing needs the raw 4000 px.
3. Convert to WebP with a JPEG fallback; target < 200 KB for heroes, < 100 KB
   for cards.
4. Strip EXIF (these are phone photos and carry GPS coordinates).
5. Build-time imagery → `assets/img/<section>/`. Client-managed imagery →
   `uploads/gallery/` via the admin.
6. Every `<img>` gets a real `alt`, `width`, `height`, and `loading="lazy"`
   below the fold.

## 5. Reference theme assets

`assets/css/` and `assets/js/` were copied from the Roofer theme
(`reference/roofer/`): Bootstrap 5, AOS, Owl Carousel, Slick, Magnific Popup,
Nice Select, FontAwesome (+ woff2 fonts), jQuery 3.7.1, and the theme's
`main.css` / `main.js`.

`main.css` is 650 KB and contains the entire Roofer design system. It is the base
layer; **CGS overrides go in `assets/css/cgs.css`, never by editing `main.css`** —
keeping it pristine means the theme's components stay predictable and the
override file stays reviewable.

**FontAwesome is missing two of its four weights.** `assets/css/fonts/` has
`fa-solid-900.woff2`, `fa-regular-400.woff2`, `fa-brands-400.woff2` and
`fa-v4compatibility.woff2` — but `fontawesome.css` is the full Pro 6.4.2
stylesheet, which defines classes for weights (`fa-light`, `fa-thin`,
`fa-duotone`) that have no matching woff2 file here. `fa-light` in particular
renders as invisible/missing-glyph boxes. Confirmed separately: even
`fa-regular` only covers a small icon subset — most glyphs beyond the ones
already in use (`fa-regular fa-envelope` etc.) render as tofu boxes too,
because the bundled `fa-regular-400.woff2` is a trimmed subset, not the full
Pro cut. **Stick to `fa-solid` unless a specific `fa-regular` icon has already
been verified to render**, or source the missing woff2 files from a full Pro
license export.

The Roofer theme's own photography (`reference/roofer/assets/img/all-images/`) is
roofing-specific and must **not** ship. Only its `icons/` SVGs were carried over,
and even those should be swapped for logistics-appropriate icons.
