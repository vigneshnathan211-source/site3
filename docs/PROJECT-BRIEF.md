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
| 10 | Air Freight | `air-freight.php` | ✅ supplied verbatim |

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

Rebuilt 2026-08-19 against the client's own "1st email on HOME Page"
(see CONTENT.md's Homepage section for exactly what came from it), then
pruned the same day to only what that email (or "Email Our Fleet") actually
supplies, plus hero/services/CTA kept unconditionally:

1. Header — top info bar (phone · 24/7 line · email · address · socials) + sticky nav
2. Hero — headline, sub-headline, CTA, enquiry form or CTA pair
3. Credentials strip — both entities' names/registration numbers + ISO 9001:2015, bizSAFE 4, WCA Project cert links
4. **20-second video section** *(explicitly required, sits directly after the hero — kept even though no email describes it; see CONTENT.md)*
5. Partners marquee — client-named logo/wordmark strip
6. Services — five cards from the `services` table
7. Divisions — the two named in-house departments, plus a 4-item "CGS advantage" strip
8. Our Fleet teaser — real equipment specifics, linking to `our-fleet.php`
9. Project desk — five real named role/email contacts
10. CTA band — "Send us your packing list"
11. Footer — company blurb, quick links, service links, both offices

Core values, special services, the operations-gallery carousel and the FAQ
accordion (all old-site copy, not emails.txt content) were removed from
the homepage 2026-08-19 — see CONTENT.md's Homepage section for where they
might belong instead.

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

Four of ten pages have client copy, plus the homepage itself now has a real
client brief. See [`CONTENT.md`](CONTENT.md) for the full breakdown and
exactly what to request.

| Have | Missing |
|---|---|
| Project Freight Forwarding (full) | Tug & Barge, RoRo copy |
| Heavy Lift Chartering (full) | All five Resources topics (explainers + T&Cs doc) |
| Air Freight (full) | Homepage hero + about copy |
| Our Fleet (capability copy, no unit counts) | Verified logo artwork for 5 of 8 homepage partner names |
| Homepage (credentials, divisions, advantage, project-desk contacts) | |
| Company facts, from the old site | |
| 105 operations photos | |
| ISO 9001:2015, bizSAFE 4, WCA Project certs (real PDFs) | |
| Logo (JPEG), 20 s video | |

**Build with real structure and clearly-marked placeholder copy**, so the pages
are ready to receive text the moment it arrives. Do not invent facts about
capacity, tonnage, fleet size, certifications or client names — those are
verifiable claims a logistics buyer will check.

## Open questions for the client

1. **Page count.** The brief says "a total 9 page site"; the pages it lists come
   to ten (5 primary + 5 service). Is the Services overview page not counted, or
   is one of the five service pages being dropped?
2. **Resources structure.** One page with five sections (assumed), or five pages?
3. **Missing copy.** Tug & Barge, RoRo, Our Fleet, all five Resources topics,
   homepage. Should CGS write it, or is copywriting in scope?
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
13. **Partner/client logos for the homepage marquee.** Superseded 2026-08-19.
    The client's own "1st email on HOME Page" names the exact eight clients
    to show ("google the logo of following clients to insert them"): Sarens,
    Pageo, IKM Subsea, Skadi Offshore, Aster Chemical, Brooke Dockyard,
    Favelle Favco, Oilstates — a higher-confidence source than the old
    staging site's "Our Clients" section this list previously used
    (`https://zvv.cra.mybluehost.me/`, since `carriageglobal.com` itself
    serves the default unconfigured Hostinger page). Three names already had
    a downloaded logo file from that earlier pass (Sarens, IKM Subsea,
    Favelle Favco, still in `assets/img/partners/`); the other five —
    Pageo, Skadi Offshore, Aster Chemical, Brooke Dockyard, Oilstates —
    have no verified logo file and render as plain text wordmark tiles on
    the marquee instead of a guessed-at graphic for a real company's mark.
    **Open:** get real logo artwork (or explicit confirmation to keep the
    text treatment) for those five, and the client's sign-off that all
    eight relationships carry over to the new site before this goes live.
