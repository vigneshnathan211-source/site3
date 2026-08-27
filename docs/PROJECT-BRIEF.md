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
| 4 | Resources | `resources.php` | Reinstated in the nav 2026-08-22 (client reply, emails.txt: nav should read "HOME- OUR FLEET- SERVICES- RESOURCES- CONTACT US"), reversing the 2026-08-17 swap to Projects. Built 2026-08-24 from the client's "Fwd: CGS website 1" email — see CONTENT.md's Resources page section for what it supplied and how each topic is laid out. |
| 5 | Past Projects | `past-projects.php` | Added to the nav 2026-08-24 (client, "HOME PAGE and VIDEO REVIESD 1" thread: "f) Past projects (upload all the pictures given via email, zip, w/app avoid repetition)"). Photo gallery built on the existing `gallery` table — see open question 12, now resolved. |
| 6 | Contact Us | `contact.php` | Enquiry form, SG + MY offices, map |

> Resources is back in both the header nav (`includes/header.php`) and footer
> (`includes/footer.php`) as of 2026-08-22; the `resources` table and its
> seeds in `database/schema.sql` are no longer orphaned. Open question 11
> (below) is resolved by this — Projects is dropped from the nav entirely,
> not moved elsewhere. Past Projects (a gallery page, not case studies) was
> added back in as its own nav item 2026-08-24 — see open question 12.

### Service detail pages (children of Services)

| # | Page | File | Copy status |
|---|---|---|---|
| 7 | Project Freight Forwarding | `project-freight-forwarding.php` | ✅ supplied verbatim — built 2026-08-26 |
| 8 | Chartering Heavy Lift & Semi-Geared Vessels | `heavy-lift-chartering.php` | ✅ supplied verbatim |
| 9 | Chartering Tug & Barge | `tug-and-barge-chartering.php` | ✅ supplied verbatim (client's "webpage -3rd email on Tug and Barge charter") — this row previously read "not supplied"; that was stale. Built 2026-08-26. |
| 10 | Roll On / Roll Off | `roll-on-roll-off.php` | ✅ supplied verbatim (client's "webpage -4th email Roll on-Roll off") — this row previously read "not supplied"; that was stale. Built 2026-08-26. |
| 11 | Air Freight | `air-freight.php` | ✅ supplied verbatim — page/copy kept, but `services.status` set to `inactive` 2026-08-24 (client's "finalized" menu list that day dropped it from the Services nav; see `database/schema.sql`). Not counted in the primary nav below until reactivated. Still not built (client explicitly deprioritised it 2026-08-26, alongside the Services overview page, when the three remaining service pages above were built). |

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

**Built 2026-08-24**, content supplied in full via the client's "Fwd: CGS
website 1" email (originally sent 2026-08-14, forwarded to the build team
2026-08-19) and confirmed back into the sitemap the same day as this build
("HOME PAGE and VIDEO REVIESD 1" thread: "e) Resources... page is given").
See CONTENT.md's Resources page section for exactly what was supplied and
how it maps onto the four rendered sections (CBM/Freight Ton content is
client-verbatim; Incoterms, Insurance and Chargeable Weight are original
copy the client explicitly asked NOT be copied from the reference links she
sent — "we do not want to copy but i need you to re write"). The five
topics above collapse to four DB `resources` categories on the page:
Incoterms and Insurance share a "Shipping Essentials" tab group with
Chargeable Weight Calculation (the client's own instruction: "I want to
show big tabs such as Incoterms... and third tab chargeable weight
calculation" — chargeable weight was not one of the original five topics
but arrived in the same email as an explicit third tab). General T&Cs is
joined in its section by three policy PDFs that arrived as attachments on
the same email (Code of Conduct, Alcohol & Drug Policy, Environmental
Policy Statement) — not separately briefed topics, but grouped in because
the client attached and referenced them together ("Certificates / General
terms and conditions as attached / Code of conduct / Alcohol and Drug
policy / Environmental policy statement. All attached.").

**Revised 2026-08-24** on client feedback ("I want certifications to be
shown not just logo and move it as first section. Total page looks so much
content oriented add images, elements vectors etc."): the page order is now
Certificates, Cargo Measurement, Shipping Essentials, Terms & Conditions —
Certificates moved from third to first, its cards now show the full scanned
document plus issuer/cert number/validity instead of a small badge, and the
other three sections each gained a real photo or a decorative accent so the
page reads as less of a text wall. See CONTENT.md's Resources page section
for the per-certificate facts and their source.

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
3. ~~**Missing copy.**~~ Resolved: Our Fleet, all five Resources topics, and
   the homepage all now have client-supplied copy and are built (see their
   own pages' header comments). Tug & Barge and RoRo copy also turned out to
   already be supplied — see item 9/10 in the sitemap table above — so
   nothing is still missing here.
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
11. ~~**Resources vs Projects.**~~ Resolved 2026-08-22: client's reply gave
    the nav order explicitly as Home / Our Fleet / Services / Resources /
    Contact Us — Projects is out, Resources is back, in both header and
    footer nav.
12. ~~**What is a "project"?**~~ Resolved 2026-08-24: client's "pages and sub
    pages" list names it "Past projects (upload all the pictures given via
    email, zip, w/app avoid repetition)" — reading #1, a photo gallery of
    past moves. Built as `past-projects.php` on the existing `gallery`
    table, added to the nav after Resources. No `projects` table, no
    written case studies, no client/cargo naming approval needed.
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
14. **Social page URLs.** Client asked 2026-08-22 to link the Facebook,
    LinkedIn and YouTube icons in the header/footer to CGS's actual channels
    ("very common function in today's website") but hasn't sent the URLs.
    `settings.facebook_url` / `linkedin_url` / `youtube_url` are seeded with
    a `#` placeholder so the icons are visible and wired up now — swap in
    the real URLs (via the admin Settings screen, or a fresh `database/
    schema.sql` default) the moment the client sends them.
15. **Past Projects video IDs.** Client (Angeline, 2026-08-24) asked to link
    "our YouTube videos" (plural) on the Past Projects page. Built as a
    ready multi-video grid (`$pastVideos` in `past-projects.php`) that
    plays in an in-page lightbox the moment a card has a real
    `youtube_id`. One confirmed 2026-08-24:
    `https://www.youtube.com/watch?v=1EcNNqCrtwc` ("398 TON Knuckle Crane
    Installation") is wired to the "Chartering Heavy Lift and
    Semi-Geared Vessels" card. **Still open:** video links for the other
    two categories (Chartering of Tug and Barges, Project Freight
    Forwarding) — those cards still show "Coming Soon" and fall back to
    the `youtube_url` placeholder from #14. Also worth confirming with
    the client: the supplied video is hosted on a personal-looking
    channel ("ravi shankar", 2 subscribers) rather than an official
    Carriage Global channel — flag this so they can swap in the
    corporate channel's link if one exists.
16. **Roll On/Roll Off has no photography at all.** ASSET-INVENTORY.md's
    "coverage gaps" note already flagged this; confirmed again while
    building `roll-on-roll-off.php` 2026-08-26. The page currently has no
    gallery section as a result (Project Freight Forwarding and Tug &
    Barge both borrow real photos from the `gallery` table's matching
    category; RoRo's category has zero rows). Ask the client for RoRo-
    specific photos, or confirm whether any of the 80 unsorted
    `client_assets/pic/` WhatsApp exports show RoRo operations once that
    folder is categorised.
17. **Tug & Barge page video.** The client's "webpage -3rd email on Tug and
    Barge charter" says a YouTube video can go on this page ("try to mute
    sound") but no link was supplied. Not built into
    `tug-and-barge-chartering.php` yet — ask for the link.
