<?php
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
| Section order and the layout family each one uses. The families are all
| different on purpose: eight near-identical card rows is what makes a page
| read as templated.
|
|   1. Hero                full-bleed video (the old Video Band's footage),
|                          copy on the left, black scrim, no CTA buttons
|   2. Our Story            two columns, each its own photo + paragraph —
|                          left closes on a 2x2 stat grid (real facts
|                          only), right opens on an eyebrow + heading
|   3. Mission & Vision     "Waypoint" — dark navy band, two waypoints on
|                          one plotted course, one porthole photo each
|                          (not repeated between the two)
|   4. Services             asymmetric bento, 5 cells for 5 services
|   5. The CGS Advantage    tinted band, 6-item icon grid
|   6. Testimonials         3-up quote wall, real named clients
|   7. Partners             logo marquee, continuous auto-scroll
|   8. Project desk         role-based contact grid
|   9. CTA                  rounded sea-blue card, inset on white
|
| 2026-08-21 "Home Page redesign" (client, docs/source/Home page CGS.docx +
| docs/source/emails.txt): rebuilt around the client's own docx content.
| Video band, the old "Unrivaled Control" Divisions section (with its
| 4-item advantage strip) and, as of a same-day follow-up ("remove
| credentials section"), Certifications too, are all hidden via if (false)
| below rather than deleted — same convention as every other client
| "remove X" request on this page (Fleet teaser, Accent CTA, Gallery) —
| markup/CSS left in place in case any comes back. The ISO/WCA/bizSAFE
| facts Certifications used to show aren't lost: they're now 3 of the 4
| stats in Our Story's stat grid. Our Story itself was rebuilt a second
| time the same day to match a layout the client referenced directly
| (reference/roofer/about.php's "About" section) — two columns, photo +
| paragraph + stats on the left, eyebrow + heading + paragraph + photo on
| the right — with every stat cell a real sourced fact rather than that
| reference's own invented placeholder counters. Mission & Vision and the
| re-scoped 6-item CGS Advantage are also sourced verbatim from the docx.
| Testimonials is new, the same source's 3 real client quotes (2 more added
| 2026-08-22 from the client's follow-up reply). Services,
| Partners, Project desk and the closing CTA are unchanged structurally,
| just reordered around the new sections.
|
| Same-day follow-up ("in hero replace image with the old video section
| video and overlay text and left bottom corner remove those two button"):
| the Hero's per-slide photograph was replaced with the Video Band's
| footage, playing continuously behind the whole carousel (not restarted
| per slide) with the same dark overlay and slide copy on top; the
| "Get a Quote" / "Our Services" button pair was removed from every slide.
|
| Second same-day follow-up ("add it above our service"): Mission & Vision
| was redesigned around a nautical wayfinding concept prototyped and
| approved as a standalone artifact first — see cgs-waypoint below — and
| moved from after Services to before it. The old paired-card layout
| (.cgs-pillars) is hidden via if (false), not deleted, same convention as
| the rest of this file; $pillars now feeds both markups, though only the
| live one reads its new 'marker' key.
|
| Copy status: Our Story, Mission, Vision, the 6-item CGS Advantage and the
| first 3 testimonials are verbatim from docs/source/Home page CGS.docx (the
| client's "Home page CGS" file); the 2 later testimonials (WTA Energy, Cory
| Brothers B.V.) are verbatim from the client's 2026-08-22 reply in
| docs/source/emails.txt. Our Story's WCA/ISO/bizSAFE stats and the
| project-desk contacts are verbatim from the client's "1st email on HOME
| Page" (docs/source/emails.txt). The partners marquee list and the service
| blurbs are also real, client-supplied content. Hero and CTA copy is
| original marketing phrasing written for this build rather than a client
| quote, but it asserts no fleet size, tonnage, headcount or project
| reference that was not supplied — nothing on this page states an
| unverified fact.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Project Logistics & Heavy Lift Freight Forwarding Singapore | '
           . $settings['company_name'];
$pageDesc  = 'Carriage Global (S) Pte Ltd moves heavy lift, break bulk and project '
           . 'cargo by sea, air and road from Singapore. ISO 9001:2015 certified.';
$bodyClass = 'page-home';

/* Hero slides. Falls back to the single `settings` hero when the table is
   empty, so the homepage still renders correctly during setup. */
$heroSlides = db_all(
    $pdo,
    "SELECT * FROM hero_slides WHERE status = 'active' ORDER BY sort_order ASC, id ASC"
);
if (!$heroSlides) {
    $heroSlides = [[
        'eyebrow'    => 'Project cargo, heavy lift, break bulk',
        'heading'    => $settings['hero_heading'],
        'subheading' => $settings['hero_subheading'],
        'image'      => $settings['hero_bg_image'],
        'alt_text'   => 'Carriage Global project cargo operation',
        'cta_label'  => 'Get a Quote',
        'cta_link'   => 'contact.php',
    ]];
}

/* Featured operations photography. Falls back to the files on disk until the
   client has uploaded and tagged their own through the admin gallery.
   ops-05.jpg was dropped 2026-08-19: byte-for-byte identical to
   assets/img/fleet/lashing.jpg (confirmed via md5sum) — the same photo
   under two filenames, which meant it silently duplicated Division 01's
   gallery every time this array's 5th slot rendered in the Services bento
   below. Replaced with ops-07.jpg, a real, previously-unused client photo
   from client_assets/pic/ (client, 2026-08-19: pointed at that folder for
   more real photography after "dully check don't repeat images"). */
$galleryRows = db_all(
    $pdo,
    "SELECT * FROM gallery WHERE status = 'active' AND featured = 1
     ORDER BY sort_order ASC, id ASC LIMIT 8"
);
if (!$galleryRows) {
    $galleryRows = [
        ['image_path' => 'assets/img/gallery/ops-01.jpg', 'alt_text' => 'Project cargo lifted aboard a geared vessel'],
        ['image_path' => 'assets/img/gallery/ops-02.jpg', 'alt_text' => 'Break bulk unit slung under a ship crane'],
        ['image_path' => 'assets/img/gallery/ops-03.jpg', 'alt_text' => 'Wrapped tank hoisted by crane onto a vessel'],
        ['image_path' => 'assets/img/gallery/ops-04.jpg', 'alt_text' => 'Cargo transferred to a barge alongside'],
        ['image_path' => 'assets/img/gallery/ops-07.jpg', 'alt_text' => 'Carriage Global trailer loaded with a large cable reel and crated cargo at a yard'],
        ['image_path' => 'assets/img/gallery/ops-06.jpg', 'alt_text' => 'Barge operation in Singapore waters'],
    ];
}

/* Certifications. Verbatim instruction from the client's "1st email on
   HOME Page": "At the left hand corner write both company names and co
   registration numbers, ISO number, Bizsafe". Rebuilt several times on
   client follow-ups — full PDF page as a card, then the compact registrar
   badge with a click-to-view popup, then a two-column entities+certs split,
   then full-page pixel-faithful previews at large size, and now
   (2026-08-21: "remove certification instead make it as badge with icons
   only") back to the compact registrar badge treatment — the real
   ISO/bizSAFE/WCA seal artwork (assets/img/certificates/badge-*.png)
   instead of a scaled-down screenshot of the PDF's first page. The two
   entity names and the UEN stayed up in the navbar logo lockup
   (includes/header.php) from the previous redesign, not repeated here.

   Clicking a badge still opens the real PDF in the Magnific Popup
   lightbox (init in includes/scripts.php) — 'file' is unchanged, only the
   card face is now the small badge icon instead of a full-page preview. */
$certificates = [
    [
        'label' => 'ISO 9001:2015',
        'file'  => 'assets/certificates/iso-9001-2015.pdf',
        'badge' => 'assets/img/certificates/badge-iso9001.jpg',
    ],
    [
        'label' => 'bizSAFE Level 4',
        'file'  => 'assets/certificates/bizsafe-4.pdf',
        'badge' => 'assets/img/certificates/badge-bizsafe.jpg',
    ],
    [
        'label' => 'WCA Project, 15 yrs',
        'file'  => 'assets/certificates/wca-project-membership.pdf',
        'badge' => 'assets/img/certificates/badge-wca.png',
    ],
];

/* SECTION 1 of the client's HOME Page email: "Unrivaled Control: Two
   Specialized In-House Departments". Verbatim, split into its two named
   divisions. */
$divisions = [
    [
        'title'   => 'In-House Asset Transport & Technical Site Services',
        'tagline' => 'Eliminating transit risks through wholly-owned equipment, certified field crews, and regional overland lanes.',
        'intro'   => "Our land transport division is built on physical assets and boots-on-the-ground technical expertise. We don't rely on sub-contractors to secure your cargo; we deploy our own personnel and machinery to ensure total quality control.",
        'items'   => [
            ['title' => 'Wholly-owned specialized fleet', 'body' => 'Immediate access to an extensive, company-owned fleet of heavy-duty low-bed trailers, multi-axle configurations, and skeleton chassis designed for heavy-haul and out-of-gauge (OOG) transport.'],
            ['title' => 'Pan-Asian cross-border trucking', 'body' => 'High-frequency, secure overland corridors connecting Singapore, transiting West Malaysia, and reaching all the way up to Thailand. We handle all customs clearances, border permits, and transit documentation seamlessly.'],
            ['title' => 'In-house lashing, lifting & rigging teams', 'body' => 'Certified rigger-packers and lifting supervisors who calculate center-of-gravity dynamics, design customized lifting plans, and execute precise tie-downs using premium-grade materials.'],
            ['title' => 'Industrial packing & box fabrication', 'body' => 'On-site construction of heavy-duty, custom-engineered wooden boxes, skids, and crates tailored to the exact dimensional and weight requirements of sensitive or high-value machinery.'],
            ['title' => 'Cargo surveying & risk mitigation', 'body' => 'Rigorous pre-ops and post-ops cargo inspections, route surveys, pinch-point analysis, and continuous monitoring to guarantee the physical integrity of your assets.'],
            ['title' => 'Asset storage & environmental protection', 'body' => 'Access to secure, high-capacity open-yard storage facilities equipped for heavy grounding, with comprehensive industrial fumigation services meeting stringent international biosecurity standards.'],
        ],
    ],
    [
        'title'   => 'Project Freight Forwarding & Marine Engineering Desk',
        'tagline' => 'Navigating complex maritime lanes, vessel charters, and port geometry constraints.',
        'intro'   => 'When industrial cargo exceeds the limits of standard roads, our maritime division steps in. We analyze everything from coastal hydrology to port infrastructure to select, secure, and engineer the ideal ocean transit method for your project.',
        'items'   => [
            ['title' => 'Specialized container operations (OOG)', 'body' => 'Expert out-of-gauge stowage planning: precise loading, blocking, and lashing of oversized cargo onto flat racks and open-top containers, plus complex uncontainerised cargo (UC) safely positioned on container vessels.'],
            ['title' => 'Roll-on / roll-off (RoRo) & MAFI solutions', 'body' => 'Efficient handling of heavy rolling stock and stationary oversized industrial components using heavy-duty MAFI trailers for seamless RoRo vessel loading and discharge.'],
            ['title' => 'Tug & barge chartering', 'body' => 'Specialized coastal and inland waterway transport. We source and charter dedicated tug and barge configurations designed to navigate shallow-draft inland waterways and remote shorelines lacking mature port infrastructure.'],
            ['title' => 'Full & part vessel chartering', 'body' => "Direct access to global shipowners. We charter heavy-lift, geared, semi-geared, and gearless vessels tailored entirely to your project's unique cargo profile and budget."],
            ['title' => 'Port infrastructure assessment', 'body' => 'Comprehensive engineering analysis of the destination and receiving sites: length overall (LOA) limits, draft restrictions, berth capacities, and tidal variations, to determine exactly which class of vessel can safely dock and discharge your cargo.'],
        ],
    ],
];

/* SECTION 2 of the same email: "Why Global Industrial Leaders Choose CGS". */
$advantage = [
    ['icon' => 'fa-truck-ramp-box', 'title' => 'Asset-backed reliability', 'body' => 'We own the trailers, including super low-bed trailers (0.8m above the ground), skeleton chassis, forklifts from 3t to 16t, stuffing equipment, and the teams behind them, giving total control over scheduling, safety protocols, and pricing.'],
    ['icon' => 'fa-route',          'title' => 'True door-to-door execution', 'body' => 'From the moment we fabricate the protective crating to the final discharge at a remote deep-sea or river port, your cargo never leaves our care.'],
    ['icon' => 'fa-calculator',     'title' => 'Engineering-first approach', 'body' => 'We don\'t guess. We calculate. Every lift, lash and vessel charter is backed by precise calculations, draft assessments, and route surveys, with lifting equipment availability confirmed ahead of time to avoid last-minute disappointments.'],
    ['icon' => 'fa-earth-asia',     'title' => 'Global network, local power', 'body' => 'A global logistics network combined with localized, asset-heavy execution. We have been a WCA Project member for fifteen years, working only with trusted, asset-based partners built up over that time.'],
];

/* "Contact Our Project Desk" from the same email — real named roles and
   department addresses, not generic placeholders. The last row has no
   'name': the source email names a person for every other row
   ("PROJECT MANAGER- ANGELINE TILOKANI", "FLEET MANAGER... ALAN SOH", etc.)
   but for Shipping Documents gives only "SHIPPING RELATED DOCUMENTS-
   ADMIN@CARRIAGEGLOBAL.COM" — no name. An earlier pass filled that gap
   with an invented "Admin Team" label; caught on a 2026-08-19 audit
   ("no ai content, only content provided from emails.txt") and removed —
   the markup below renders just the role + email when 'name' is absent. */
$projectDesk = [
    ['role' => 'Project Manager',            'name' => 'Angeline Tilokani', 'email' => 'angeline@carriageglobal.com'],
    ['role' => 'Fleet Manager & Operations', 'name' => 'Alan Soh',          'email' => 'alan@carriageglobal.com'],
    ['role' => 'Yard Manager',               'name' => 'Mr Teo & Mr Khoo',  'email' => 'ops@carriageglobal.com'],
    ['role' => 'Accounts',                   'name' => 'Ashwini & Mr Ryan', 'email' => 'accounts@carriageglobal.com'],
    ['role' => 'Shipping Documents',         'email' => 'admin@carriageglobal.com'],
];

/* Partner/client logos for the homepage marquee. Replaced 2026-08-19 with
   the list the client actually named for this exact purpose (the same
   HOME Page email: "towards the end of the page write our clients...
   google the logo of following clients to insert them"), superseding the
   old-site-scraped list. All eight now have a working logo file.

   The first pass at the last three (oilstates.svg, skadi-offshore.svg,
   logo.webp) each had a real problem unrelated to file format — flagged
   back to the client rather than silently worked around: the first two
   were white-on-transparent marks built for a dark background, invisible
   against this section's light one; logo.webp was a website-header
   screenshot bundling Brooke Dockyard's mark with a second, unrelated
   company's logo and a tagline on a grey banner. The client's follow-up
   files fix the first two directly (oil-states-1.png, skadie_offshore_1.png
   — proper navy/gold and blue marks on white, 2026-08-19). For Brooke's
   (brooke.png) the badge only occupied a ~310x265 region inside a
   2928x291 canvas of flat grey padding — displayed at this tile's actual
   size that would have shrunk the badge to an unreadable speck, so
   brooke-mark.png is a crop down to just the badge with that flat grey
   (229,229,229) chroma-keyed to transparent (checked against this
   section's background for edge fringing before saving — none). The
   original brooke.png is left on disk unused, same as any other
   as-delivered source file. */
$partners = [
    ['name' => 'Sarens',          'logo' => 'assets/img/partners/sarens.png'],
    ['name' => 'IKM Subsea',      'logo' => 'assets/img/partners/ikm-subsea.png'],
    ['name' => 'Favelle Favco',   'logo' => 'assets/img/partners/favelle-favco.png'],
    ['name' => 'Pageo',           'logo' => 'assets/img/partners/Pageo-Logo.gif'],
    ['name' => 'Skadi Offshore',  'logo' => 'assets/img/partners/skadie_offshore_1.png'],
    ['name' => 'Aster Chemical',  'logo' => 'assets/img/partners/aster-logo.webp'],
    ['name' => 'Brooke Dockyard', 'logo' => 'assets/img/partners/brooke-mark.png'],
    ['name' => 'Oilstates',       'logo' => 'assets/img/partners/oil-states-1.png'],
];

/* "Our Story" — verbatim from docs/source/Home page CGS.docx, split into
   its own two paragraphs at the sentence break the source already has (the
   "By investing..." sentence starts a new idea), one per column rather
   than both stacked in one. 2026-08-21: rebuilt to the client-referenced
   Roofer "About" layout (reference/roofer/about.php) — image + paragraph +
   a 2x2 stat grid on the left, an eyebrow + heading + paragraph + second
   image on the right. CLAUDE.md is explicit that this page invents no
   unverified numbers, so unlike Roofer's own placeholder counters
   ("2.5K+ Projects Completed", "84+ Specialists", "100% Client
   Satisfaction" — none of which CGS supplied), every stat cell here is a
   real, sourced fact: the founding year from this same docx paragraph, and
   the WCA/ISO/bizSAFE credentials from the client's "1st email on HOME
   Page" — which also means the credentials section removed from this page
   (client, 2026-08-21: "remove credentials section") isn't lost, just
   relocated into this stat grid. Two different photos, not one repeated
   (winch-transport.jpg was already used here; self-geared-crane.jpg was
   still unused anywhere on this page).

   'paras' order is deliberately: [0] the "By investing..." paragraph,
   [1] the "Founded in Singapore..." paragraph — the reverse of the source
   document's own order (client: "swap place for paragraph") — because the
   right column's own heading already says "Founded in Singapore in 2007",
   so [1] sits directly underneath it, and the fleet/investment paragraph
   sits with the first photo on the left instead. [1]'s own opening clause
   ("Founded in Singapore in 2007,") is dropped (client: "remove this line
   in description") since the heading right above it already says the same
   thing — the rest of the sentence is untouched, verbatim from the docx.

   ISO/bizSAFE stat cells render the real registrar badge artwork (client:
   "for ISO and bizSAFE add only logo") instead of a text value — 'logo'
   wins over 'value' in the template below when both could apply. The ISO
   badge (assets/img/certificates/badge-iso9001.jpg) is the United
   Registrar of Systems seal — client-supplied 2026-08-22 ("this is the
   correct body we are accredited with", client_assets/certificates/
   ISO-9001.jpg), replacing the generic "ISO 9001:2015 Certified Company"
   seal used the day before, which the client flagged as the wrong
   accrediting body. The bizSAFE badge (assets/img/certificates/
   badge-bizsafe.jpg) is client-supplied the same day (assets/certificates/
   bizSAFE-Level-4-logo.jpg) — the official mark with the "4" level
   subscript, replacing an unlevelled "bizSAFE" wordmark.

   The founding-year cell was swapped for the company motto 2026-08-21
   (client: "in our story replace 2007 stats with company motto"). Pulled
   from $settings['tagline'] rather than retyped here, so it stays in sync
   with the same line already shown in the footer (includes/footer.php).

   WCA Projects mark added 2026-08-26 (client, attaching the logo: "Where
   you have pasted the Bizsafe 4 logo and ISO logo, pls paste this as
   well") — into the existing "15+ years" cell as a small inline mark next
   to the number ('logo_inline', below), rather than a standalone 'logo'
   cell like ISO/bizSAFE: a 5th grid cell left an orphaned single item on
   its own row in the 2-column grid, and the "15+" fact is worth keeping
   next to the mark rather than dropped in favor of it. */
$ourStory = [
    'paras' => [
        'By investing in our own asset-based fleet, storage facilities, and marine transport services, CGS has enhanced its ability to deliver seamless, end-to-end supply chain management. We remain committed to providing dependable, safe, and tailor-made project logistics throughout Southeast Asia and across the global marketplace.',
        'Carriage Global (S) Pte Ltd (CGS) began as a specialized logistics and project freight-forwarding provider. Through the years, we have cultivated deep technical proficiency in managing heavy-lift, out-of-gauge, and intricate shipments for the energy, offshore, mining, and infrastructure sectors.',
    ],
    'image'       => 'assets/img/fleet/winch-transport.jpg',
    'alt'         => 'Carriage Global (S) Pte Ltd branded low-bed trailer carrying a FAGEO winch unit through an industrial yard',
    'image_two'   => 'assets/img/hero/self-geared-crane.jpg',
    'alt_two'     => 'Self-geared MacGregor ship crane hoisting cargo at a Singapore port',
    'stats' => [
        ['value' => $settings['tagline'], 'label' => 'Our motto'],
        ['value' => '15+',  'label' => 'Years as a WCA Project member', 'logo_inline' => 'assets/img/certificates/badge-wca-projects.png'],
        ['logo'  => 'assets/img/certificates/badge-iso9001.jpg', 'label' => 'ISO 9001:2015 certified'],
        ['logo'  => 'assets/img/certificates/badge-bizsafe.jpg', 'label' => 'bizSAFE Level 4 certified'],
    ],
];

/* Mission & Vision — verbatim from the same docx. Rendered as $mvSection
   below: an image-collage-plus-text feature row per pillar, sides
   alternating (client, with a reference screenshot: "Mission right side
   text left image ... total 2 images ... reverse the same for
   [vision]") — Mission's collage sits left of its text, Vision's sits
   right. Replaces the earlier "Waypoint" concept (kept hidden below, same
   convention as every other superseded section on this page) rather than
   deleting it.

   Each pillar now carries two real photos for the collage instead of
   one: 'image'/'alt' is the large lead photo, 'image_two'/'alt_two' is
   the smaller overlapping accent photo (client: "use images but not
   repeat it for mission and vision"). Mission keeps its original pair
   (tank-transport.jpg + ops-05.jpg). Vision's pair was replaced 2026-08-21
   (client: "replace vision images") — spmt-trailer.jpg and ops-09.jpg are
   now unused anywhere on this page (freed up, not deleted, in case a
   future section wants them) rather than reused elsewhere. The
   replacements, ops-16.jpg and ops-17.jpg, are two fresh shots pulled
   from client_assets/latest/ and copied into assets/img/gallery/ under
   the next free ops- numbers — chosen because every other real photo
   already on this page was already accounted for. 'marker' is dead data
   now, read only by the hidden Waypoint markup below. */
$pillars = [
    [
        'label'     => 'Mission',
        'marker'    => 'present position',
        'icon'      => 'fa-bullseye',
        'body'      => "To provide integrated, customized and reliable logistics solutions that optimize customers' supply chains, while maintaining high standards of quality, compliance, health, safety and environmental responsibility.",
        'image'     => 'assets/img/fleet/tank-transport.jpg',
        'alt'       => 'Crane lowering a large process tank onto a low-bed trailer at a Singapore port',
        'image_two' => 'assets/img/gallery/ops-05.jpg',
        'alt_two'   => 'Large cable reel secured on a Carriage Global low-bed trailer at a container terminal at night',
    ],
    [
        'label'     => 'Vision',
        'marker'    => 'heading',
        'icon'      => 'fa-binoculars',
        'body'      => 'To achieve operational excellence and become a trusted global logistics partner, delivering safe, efficient and sustainable transportation solutions while consistently exceeding customer expectations.',
        'image'     => 'assets/img/gallery/ops-16.jpg',
        'alt'       => 'Large IQIP pressure vessel rigged for lifting on a Carriage Global low-bed trailer at a yard',
        'image_two' => 'assets/img/gallery/ops-17.jpg',
        'alt_two'   => 'Mobile crane lowering specialized equipment onto a low-bed trailer at a Singapore port, container cranes in the background',
    ],
];

/* "The CGS Advantage" — verbatim from the docx's second section ("Why
   Customers Choose an Asset-Based Project Logistics Partner"), 6 items.
   Supersedes the old 4-item $advantage strip that used to live inside the
   Divisions section below (now hidden along with it) — kept as a separate,
   standalone section here since Divisions itself is gone. */
$cgsAdvantage = [
    ['icon' => 'fa-truck-ramp-box',  'title' => 'Asset-Backed Reliability',        'body' => 'CGS owns and operates specialized transport and handling equipment, including low-bed and super-low-bed trailers, skeleton chassis, forklifts and supporting equipment. This provides greater control over equipment availability, planning and execution.'],
    ['icon' => 'fa-diagram-project', 'title' => 'Single-Source Accountability',    'body' => 'Our transport, technical, freight forwarding and marine capabilities work together. Customers have a coordinated project team rather than multiple disconnected service providers.'],
    ['icon' => 'fa-calculator',      'title' => 'Engineering-Led Planning',        'body' => 'We assess the physical and operational requirements before execution.'],
    ['icon' => 'fa-route',           'title' => 'Door-to-Door Coordination',       'body' => 'From packing and factory collection through inland transportation, port operations, ocean freight, discharge and final delivery, CGS coordinates the complete logistics chain.'],
    ['icon' => 'fa-earth-asia',      'title' => 'Regional Execution. Global Reach.', 'body' => 'Our Singapore-based operations support regional project movements while our international network extends our capabilities across global trade lanes.'],
    ['icon' => 'fa-handshake',       'title' => 'Established Project Network',     'body' => 'CGS has been a WCA Project member for 15 years. Over this period, we have developed long-standing relationships with project logistics partners, vessel owners and service providers. We prioritize partners based on operational capability, reliability and proven working relationships rather than simply selecting the lowest-cost option.'],
];

/* Testimonials — the first 3 client quotes from docs/source/Home page
   CGS.docx, kept verbatim including their own capitalization and phrasing
   (client convention on this page throughout: real content only, nothing
   smoothed over or invented). Only stray double-spaces from the source
   document's own formatting are collapsed to single spaces.

   2 more added 2026-08-22, from the client's reply in emails.txt ("As
   mentioned yesterday, I will give you a few more testimonials"). Same
   verbatim convention: WTA Energy's is untouched; Cory Brothers B.V.'s
   fixes the one obvious transcription typo the client's own email had
   ("epeatedly" -> "repeatedly") — not a phrasing edit. The source email
   signed off with a stray "C" instead of a role (unlike every other quote
   here, which names one) — rendered as "Company" so the card's byline
   line isn't a bare, meaningless initial. */
$testimonials = [
    [
        'quote'   => 'We thank the Carriage Global team for their continued support, great service and very professional approach! Communication is clear, response times are fast and everything is handled with great care. A reliable logistics partner who are willing to go the extra mile. Much appreciate the support for the Sarens Team.',
        'name'    => 'Group Logistic Manager',
        'company' => 'Sarens NV, Belgium',
    ],
    [
        'quote'   => 'We have been using CGS Since the start of this year to send our equipment to various part of Asia. what stands out is their efficiency and knowledge of the many custom requirements for the different countries. All our Equipment were shipped with NO issue and Delay.',
        'name'    => 'Project Manager',
        'company' => 'Pageo SubOcean Pte Ltd',
    ],
    [
        'quote'   => 'Very Knowledgeable and Experienced in handling Global Shipping requirements. The team is highly responsive, providing quick and efficient support whenever needed.',
        'name'    => 'Project Department',
        'company' => 'Mansam Group',
    ],
    [
        'quote'   => 'Without a doubt CGS have been giving us not only competitive pricing to beat the market for business, but also technical solutions which has given us an edge to compete for more complex business. The volume of business has increased tremendously due to your support. We are happy to continue this partnership for as long as we can.',
        'name'    => 'Regional Managing Director',
        'company' => 'WTA Energy',
    ],
    [
        'quote'   => 'We are working with Carriage Global (S) Pte Ltd for quite some years now and very content with the cooperation. They have handled both OOG and BB cargoes, including pre-carriage, FOB handling and Ocean Freight. I would work with them repeatedly.',
        'name'    => 'Company',
        'company' => 'Cory Brothers B.V.',
    ],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── HERO CAROUSEL ────────────────────────────────────
       Slides come from the hero_slides table so the client can reorder
       or retire them from the admin.

       Only the copy and the photograph change between slides. The CTA
       label, the facts band and the layout stay put, so the carousel
       never moves a target the visitor is reaching for.

       Marked aria-roledescription="carousel" with each slide labelled
       "n of N"; aria-live is set to "off" while autoplay runs and
       flipped to "polite" once the visitor takes manual control, so a
       screen reader is not interrupted every few seconds. -->
  <section class="cgs-hero" aria-roledescription="carousel" aria-label="Carriage Global capabilities">

    <?php /* Single persistent background across every slide — the footage
             that used to run in its own Video Band section further down the
             page (now hidden, see that section's comment). Sits behind the
             swiper rather than inside each slide, so it keeps playing
             uninterrupted as the copy rotates instead of restarting per
             slide.

             --cgs-hero-video-pos controls the object-position crop point
             (see cgs.css): this footage carries its own logo watermark
             near the top of the frame, and a plain center crop cut it off
             under the header. Biased toward the top edge so the watermark
             clears the header instead of being cropped away — tune this
             one value if the source footage changes. */ ?>
    <div class="cgs-hero__media" style="--cgs-hero-video-pos: center 15%;">
      <video
        src="<?php echo url($settings['video_band_src']); ?>"
        poster="<?php echo url($settings['video_band_poster'] ?: $heroSlides[0]['image']); ?>"
        autoplay muted loop playsinline preload="metadata"
        aria-hidden="true"></video>
    </div>

    <?php /* Background footage autoplays muted (browser policy requires
             that); this is the one control a visitor gets over it — sound
             stays off until they ask for it. Wired up in cgs.js
             (data-hero-sound), which is also what keeps aria-pressed and
             the label in sync with the video's actual muted state. */ ?>
    <button type="button" class="cgs-hero__sound" data-hero-sound aria-pressed="false" aria-label="Unmute background video">
      <i class="fa-solid fa-volume-xmark" aria-hidden="true"></i>
      <i class="fa-solid fa-volume-high" aria-hidden="true"></i>
    </button>

    <div class="swiper cgs-hero__swiper" data-hero-swiper>
      <div class="swiper-wrapper">
        <?php foreach ($heroSlides as $i => $slide): ?>
        <div class="swiper-slide cgs-hero__slide"
             role="group"
             aria-roledescription="slide"
             aria-label="<?php echo ($i + 1) . ' of ' . count($heroSlides); ?>">

          <?php /* Client: "in hero remove overlay all text and pagination" —
                   the eyebrow/heading/lead copy, the dark scrim behind it, and
                   the dot pagination are all gone; just the video plays behind
                   the sound toggle now. The one exception is slide 0's heading,
                   kept as a visually-hidden h1 (Bootstrap's .visually-hidden)
                   rather than deleted outright — every other page on this site
                   has exactly one h1 and search engines/screen readers still
                   expect this document to, even with the visible copy gone. */ ?>
          <?php if ($i === 0): ?>
          <h1 class="visually-hidden"><?php echo e($slide['heading']); ?></h1>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 2 ── VIDEO BAND ───────────────────────────────────────
       Hidden 2026-08-21 (client, "Home Page redesign": "remove ... video
       section") — markup/CSS left in place, not deleted, same convention
       as every other client "remove X" request on this page.

       Its footage (video_band_src / video_band_poster) is no longer idle,
       though: per a later client request the same day ("in hero replace
       image with the old video section video"), it now plays as the Hero's
       own persistent background — see the .cgs-hero__media block above,
       outside the slide loop so playback isn't restarted on every slide
       change. This standalone section stays hidden rather than doubling
       the footage up on the page. -->
  <?php if (false): ?>
  <section class="cgs-video-band" aria-label="Carriage Global operations">
    <div class="container-fluid px-4">
      <div class="cgs-video-band__frame">
        <video
          src="<?php echo url($settings['video_band_src']); ?>"
          <?php if (!empty($settings['video_band_poster'])): ?>
          poster="<?php echo url($settings['video_band_poster']); ?>"
          <?php endif; ?>
          autoplay muted loop playsinline controls preload="metadata"></video>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 2 ── CERTIFICATIONS ───────────────────────────────────
       Hidden 2026-08-21 (client: "remove credentials section") — markup/CSS
       left in place, not deleted, same convention as every other client
       "remove X" request on this page. The ISO/WCA/bizSAFE facts it used to
       show aren't lost: they now live in the Our Story stat grid just below
       (see $ourStory['stats'] and cgs-story__stat). -->
  <?php if (false): ?>
  <section class="cgs-section cgs-credentials" aria-label="Certifications">
    <div class="container-fluid px-4">
      <header class="cgs-credentials__head">
        <p class="cgs-eyebrow">Credentials</p>
        <h2>Certifications</h2>
      </header>

      <div class="cgs-credentials__badges">
        <?php foreach ($certificates as $cert): ?>
        <a class="cgs-credentials__badge cgs-pdf-trigger" href="<?php echo url($cert['file']); ?>" aria-label="<?php echo e($cert['label']); ?> — view full certificate PDF">
          <span class="cgs-credentials__badge-icon">
            <img src="<?php echo url($cert['badge']); ?>" alt="" loading="lazy">
          </span>
          <span class="cgs-credentials__meta"><?php echo e($cert['label']); ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 2 ── OUR STORY ────────────────────────────────────────
       2026-08-21: rebuilt to the layout the client pointed at
       (reference/roofer/about.php's "About" section) — image + paragraph +
       a 2x2 stat grid on the left, an eyebrow + heading + paragraph +
       second image on the right. Content is still verbatim from
       docs/source/Home page CGS.docx; every stat is a real, sourced fact
       (see the $ourStory comment above) rather than Roofer's own invented
       placeholder counters. -->
  <section class="cgs-section cgs-story">
    <div class="container-fluid px-4">
      <div class="cgs-story__grid">

        <div class="cgs-story__col">
          <div class="cgs-story__media" data-reveal>
            <img src="<?php echo url($ourStory['image']); ?>"
                 alt="<?php echo e($ourStory['alt']); ?>"
                 loading="lazy" width="1000" height="720">
          </div>
          <p data-reveal style="--reveal-delay: 80ms"><?php echo e($ourStory['paras'][0]); ?></p>
          <div class="cgs-story__stats" data-reveal style="--reveal-delay: 140ms">
            <?php foreach ($ourStory['stats'] as $stat): ?>
            <div class="cgs-story__stat">
              <?php if (!empty($stat['logo'])): ?>
              <img class="cgs-story__stat-logo" src="<?php echo url($stat['logo']); ?>" alt="<?php echo e($stat['label']); ?>" loading="lazy">
              <?php elseif (!empty($stat['logo_inline'])): ?>
              <span class="cgs-story__stat-combo">
                <strong><?php echo e($stat['value']); ?></strong>
                <img class="cgs-story__stat-mark" src="<?php echo url($stat['logo_inline']); ?>" alt="" loading="lazy">
              </span>
              <?php else: ?>
              <strong><?php echo e($stat['value']); ?></strong>
              <?php endif; ?>
              <span><?php echo e($stat['label']); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="cgs-story__col">
          <p class="cgs-story__eyebrow" data-reveal>
            <i class="fa-solid fa-flag" aria-hidden="true"></i>
            Our Story
          </p>
          <h2 data-reveal style="--reveal-delay: 60ms">Founded in Singapore in 2007</h2>
          <p data-reveal style="--reveal-delay: 120ms"><?php echo e($ourStory['paras'][1]); ?></p>
          <div class="cgs-story__media" data-reveal style="--reveal-delay: 180ms">
            <img src="<?php echo url($ourStory['image_two']); ?>"
                 alt="<?php echo e($ourStory['alt_two']); ?>"
                 loading="lazy" width="1000" height="900">
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 3 ── MISSION & VISION ─────────────────────────────────
       Moved ahead of Services (client: "add it above our service").
       Rebuilt again 2026-08-21 (client, with a reference screenshot):
       "Mission right side text left image on top a image total 2
       images ... reverse the same for [vision]" — an image-collage-plus-
       text feature row per pillar rather than the "Waypoint" nautical
       concept this replaces (kept hidden below, same convention as every
       other superseded section on this page — not deleted). Same dark
       navy background Waypoint used (client: "with same background"),
       just a different layout inside it. Mission's collage sits left of
       its text; Vision mirrors it via --reverse, text left, collage
       right — literal alternation, not decorative, so the two rows never
       repeat the same shape twice in a row. Boxed in a real Bootstrap
       .container (nested inside the page's usual .container-fluid px-4,
       client: "make it in container") rather than running the row
       full-bleed — that's what was making the photo balloon to an
       unreasonable size on wide screens. Gallery/text are actual
       col-lg-6 columns now (client: "make the image suitable for the
       col6"), so the photo fills a real half-width column inside a
       width-capped container instead of a custom small box. -->
  <section class="cgs-section cgs-section--dark cgs-mv" aria-label="Mission and vision">
    <div class="container-fluid px-4">
      <div class="container">
        <div class="cgs-mv__list">
          <?php foreach ($pillars as $p => $pillar): ?>
          <?php $reverse = ($p % 2) === 1; ?>
          <article class="cgs-mv-feature row align-items-stretch g-4 g-lg-5">
            <div class="cgs-mv-feature__gallery col-lg-6 order-1<?php echo $reverse ? ' order-lg-2' : ' order-lg-1'; ?>" data-reveal>
              <div class="cgs-mv-feature__img cgs-mv-feature__img--main">
                <img src="<?php echo url($pillar['image']); ?>"
                     alt="<?php echo e($pillar['alt']); ?>"
                     loading="lazy" width="640" height="760">
              </div>
              <div class="cgs-mv-feature__img cgs-mv-feature__img--accent">
                <img src="<?php echo url($pillar['image_two']); ?>"
                     alt="<?php echo e($pillar['alt_two']); ?>"
                     loading="lazy" width="360" height="360">
              </div>
            </div>
            <div class="cgs-mv-feature__text col-lg-6 order-2<?php echo $reverse ? ' order-lg-1' : ' order-lg-2'; ?>" data-reveal style="--reveal-delay: 120ms">
              <span class="cgs-mv-feature__icon"><i class="fa-solid <?php echo e($pillar['icon']); ?>" aria-hidden="true"></i></span>
              <h3>Our <?php echo e($pillar['label']); ?></h3>
              <p><?php echo e($pillar['body']); ?></p>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- 3b ── MISSION & VISION ("Waypoint", superseded) ─────────
       Hidden 2026-08-21 — replaced same day by the image-collage layout
       above per a direct client request with a reference screenshot.
       Markup/CSS left in place, not deleted, same convention as every
       other superseded section on this page. -->
  <?php if (false): ?>
  <section class="cgs-section cgs-section--dark cgs-waypoint" aria-label="Mission and vision">
    <div class="container-fluid px-4">
      <div class="cgs-waypoint__head">
        <p class="cgs-waypoint__eyebrow" data-reveal>
          <i class="fa-solid fa-compass" aria-hidden="true"></i>
          Plotted course
        </p>
        <h2 data-reveal style="--reveal-delay: 60ms">Where we stand, where we're headed</h2>
      </div>

      <div class="cgs-waypoint__route">
        <?php foreach ($pillars as $p => $pillar): ?>
        <article class="cgs-waypoint-card" data-reveal style="--reveal-delay: <?php echo $p * 120; ?>ms">
          <p class="cgs-waypoint-card__marker">
            <span class="cgs-waypoint-card__dot" aria-hidden="true"><span></span></span>
            Waypoint &middot; <b><?php echo e($pillar['marker']); ?></b>
          </p>
          <div class="cgs-waypoint-card__medallion">
            <span class="cgs-waypoint-card__porthole">
              <img src="<?php echo url($pillar['image']); ?>"
                   alt="<?php echo e($pillar['alt']); ?>"
                   loading="lazy" width="168" height="168">
              <span class="cgs-waypoint-card__glare" aria-hidden="true"></span>
            </span>
            <span class="cgs-waypoint-card__badge" aria-hidden="true">
              <i class="fa-solid <?php echo e($pillar['icon']); ?>" aria-hidden="true"></i>
            </span>
          </div>
          <h3><?php echo e($pillar['label']); ?></h3>
          <p><?php echo e($pillar['body']); ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── SERVICES ─────────────────────────────────────────
       Scroll-pin: on desktop, with motion allowed, the section holds one
       screen (cgs-services-pin__sticky) while its own extra height
       (services-count x 100vh, added by cgs.js as .is-pinned) is scrolled
       through — each service crossfades in in turn, then the page
       continues to the next section, rather than requiring a swipe
       gesture to see all five. Mobile and reduced-motion visitors get the
       same crossfading stage without the pinned scroll: the arrows step
       it directly. Without JS, every service's grid is simply stacked in
       normal flow — nothing is hidden.

       The secondary photo cell uses a general operations shot (real
       company photography, not stock), cycled by index so no two
       services show the same picture — the feature cell keeps the one
       photo actually tied to that specific service. The Read More CTA
       cell has its own distinct background (`services.cta_image`,
       falls back to `image` when a service has none set), so a service
       card never shows the same photo twice across its three cells. -->
  <section class="cgs-section cgs-services-pin" id="services" data-services-pin
           style="--services-count: <?php echo max(1, count($services)); ?>;">
    <div class="cgs-services-pin__sticky">
      <div class="container-fluid px-4">
        <header class="cgs-section-head">
          <h2>Our Services</h2>
          <div class="cgs-section-head__right">
            <a href="<?php echo url('services.php'); ?>" class="cgs-textlink">
              All services <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
            </a>
            <?php if (count($services) > 1): ?>
            <div class="cgs-services__arrows">
              <button class="cgs-services__arrow" data-services-prev type="button" aria-label="Previous service">
                <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
              </button>
              <button class="cgs-services__arrow" data-services-next type="button" aria-label="Next service">
                <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
              </button>
            </div>
            <?php endif; ?>
          </div>
        </header>

        <?php if ($services): ?>
        <div class="cgs-services-pin__stage" data-services-stage>
          <?php /* Plain wrapper in the markup, no classes — cgs.js promotes
             this to a real .swiper-wrapper (and each slide to .swiper-slide)
             only while the carousel below 992px is active, so the swiper
             CSS's own display:flex never touches the desktop pin/crossfade
             layout or the no-JS fallback (every service stacked in normal
             flow), both of which rely on it staying an inert div. */ ?>
          <div data-services-track>
          <?php foreach ($services as $i => $svc):
            $num       = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
            $total     = str_pad((string) count($services), 2, '0', STR_PAD_LEFT);
            $secondary = $galleryRows ? $galleryRows[$i % count($galleryRows)] : null;
          ?>
          <div class="cgs-service-bento" data-service-slide>

            <a class="cgs-service-card cgs-service-bento__feature" href="<?php echo url($svc['link']); ?>"
               <?php if (empty($svc['image'])): ?>aria-label="<?php echo e($svc['title']); ?>"<?php endif; ?>>
              <?php if (!empty($svc['image'])): ?>
              <span class="cgs-service-card__media">
                <img src="<?php echo url($svc['image']); ?>"
                     alt="<?php echo e($svc['alt_text'] ?: $svc['title']); ?>"
                     loading="lazy" width="800" height="600">
              </span>
              <?php endif; ?>
            </a>

            <div class="cgs-service-bento__cell cgs-service-bento__desc">
              <h3 class="cgs-service-bento__desc-title"><?php echo e($svc['title']); ?></h3>
              <?php if (!empty($svc['short_desc'])): ?>
              <p><?php echo e($svc['short_desc']); ?></p>
              <?php endif; ?>
            </div>

            <?php if ($secondary): ?>
            <div class="cgs-service-bento__cell cgs-service-bento__icon">
              <img src="<?php echo url($secondary['image_path']); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="500">
            </div>
            <?php endif; ?>

            <?php $ctaImage = $svc['cta_image'] ?: $svc['image']; ?>
            <a class="cgs-service-bento__cell cgs-service-bento__cta" href="<?php echo url($svc['link']); ?>">
              <?php if (!empty($ctaImage)): ?>
              <span class="cgs-service-bento__cta-media" aria-hidden="true">
                <img src="<?php echo url($ctaImage); ?>" alt="" loading="lazy" width="300" height="220">
              </span>
              <?php endif; ?>
              <span class="cgs-service-bento__cta-label">
                Read more <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
              </span>
            </a>

            <div class="cgs-service-bento__cell cgs-service-bento__index" aria-hidden="true">
              <strong><?php echo $num; ?></strong>
              <span>/ <?php echo $total; ?></span>
            </div>

          </div>
          <?php endforeach; ?>
          </div>
        </div>

        <div class="cgs-services-pin__track" aria-hidden="true">
          <span class="cgs-services-pin__fill" data-services-fill></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- 4 ── DIVISIONS ────────────────────────────────────────
       Hidden 2026-08-21 (client, "Home Page redesign": "remove unrivaled
       control") — markup/CSS left in place, not deleted, same convention
       as every other client "remove X" request on this page. Its "why
       choose CGS" strip is superseded by the new, docx-sourced
       $cgsAdvantage section further down (now standalone, since this
       section is gone); $advantage below is the old 4-item strip, kept
       only for this hidden block. The client's own two named in-house
       departments, verbatim from the HOME Page email, followed by that old
       4-item strip — one band, two distinct rhythms within it. Each
       division is its own "feature module" (title/description + 3
       asymmetrical photos + a card grid of its checklist items),
       alternating text/gallery sides left-to-right — see $divisionMedia
       below and cgs.css's .cgs-division-feature block. -->
  <?php if (false): ?>
  <section class="cgs-section cgs-divisions">
    <div class="container-fluid px-4">
      <header class="cgs-divisions__head">
        <p class="cgs-eyebrow">Unrivaled control</p>
        <h2>Two specialized in-house departments</h2>
        <p class="cgs-divisions__lede">
          We eliminate third-party delays, hidden markups, and communication
          gaps. By operating our own transport fleet alongside a dedicated
          marine engineering desk, CGS provides single-source accountability
          from the manufacturing floor to the final foundation.
        </p>
      </header>

      <?php
      /* Both divisions share one bespoke "feature module" layout (client,
         division 01: "01 > title/subtitle/description at left, right col
         3 asymmetrical images, section below 2x3 cards for points, whole
         background white"; division 02: "likewise change division 2 with
         exact layout in reverse direction" — same module, gallery and
         text swap sides via --reverse). Real photography per division, not
         generic filler: division 01 is land-transport/fleet themed,
         division 02 is maritime/vessel-charter themed, matching each
         division's own subject.
         Every file below is used exactly once on this page (audited
         2026-08-19, client: "dully check don't repeat images"). The first
         pass at this only checked filenames, not actual file content —
         assets/img/hero/cgs-trailer.jpg turned out to be a byte-for-byte
         copy of assets/img/services/project-freight-forwarding.jpg (same
         photo, two filenames), so it was silently duplicating Service #1's
         card. Caught via md5sum once the client pointed at
         client_assets/pic/ for more real photography. Both divisions were
         cut back to a single full-bleed lead photo each (2026-08-19,
         client: "in division 1 and 2 keep only 1 image") — the --solo
         gallery modifier below already existed in cgs.css for this case
         from when division 02 briefly had only one usable photo, so no CSS
         change was needed, just fewer array entries. Division 01's photo
         was swapped again the same day (client: "change the image for
         division 1") for a shot that both reads landscape at full size
         (2048x1152, no crop needed in the solo box) and carries the
         Carriage Global name directly on the trailer. The two photos here
         are unpublished CGS operations photography, each confirmed unique
         via md5sum against every other file already used on this page
         before being copied into assets/img/. */
      $divisionMedia = [
          [
              ['src' => 'assets/img/fleet/oocl-pipe-trailer.jpg', 'alt' => 'Carriage Global low-bed trailer hauling large yellow industrial pipes past an OOCL container', 'wide' => true],
          ],
          [
              ['src' => 'assets/img/gallery/ops-08.jpg', 'alt' => 'MacGregor ship crane hoisting cargo over the water at a Singapore port', 'wide' => true],
          ],
      ];
      ?>
      <?php foreach ($divisions as $d => $division): ?>
      <?php $reverse = ($d % 2) === 1; ?>
      <article class="cgs-division-feature<?php echo $reverse ? ' cgs-division-feature--reverse' : ''; ?>">
        <div class="cgs-division-feature__top">
          <div class="cgs-division-feature__text" data-reveal>
            <span class="cgs-division-feature__num"><?php echo str_pad((string) ($d + 1), 2, '0', STR_PAD_LEFT); ?></span>
            <h3><?php echo e($division['title']); ?></h3>
            <p class="cgs-division-feature__tagline"><?php echo e($division['tagline']); ?></p>
            <p class="cgs-division-feature__intro"><?php echo e($division['intro']); ?></p>
          </div>
          <?php $images = $divisionMedia[$d] ?? []; ?>
          <div class="cgs-division-feature__gallery<?php echo count($images) === 1 ? ' cgs-division-feature__gallery--solo' : ''; ?>" data-reveal style="--reveal-delay: 120ms">
            <?php foreach ($images as $img): ?>
            <div class="cgs-division-feature__gimg<?php echo !empty($img['wide']) ? ' cgs-division-feature__gimg--wide' : ''; ?>">
              <img src="<?php echo url($img['src']); ?>" alt="<?php echo e($img['alt']); ?>"
                   loading="lazy" width="900" height="460">
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="cgs-division-feature__points">
          <?php foreach ($division['items'] as $i => $item): ?>
          <div class="cgs-point-card" data-reveal style="--reveal-delay: <?php echo $i * 60; ?>ms">
            <i class="fa-solid fa-check" aria-hidden="true"></i>
            <h4><?php echo e($item['title']); ?></h4>
            <p><?php echo e($item['body']); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </article>
      <?php endforeach; ?>

      <div class="cgs-advantage">
        <p class="cgs-eyebrow">The CGS advantage</p>
        <h3>Why global industrial leaders choose CGS</h3>
        <ul class="cgs-advantage__grid">
          <?php foreach ($advantage as $a => $point): ?>
          <li data-reveal style="--reveal-delay: <?php echo $a * 70; ?>ms">
            <span class="cgs-advantage__icon"><i class="fa-solid <?php echo e($point['icon']); ?>" aria-hidden="true"></i></span>
            <strong><?php echo e($point['title']); ?></strong>
            <p><?php echo e($point['body']); ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── MISSION & VISION (card layout) ─────────────────────
       Hidden 2026-08-21 (superseded same day by the "Waypoint" redesign —
       see cgs-waypoint above, moved ahead of Services) — markup/CSS left
       in place, not deleted, same convention as every other client
       "remove/replace X" request on this page. Was: two paired feature
       cards side by side, not the alternating text/image rows the retired
       Divisions section above used to use. -->
  <?php if (false): ?>
  <section class="cgs-section cgs-pillars">
    <div class="container-fluid px-4">
      <div class="cgs-pillars__grid">
        <?php foreach ($pillars as $p => $pillar): ?>
        <article class="cgs-pillar-card" data-reveal style="--reveal-delay: <?php echo $p * 100; ?>ms">
          <div class="cgs-pillar-card__media">
            <img src="<?php echo url($pillar['image']); ?>"
                 alt="<?php echo e($pillar['alt']); ?>"
                 loading="lazy" width="900" height="600">
          </div>
          <div class="cgs-pillar-card__body">
            <span class="cgs-pillar-card__icon"><i class="fa-solid <?php echo e($pillar['icon']); ?>" aria-hidden="true"></i></span>
            <h3><?php echo e($pillar['label']); ?></h3>
            <p><?php echo e($pillar['body']); ?></p>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5 ── THE CGS ADVANTAGE ────────────────────────────────
       New 2026-08-21, verbatim from docs/source/Home page CGS.docx
       ("Why Customers Choose an Asset-Based Project Logistics Partner"),
       6 items. Standalone tinted band — supersedes the old 4-item
       $advantage strip that used to live inside Divisions (now hidden
       above). Reuses the .cgs-advantage__grid icon-card pattern already
       built for that strip. -->
  <section class="cgs-section cgs-section--tint">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>The CGS Advantage</h2>
      </header>
      <ul class="cgs-advantage__grid">
        <?php foreach ($cgsAdvantage as $a => $point): ?>
        <li data-reveal style="--reveal-delay: <?php echo $a * 60; ?>ms">
          <span class="cgs-advantage__icon"><i class="fa-solid <?php echo e($point['icon']); ?>" aria-hidden="true"></i></span>
          <strong><?php echo e($point['title']); ?></strong>
          <p><?php echo e($point['body']); ?></p>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- 6 ── TESTIMONIALS ─────────────────────────────────────
       New 2026-08-21, the 3 real client quotes from
       docs/source/Home page CGS.docx, kept verbatim (2 more added
       2026-08-22 from the client's follow-up reply — see $testimonials
       above). Rebuilt as a Swiper
       carousel (client: "make the testimonials swiper with pagination and
       controls and it moves autoplay"), then back to 3 cards side by side
       on desktop (client: "make the testimonial three cards") — still a
       Swiper underneath so mobile/tablet, which only fit 1-2 cards at a
       time, keep real pagination/arrow/autoplay movement (see cgs.js).
       Same autoplay etiquette as the hero and services carousels: pauses
       on hover, on keyboard focus and while off-screen, never starts
       under reduced motion. -->
  <?php if ($testimonials): ?>
  <section class="cgs-section cgs-testimonials">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>What our clients say</h2>
        <?php if (count($testimonials) > 1): ?>
        <div class="cgs-section-head__right">
          <div class="cgs-testimonials__arrows">
            <button class="cgs-testimonials__arrow" data-testimonials-prev type="button" aria-label="Previous testimonial">
              <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
            </button>
            <button class="cgs-testimonials__arrow" data-testimonials-next type="button" aria-label="Next testimonial">
              <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
            </button>
          </div>
        </div>
        <?php endif; ?>
      </header>

      <div class="swiper cgs-testimonials__swiper" data-testimonials-swiper>
        <ul class="swiper-wrapper cgs-testimonials__grid">
          <?php foreach ($testimonials as $t => $item): ?>
          <li class="swiper-slide cgs-testimonial">
            <i class="fa-solid fa-quote-left cgs-testimonial__icon" aria-hidden="true"></i>
            <blockquote><?php echo e($item['quote']); ?></blockquote>
            <footer>
              <span class="cgs-testimonial__name"><?php echo e($item['name']); ?></span>
              <span class="cgs-testimonial__company"><?php echo e($item['company']); ?></span>
            </footer>
          </li>
          <?php endforeach; ?>
        </ul>

        <?php if (count($testimonials) > 1): ?>
        <div class="swiper-pagination cgs-testimonials__pagination"></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 7 ── PARTNERS ─────────────────────────────────────────
       Continuous auto-scroll logo marquee. The exact client list from the
       HOME Page email (docs/PROJECT-BRIEF.md open question 13) — five of
       the eight names have no verified logo file yet and render as text. -->
  <?php if ($partners): ?>
  <section class="cgs-section cgs-partners" aria-label="Partners and carriers">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Working with trusted partners</h2>
      </header>
    </div>

    <?php
    /* Swiper's loop mode needs the *real* slide count (before its own
       internal duplication) to cover however many tiles fit on screen at
       once, or it silently disables looping and the whole strip freezes
       (see cgs.js) — on a wide enough monitor, more ~230px tiles fit than
       there are partner logos. Repeating the same list into the DOM a few
       times keeps that covered regardless of screen width, without
       needing a second copy of the data itself. */
    $partnersLoop = array_merge($partners, $partners, $partners);
    /* No loading="lazy" on the logos below: Swiper measures every slide's
       width at init to decide whether loop mode has enough real content,
       and a still-loading (still zero-width) image at that exact moment
       makes the strip look narrower than it is — intermittently, only on
       whichever load was slow that time, which is why this only ever
       happened "sometimes" (client). cgs.js also re-measures once the
       images actually finish loading, as a second line of defence. */
    ?>
    <?php /* aria-hidden: the section's own aria-label already names the
       purpose; without this a screen reader would read out each partner
       name 3x now that the list is tripled for the loop-mode fix above. */ ?>
    <div class="swiper cgs-partners__swiper" data-partners-swiper aria-hidden="true">
      <div class="swiper-wrapper">
        <?php foreach ($partnersLoop as $partner): ?>
        <div class="swiper-slide cgs-partners__slide">
          <?php if (!empty($partner['logo'])): ?>
          <span class="cgs-partners__tile">
            <img src="<?php echo url($partner['logo']); ?>"
                 alt="<?php echo e($partner['name']); ?>"
                 width="160" height="60">
          </span>
          <?php else: ?>
          <span class="cgs-partners__tile cgs-partners__tile--text"><?php echo e($partner['name']); ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4c ── ACCENT CTA card ─────────────────────────────────
       Removed 2026-08-19 (client: "remove send us your packing list") —
       left in place, not deleted, in case it comes back. Partners (above)
       now sits directly on the white Divisions-to-teal-CTA seam it was
       styled for, so removing this doesn't leave a color mismatch. -->
  <?php if (false): ?>
  <section class="cgs-accent-cta">
    <div class="container-fluid px-4">
      <div class="cgs-accent-cta__inner" data-reveal>
        <h2>Send us your packing list</h2>
        <p>
          Dimensions and a deadline are enough to start. We come back with the
          mode, the route and what it costs.
        </p>
        <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--on-dark">
          Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5 ── FLEET TEASER ─────────────────────────────────────
       Two-image split. Copy upgraded 2026-08-19 with the client's own
       equipment specifics (docs/source/emails.txt): deck height and yard
       address from "Email Our Fleet", forklift tonnage from the "HOME
       Page" email's CGS-advantage bullet — replacing the previous
       generic bullets.
       Hidden 2026-08-19 (client: "hide the Our own trailers section") —
       left in place, not deleted, in case it comes back. -->
  <?php if (false): ?>
  <section class="cgs-section">
    <div class="container-fluid px-4">
      <div class="cgs-split">
        <div class="cgs-split__media">
          <img src="<?php echo url('assets/img/fleet/fleet-trailer.jpg'); ?>"
               alt="Carriage Global low-bed trailer loaded with an oversized vessel section"
               loading="lazy" width="900" height="700">
          <img src="<?php echo url('assets/img/fleet/lashing.jpg'); ?>"
               alt="Cargo chained and lashed to a Carriage Global trailer"
               loading="lazy" width="700" height="900">
        </div>

        <div class="cgs-split__body" data-reveal style="--reveal-delay: 280ms">
          <h2>Our own trailers, our own lashing crew</h2>
          <p>
            Owning the equipment and the people who secure the cargo removes the
            handover where most project shipments go wrong. Our fleet, our
            in-house lashing team and our open yard for storage and re-working
            all sit under one operation.
          </p>
          <ul class="cgs-split__points">
            <li>
              <strong>Low-bed & super low-bed trailers</strong>
              <span>Deck heights from around 0.8m off the ground, for clearance on high and oversized cargo.</span>
            </li>
            <li>
              <strong>Skeleton chassis & lifting equipment</strong>
              <span>Various chassis sizes, plus forklifts from 3t to 16t for loading, positioning and project cargo.</span>
            </li>
            <li>
              <strong>In-house lashing, lifting & fabrication</strong>
              <span>Qualified rigging teams, plus in-house fabrication of boxes, frames and protective structures.</span>
            </li>
            <li>
              <strong>Open yard storage</strong>
              <span>14 Penjuru Road: space, accessibility and controlled handling before onward transportation.</span>
            </li>
          </ul>
          <a href="<?php echo url('our-fleet.php'); ?>" class="cgs-btn cgs-btn--ghost">
            See the fleet <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 8 ── PROJECT DESK ─────────────────────────────────────
       "Contact Our Project Desk" from the client's HOME Page email — real
       named roles and department addresses, not generic placeholders, so a
       visitor with a live shipment reaches the right desk on the first
       try instead of a general enquiry queue. -->
  <section class="cgs-section cgs-section--tint cgs-desk">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Contact our project desk</h2>
      </header>
      <ul class="cgs-desk__grid">
        <?php foreach ($projectDesk as $n => $contact): ?>
        <li data-reveal style="--reveal-delay: <?php echo $n * 60; ?>ms">
          <span class="cgs-desk__role"><?php echo e($contact['role']); ?></span>
          <?php if (!empty($contact['name'])): ?>
          <strong class="cgs-desk__name"><?php echo e($contact['name']); ?></strong>
          <?php endif; ?>
          <a class="cgs-desk__email" href="mailto:<?php echo e($contact['email']); ?>">
            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            <?php echo e($contact['email']); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- 5c ── GALLERY ─────────────────────────────────────────
       Real operations photography, last content section on the page before
       the closing CTA (client: "I need a gallery section at last also").
       Masonry (CSS multi-column, not a fixed-height grid) so portrait and
       landscape shots each keep their own natural aspect ratio rather than
       being force-cropped into a uniform cell (client, earlier: "place
       portrait images in correct layout"). All six files are freshly
       pulled from client_assets/pic/ and confirmed unique via md5sum
       against every other image already used on this page — none of these
       repeat the Hero, Services or Division photography above. Each tile
       opens the full photo in a Magnific Popup lightbox with gallery
       navigation (init in includes/scripts.php, kept out of cgs.js since
       Magnific Popup is jQuery-dependent).

       Hidden 2026-08-19 (client: "hide the gallery section") — markup, CSS
       (.cgs-gallery* in cgs.css) and the Magnific Popup init in
       scripts.php are all left in place, just not rendered, so this can
       come back with a one-line flip if the client wants it again. -->
  <?php if (false): ?>
  <?php
  $galleryShots = [
      ['src' => 'assets/img/gallery/ops-10.jpg', 'alt' => 'Green tarpaulin-wrapped cargo lifted by gantry crane at a Singapore container terminal'],
      ['src' => 'assets/img/gallery/ops-11.jpg', 'alt' => 'Support vessel lifted clear of the water by twin shipyard cranes'],
      ['src' => 'assets/img/gallery/ops-12.jpg', 'alt' => 'Large cylindrical pressure vessel lifted aboard a geared vessel at sea'],
      ['src' => 'assets/img/gallery/ops-13.jpg', 'alt' => "Wrapped process vessel lowered into a ship's cargo hold"],
      ['src' => 'assets/img/gallery/ops-14.jpg', 'alt' => 'Winch and crane equipment secured on a vessel deck alongside shipping containers'],
      ['src' => 'assets/img/gallery/ops-15.jpg', 'alt' => 'Twin deck cranes lifting cylindrical tanks aboard a vessel'],
  ];
  ?>
  <section class="cgs-section cgs-gallery" aria-label="Project photography">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Our operations, in the field</h2>
      </header>

      <div class="cgs-gallery__grid">
        <?php foreach ($galleryShots as $g => $shot):
          /* Real pixel dimensions, not a guessed 800x600 — masonry sizes
             each tile from its image's own intrinsic ratio, so getting
             width/height right here is what keeps a tall portrait shot
             tall instead of the browser reserving a landscape-shaped box
             for it before the file loads. */
          $shotDims = @getimagesize(__DIR__ . '/' . $shot['src']);
          $shotW = $shotDims[0] ?? 800;
          $shotH = $shotDims[1] ?? 600;
        ?>
        <a class="cgs-gallery__tile cgs-gallery-trigger" href="<?php echo url($shot['src']); ?>"
           data-reveal style="--reveal-delay: <?php echo $g * 60; ?>ms">
          <img src="<?php echo url($shot['src']); ?>"
               alt="<?php echo e($shot['alt']); ?>"
               loading="lazy" width="<?php echo (int) $shotW; ?>" height="<?php echo (int) $shotH; ?>">
          <span class="cgs-gallery__caption" aria-hidden="true">
            <?php echo e($shot['alt']); ?>
            <i class="fa-solid fa-expand" aria-hidden="true"></i>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 9 ── CTA ──────────────────────────────────────────────
       One CTA intent on this page: "Get a Quote". Same label in the
       nav, the hero and here. Rounded navy card inset on a white section,
       copy on the left and actions on the right, with a hairline squiggle
       and dashed orbit rings as quiet decoration. Last section on the
       page, right above the footer. -->
  <section class="cgs-cta">
    <div class="container-fluid px-4">
      <div class="cgs-cta__card">
        <div class="cgs-cta__copy">
          <h2>Tell us what needs to move</h2>
          <p>
            Send the dimensions and the deadline. We will come back with the mode,
            the route and what it costs.
          </p>
        </div>
        <div class="cgs-cta__actions">
          <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
          <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-btn cgs-btn--outline-light">
            <i class="fa-solid fa-phone" aria-hidden="true"></i> <?php echo e($settings['phone']); ?>
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';
