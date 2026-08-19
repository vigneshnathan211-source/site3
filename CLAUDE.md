# CLAUDE.md

Guidance for Claude Code working in this repository.

## Project

Website + custom CMS for **Carriage Global (S) Pte Ltd** (CGS) — a Singapore
project-logistics and freight-forwarding company (ISO 9001:2015, UEN 200714170K,
with a second office in Johor Bahru). Ten pages plus a PHP admin dashboard.

Being **built from scratch in this root folder**. The Roofer theme in
`reference/roofer/` is the visual and architectural model — read from it, copy
patterns and assets out of it, but never edit it and never let its content (roofing
copy, orange branding, blog/projects modules) leak into the build.

Three documents carry the detail. Read them before making decisions:

| Document | What it settles |
|---|---|
| [`docs/PROJECT-BRIEF.md`](docs/PROJECT-BRIEF.md) | Sitemap, page-by-page scope, admin modules, deviations from the Roofer theme, open questions |
| [`docs/CONTENT.md`](docs/CONTENT.md) | Every line of client-supplied copy, verbatim, and exactly which pages have none |
| [`docs/ASSET-INVENTORY.md`](docs/ASSET-INVENTORY.md) | The 105 photos, the logo, the video, and what each is fit for |

Raw client material is preserved in `docs/source/` and `client_assets/`.

## Stack

PHP 8 · MySQL/MariaDB (PDO) · Bootstrap 5 · jQuery 3.7.1 · PHPMailer (SMTP).
No build step, no package manager, no framework — files are served as-is.
Deploys to Hostinger shared hosting.

Front-end plugins actually wired into `includes/head.php` / `scripts.php`: AOS,
Owl Carousel, Magnific Popup, Swiper, FontAwesome (self-hosted woff2), plus
Bootstrap's own JS. The Roofer theme's `main.css` / `main.js` and the Slick /
Nice Select plugins still sit in `assets/css/plugins` and `assets/js/plugins`
but are not linked from any page — a file existing there doesn't mean it's
active; check `head.php` and `scripts.php` before assuming a plugin is loaded.

## Layout

```
/                            web root
├── index.php                home — hero, then the 20s video band, then services
├── our-fleet.php            fleet · in-house lashing · open yard
├── services.php             overview grid
├── resources.php            5 anchored sections
├── contact.php
├── <service>.php            5 flat service pages, filenames match services.link
├── thankyou.php             form success — the Google Ads conversion URL
├── 404.php
├── submit-lead.php          shared form handler for every form on the site
├── includes/                bootstrap · head · header · footer · scripts
├── admin/
│   ├── config/              db.php · mail_config.php  (placeholders only)
│   ├── includes/            shared admin helpers
│   └── PHPMailer/
├── assets/
│   ├── css/                 main.css (theme, unused) · cgs.css (brand, active) · plugins/ · fonts/
│   ├── js/                  main.js (theme, unused) · cgs.js (site, active) · plugins/
│   ├── img/                 logo/ hero/ services/ fleet/ gallery/ icons/ bg/ resources/
│   └── video/               cgs-home-intro.mp4
├── uploads/                 admin-uploaded media — writable, gitignored
├── database/schema.sql      full schema + seeds, re-runnable
├── docs/                    brief · content · assets · source/
├── client_assets/           client-supplied originals — READ ONLY
└── reference/roofer/        reference theme + its SQL dump — READ ONLY
```

## Architecture

Every public page's first line is `require_once __DIR__ . '/includes/bootstrap.php';`,
which does all the real setup — nothing else does:

- Starts the session, opens `$pdo` (via `admin/config/db.php`), and derives
  `BASE_URL` from `$_SERVER['SCRIPT_NAME']` so the site works unedited at a
  domain root or in a subfolder.
- Defines the helpers templates use throughout: `e()` (escape), `url()`
  (root-relative + escaped), `current_page()` / `nav_active()` (nav
  highlighting), `tel_link()`, `excerpt()`, and `db_all()` — a query wrapper
  that logs and returns `[]` instead of throwing when a table doesn't exist
  yet, so pages still render during setup, before `schema.sql` has been
  imported.
- Loads `$settings` (single-row table) and fills any NULL/missing column from
  `$settingDefaults` in a loop (not `+=` — every column already exists once
  the row does, just possibly NULL, and `+=` only fills keys that are
  *absent*), so header/footer/hero render sensible copy before the client has
  touched the admin. Loads `$services` (active, sorted) once for the nav
  dropdown, mobile menu and footer to share.
- Derives `$phoneTel`, `$phone247Tel`, `$whatsappLink`, `$socialPlatforms`
  from `$settings`, so no template re-parses a phone number or checks which
  social links are configured.

Schema tables (`database/schema.sql`): `users`, `settings`, `hero_slides`,
`services`, `service_sections`, `fleet_items`, `resources`, `certificates`,
`gallery`, `page_blocks`, `faqs`, `leads`.

`landing.php` is a standalone design concept ("Hyer"), not part of the
day-to-day build — it deliberately skips `head.php` / `header.php` /
`footer.php` and loads its own `assets/css/landing-hyer.css` /
`assets/js/landing-hyer.js` instead of `cgs.css`, scoped under
`body.page-hyer`. It still calls `includes/bootstrap.php` for
`$settings`/`$services`. Don't "fix" it to match the shared-includes rule
below — staying separate is the point of it.

## Conventions

**Shared includes, always.** Every page starts with
`require_once __DIR__ . '/includes/bootstrap.php';` and pulls in
`includes/head.php`, `header.php`, `footer.php`, `scripts.php`. The Roofer theme
duplicates the whole head/header/footer into all 15 of its pages — that mistake
is the main thing this build exists to avoid. If you find yourself pasting nav
markup into a second file, stop and put it in an include.

**Escape on output.** Every value that reaches HTML goes through
`htmlspecialchars()` — or the `e()` helper in `bootstrap.php`. The only exception
is admin rich-text (`service_sections.body`, `resources.content`,
`page_blocks.body`), which is stored as HTML and must be sanitised on the way
**in**, at save time, never trusted on the way out.

**Prepared statements only.** PDO with bound parameters, no string interpolation
into SQL. `PDO::ATTR_EMULATE_PREPARES` is off.

**CSS goes in `cgs.css`.** Never edit `main.css` — it's not even linked from
any page (see Stack), kept only as a copy-from reference, so changing it would
have zero effect and just cost the next person time figuring that out. Never
write inline `<style>` blocks in pages either — Roofer has 450+ lines of
inline CSS per page; do not reproduce that. Use the `--cgs-*` custom
properties rather than hard-coded hex values.

**Content comes from the database, not from markup.** Services, fleet items,
resources, gallery, FAQs and the editable page copy all live in tables so the
client can change them from the admin. Hard-coding a service name into a nav or
footer defeats the CMS.

**Root-relative paths.** Use the `BASE_URL` constant, not `../` chains — service
pages sit at the root today but may move.

## Brand

Colours are defined once as custom properties in `assets/css/cgs.css`.

| Token | Value | Use |
|---|---|---|
| `--cgs-navy-900` | `#082238` | Primary buttons, top bar, footer, dark sections |
| `--cgs-navy-700` | `#0E3A56` | Primary button hover |
| `--cgs-blue-500` | `#146B96` | Links, active nav |
| `--cgs-cyan-400` | `#2E9FD6` | Accents, icons, focus rings |
| `--cgs-ink` | `#0E1726` | Headings and body text |

Type: **Plus Jakarta Sans** (Google Fonts), as in the Roofer build. Button radius
is 4px (`--cgs-radius`).

These started eyeball-matched to the rendered logo, because the supplied JPEG
carries a colour profile that shifts its raw pixel values — always a stopgap
pending a vector. They're now additionally tuned against Maersk.com's real,
live-sampled palette (2026-08-17: navy `rgb(0,36,61)`, accent blue
`rgb(66,176,213)`, link blue `rgb(0,115,171)`, 4px button radius) — not copied,
but used as industry validation that a deep-navy-primary / brighter-blue-accent
hierarchy with **no warm colour at all** is the right direction for a shipping
and logistics brand. The former `--cgs-accent` amber token (flagged provisional
here previously) is dropped for exactly that reason; anything that used it now
reads `--cgs-cyan-400`. **Still re-derive these properly from a vector when the
client provides one** — the logo is currently a white-background JPEG, so it
cannot yet sit on the dark footer.

Primary buttons fill with the dark navy, not the brighter blue — matching
Maersk's own hierarchy, where the accent blue never appears as a button fill,
only as a link colour and small UI accents. Keep that hierarchy: one confident
"this is the action" colour, not the mid-blue doing double duty as both a link
colour and a button fill.

The main navbar's mobile menu is a **dropdown that expands from the navbar
itself** (`.cgs-mobile-panel` in `includes/header.php`), the same interaction
language as the desktop Services hover dropdown next to it — not a side-drawer
or offcanvas panel. This was a deliberate call away from the Roofer reference,
which uses Bootstrap's offcanvas for its mobile menu; ours does not.

## Content rules

Only three of ten pages have client copy (Project Freight Forwarding, Heavy Lift
Chartering, and Air Freight; all three are reproduced verbatim in
`docs/CONTENT.md`).

Build the remaining pages with real structure and **visibly marked placeholder
copy**, so they are ready the moment text arrives. Do not invent facts — fleet
size, tonnage, capacities, years in business, certifications, client names and
project references are all claims a logistics buyer will verify. When a page
needs a number nobody supplied, leave a marked placeholder and add it to the
open-questions list in `docs/PROJECT-BRIEF.md`.

Client copy is written in US English ("analyze", "specialized"). Keep it as
written; use Singapore conventions for anything newly authored, and keep one
convention per page.

## Security

- `admin/config/db.php` and `mail_config.php` are tracked with **placeholders**.
  Real credentials go on the server only, and are never committed. (The Roofer
  reference build has a live SMTP password in a tracked file — do not copy that
  pattern along with the code.)
- Admin auth is email + password (`password_hash`) followed by a 6-digit OTP
  emailed over SMTP. Every admin page checks the session before rendering
  anything.
- Uploads: validate MIME type and extension against an allow-list, cap file size,
  rename to a generated filename, and store outside the executable path where
  possible. Never trust `$_FILES['...']['type']`.
- Forms need a honeypot field and server-side validation; HTML5 `required` is not
  validation.
- `error_reporting`/`display_errors` stay off in production. `db.php` logs the
  driver message and shows the visitor a generic 503.

## Working on this repo

- `reference/roofer/` and `client_assets/` are read-only inputs. Copy out of
  them; do not modify them.
- `database/schema.sql` is re-runnable (`CREATE TABLE IF NOT EXISTS`,
  `INSERT IGNORE`). Schema changes go in that file, not in ad-hoc migrations —
  there is no migration tool.
- Local development: PHP 8 + MySQL, served locally as `http://site3.test/`
  (Herd/Valet-style `.test` domain) or `localhost`; `db.php` detects which by
  hostname and switches credentials accordingly, so no file edit is needed to
  deploy.
- There is no test suite, no linter, and no build step. Verify changes by
  running `php -l <file>.php` (catches syntax errors) and then loading the
  page — there's nothing else to run.
- `main` is on GitHub (`origin`); work happens on feature branches
  (`redesign/*`, `concept/*`) merged into `redesign/home`. `.gitignore`
  excludes `client_assets/`, `reference/`, `uploads/` and local config.
