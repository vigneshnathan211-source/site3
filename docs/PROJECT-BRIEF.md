# Project Brief — Carriage Global (S) Pte Ltd

Derived from [`docs/source/requirements.txt`](source/requirements.txt) and
[`docs/source/emails.txt`](source/emails.txt). Where the brief is ambiguous, the
reading taken here is stated explicitly and listed again under **Open questions**.

---

## What is being built

A brochure-and-enquiry website for a Singapore project-logistics company, with a
custom PHP admin dashboard so CGS can maintain content without a developer.

Modelled on the **Roofer** theme (`reference/roofer/`) — same stack, same visual
system, same admin pattern — rebuilt from scratch for CGS rather than edited in
place. See *Deviations from the Roofer theme* below for what changes.

## Sitemap

### Primary navigation

| # | Page | File | Notes |
|---|---|---|---|
| 1 | Home | `index.php` | Hero, then a 20-second video section, then services, fleet teaser, gallery, CTA |
| 2 | Our Fleet | `our-fleet.php` | Three blocks: fleet · in-house lashing · open yard for cargo storage and re-working |
| 3 | Services | `services.php` | Overview grid linking to the five detail pages; dropdown parent in the nav |
| 4 | Projects | `projects.php` | Replaced Resources in the nav on the client's instruction, 2026-08-17. Not built yet. |
| 5 | Contact Us | `contact.php` | Enquiry form, SG + MY offices, map |

> **Resources is currently unrouted.** `requirements.txt` specifies a Resources
> page with five topics, and the `resources` table and its seeds are still in
> `database/schema.sql`, but nothing links to it since Projects took its nav
> slot. Either it comes back somewhere (footer, or a child of Projects), or it
> is dropped and the table should go with it. See open question 11.

### Service detail pages (children of Services)

| # | Page | File | Copy status |
|---|---|---|---|
| 6 | Project Freight Forwarding | `project-freight-forwarding.php` | ✅ supplied verbatim |
| 7 | Chartering Heavy Lift & Semi-Geared Vessels | `heavy-lift-chartering.php` | ✅ supplied verbatim |
| 8 | Chartering Tug & Barge | `tug-and-barge-chartering.php` | ❌ not supplied |
| 9 | Roll On / Roll Off | `roll-on-roll-off.php` | ❌ not supplied |
| 10 | Air Freight | `air-freight.php` | ❌ copy missing (images supplied) |

### Supporting pages (not counted in the brief, still required)

`thankyou.php` (form success — the Google Ads conversion URL) · `404.php` ·
`submit-lead.php` (form handler, no UI).

### Resources page topics

1. CBM versus freight ton
2. Incoterms
3. Freight service liability insurance versus cargo insurance
4. Our certificates
5. CGS general terms and conditions

**Read as one page with five anchored sections**, not five separate pages — the
brief lists them inline under a single "Resources" nav item, and separate pages
would push the site well past the stated page count.

## Homepage structure

1. Header — top info bar (phone · 24/7 line · email · address · socials) + sticky nav
2. Hero — headline, sub-headline, CTA, enquiry form or CTA pair
3. **20-second video section** *(explicitly required, sits directly after the hero)*
4. Services — five cards from the `services` table
5. Our Fleet teaser — fleet · lashing · open yard, linking to `our-fleet.php`
6. Why CGS — ISO 9001:2015, sectors served, core values
7. Gallery strip — featured operations photography
8. CTA band — "Send us your packing list"
9. Footer — company blurb, quick links, service links, both offices

## Admin dashboard

Custom PHP, no WordPress. Login is email + password, then a 6-digit OTP emailed
via SMTP — carried over from the Roofer build, which already works this way.

| Screen | Manages | Table(s) |
|---|---|---|
| Dashboard | Lead counts, recent enquiries, quick stats | `leads` |
| Leads | View, filter, status pipeline, internal notes, CSV export | `leads` |
| Services | The five service pages and their content blocks | `services`, `service_sections` |
| Fleet | Fleet units, lashing, open yard | `fleet_items` |
| Resources | The five Resources topics + PDF uploads | `resources` |
| Certificates | ISO 9001:2015 and any others | `certificates` |
| Gallery | Upload, tag and sort operations photography | `gallery` |
| Page Content | Editable headings/copy on fixed pages | `page_blocks` |
| FAQs | Optional accordion entries | `faqs` |
| Settings | Logo, video, contact details for both offices, socials | `settings` |
| Users | Admin accounts | `users` |

Schema: [`database/schema.sql`](../database/schema.sql).

## Deviations from the Roofer theme

The Roofer build is the reference, not the target. These things change:

1. **Shared includes.** Roofer duplicates the entire `<head>`, header and footer
   into every page — ~50 KB per file, and a nav change means editing 15 files.
   CGS uses `includes/head.php`, `header.php`, `footer.php`, `scripts.php`, with
   `includes/bootstrap.php` doing the DB connection and shared queries once.
2. **No inline `<style>` blocks.** Roofer carries 450+ lines of inline CSS per
   page. CGS keeps overrides in `assets/css/cgs.css`.
3. **Brand.** Roofer is orange (`#F05A00`) on white; CGS is the logo's blue
   gradient — see the token table in `CLAUDE.md`.
4. **No blog, no projects module.** Neither appears in the CGS brief. The
   photography lives in a `gallery` table instead of Roofer's `projects` table.
5. **New modules** Roofer has no equivalent of: fleet, resources, certificates,
   page blocks, service sections.
6. **Two offices.** Roofer assumes one address; CGS needs Singapore and Johor
   Bahru throughout.
7. **Video-first homepage.** Roofer has no video section.
8. **Credentials are not committed.** Roofer's repo carries a live SMTP password
   and DB credentials in tracked files; CGS keeps placeholders in the tracked
   config and real values only on the server.
9. **Mobile nav is a dropdown, not an offcanvas.** Roofer's mobile menu is
   Bootstrap's `offcanvas` component — a full-height drawer sliding in from
   the screen edge. CGS's mobile menu (`.cgs-mobile-panel` in
   `includes/header.php`) drops down from the navbar itself instead, the same
   interaction language as the desktop Services hover dropdown next to it.
   Client-requested change, 2026-08-17.
10. **Colour palette additionally validated against Maersk.com**, not just the
    logo — deep navy as the primary fill, brighter blue reserved for accents,
    no warm colour. See the Brand section in `CLAUDE.md`.

## Content status

Two of ten pages have client copy. See [`CONTENT.md`](CONTENT.md) for the full
breakdown and exactly what to request.

| Have | Missing |
|---|---|
| Project Freight Forwarding (full) | Tug & Barge, RoRo, Air Freight copy |
| Heavy Lift Chartering (full) | Our Fleet (all three blocks) |
| Company facts, from the old site | All five Resources topics |
| 105 operations photos | Homepage hero + about copy |
| Logo (JPEG), 20 s video | ISO certificate scan, General T&Cs document |

**Build with real structure and clearly-marked placeholder copy**, so the pages
are ready to receive text the moment it arrives. Do not invent facts about
capacity, tonnage, fleet size, certifications or client names — those are
verifiable claims a logistics buyer will check.

## Open questions for the client

1. **Page count.** The brief says "a total 9 page site"; the pages it lists come
   to ten (5 primary + 5 service). Is the Services overview page not counted, or
   is one of the five service pages being dropped?
2. **Resources structure.** One page with five sections (assumed), or five pages?
3. **Missing copy.** Tug & Barge, RoRo, Air Freight, Our Fleet, all five
   Resources topics, homepage. Should CGS write it, or is copywriting in scope?
4. **Logo files.** Vector or transparent PNG, plus a white version for the dark
   footer. The supplied JPEG has a white background.
5. **Video.** Is the AI-generated clip final, or is real operations footage coming?
6. **Contact details.** Confirm every value transcribed from the old site,
   especially whether `angeline@carriageglobal.com` is the right public address
   and where enquiry emails should be routed.
7. **Johor Bahru office.** Own phone/email, or shares Singapore's?
8. **Domain and hosting.** Which domain, and is this deploying to the same
   Hostinger account as the Roofer site?
9. **Old-site services.** Cross Border Trucking, Dangerous Goods, Customized
   Packing, Door-to-Door and Sea Freight are on the old site but not in the new
   five. Three of them (Dangerous Goods, Door to Door, Customized Packing) now
   show as a "Special services" strip on the homepage (2026-08-18), verbatim
   old-site copy, no dedicated pages or links — still needs the client's call
   on whether that's enough or they warrant full service pages of their own.
   Cross Border Trucking and Sea Freight remain unaddressed.
10. **Analytics.** Reuse a GTM container, or set up a new one?
11. **Resources vs Projects.** Projects replaced Resources in the nav on
    2026-08-17. Is Resources dropped entirely, or does it move somewhere else?
    Its five topics were an explicit client requirement, and two of them
    (certificates, general T&Cs) are documents a logistics buyer looks for.
12. **What is a "project"?** Two readings, and they need different builds:
    a photo gallery of past moves (the existing `gallery` table already
    covers it), or written case studies with cargo, mode, route and outcome
    (needs a `projects` table and, more to the point, needs the client to
    approve naming the cargo and the client involved).
13. **Partner/client logos for the homepage marquee.** Requested 2026-08-18.
    `carriageglobal.com` itself serves the default unconfigured Hostinger
    page, not the old site; the client pointed to the real old site at
    `https://zvv.cra.mybluehost.me/` (its "Our Clients" section) instead.
    The homepage marquee (`index.php`, after Services) now uses those ten
    logos, downloaded to `assets/img/partners/`: Zodiac Milpro, IKM Subsea,
    MMA Offshore, Subsea 7, Sarens, ALE, Fugro, MacGregor, Favelle Favco,
    Louis Dreyfus Armateurs. Still needs the client to confirm these
    relationships carry over to the new site before this goes live — an old
    staging site is a reasonable source to build from, not a substitute for
    the client's own sign-off on which partners to name publicly.
