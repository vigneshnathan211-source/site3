---
name: Carriage Global (S) Pte Ltd — CGS Web CMS
description: Sea Blue, Logo True — a navy-and-sea-blue project-logistics design system, sourced from the client's own mark, with zero warm colour.
colors:
  deep-navy: "#082238"
  navy-hover: "#0E3A56"
  ocean-blue: "#146B96"
  ocean-blue-deep: "#0F5378"
  sea-blue: "#2E9FD6"
  sea-blue-light: "#6FC6E9"
  ink: "#0E1726"
  slate: "#4A5568"
  muted-slate: "#64708A"
  hairline: "#E4E8EF"
  surface-tint: "#F5F8FC"
  white: "#FFFFFF"
  success-green: "#1E9E6A"
  danger-red: "#D64550"
typography:
  display:
    fontFamily: "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "clamp(32px, 5vw, 56px)"
    fontWeight: 800
    lineHeight: 1.12
    letterSpacing: "-0.025em"
  headline:
    fontFamily: "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "clamp(26px, 3.4vw, 40px)"
    fontWeight: 800
    lineHeight: 1.15
    letterSpacing: "-0.025em"
  title:
    fontFamily: "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "18px"
    fontWeight: 700
    letterSpacing: "-0.01em"
  body:
    fontFamily: "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.75
  label:
    fontFamily: "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "14px"
    fontWeight: 600
    letterSpacing: "0.08em"
rounded:
  sm: "4px"
  pill: "100px"
  full: "50%"
spacing:
  gap-md: "16px"
  gap-lg: "clamp(32px, 5vw, 72px)"
  section-y: "clamp(56px, 7vw, 104px)"
components:
  button-primary:
    backgroundColor: "{colors.deep-navy}"
    textColor: "{colors.white}"
    rounded: "{rounded.sm}"
    padding: "15px 28px"
  button-primary-hover:
    backgroundColor: "{colors.navy-hover}"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.deep-navy}"
    rounded: "{rounded.sm}"
    padding: "15px 28px"
  button-ghost-hover:
    textColor: "{colors.ocean-blue}"
  card:
    backgroundColor: "{colors.white}"
    rounded: "{rounded.sm}"
    padding: "24px"
  eyebrow-label:
    textColor: "{colors.ocean-blue}"
    typography: "{typography.label}"
---

# Design System: Carriage Global (S) Pte Ltd

## Overview

**Creative North Star: "Sea Blue, Logo True"**

This palette is not invented. It is the client's own logo — a left-to-right gradient from a light sea-blue into a deep navy — extended into a full interface system. Deep Navy is the dark end of that gradient and does almost all of the work: button fills, the top bar, the footer, every dark section. Sea Blue is the light end, and it is deliberately rare: reserved for links, icons, and focus rings, with exactly one place on the entire home page (the accent CTA band) where it is allowed to fill a surface outright. Maersk's own site was sampled live as a second data point late in the process — not as inspiration to copy, but as industry validation that a deep-navy-primary / sea-blue-accent hierarchy with no warm colour anywhere is the right register for a shipping and project-logistics brand, not an idiosyncratic choice.

The system reads as confident rather than decorated: near-square 4px corners instead of soft consumer-app rounding, hairline borders doing most of the separating instead of ambient shadow, and photography that stays untouched by dark gradient overlays — legibility for white type comes from `text-shadow` alone, so the client's own operations photography is never dimmed to make room for a headline. Motion is brief and functional (sub-300ms transitions, a hard `ease-out` curve, no bounce) rather than atmospheric.

**Key Characteristics:**
- Two-colour brand axis only — Deep Navy and Sea Blue — with zero warm hue anywhere in the system.
- Sea Blue's rarity is enforced: one fill use on the whole page; everywhere else it is a link, an icon, or a focus ring.
- Flat by default; shadow appears only as hover/interaction feedback, always tinted navy, never pure black.
- One radius (4px) for every rectangle; fully-round shapes (pill, circle) are reserved for tags, badges, and icon buttons.
- No scrims over photography — `text-shadow` carries legibility instead, so the client's photos stay true.

## Colors

The palette is a strict two-hue system — navy and sea-blue — plus the neutrals needed to set type and draw hairlines. Nothing else is admitted.

### Primary
- **Deep Navy** (`#082238`): The system's dominant surface colour. Primary button fill, top info bar, footer, every `.cgs-section--dark` band, the 404 page. This is the dark end of the client's own logo gradient, not an invented brand colour.
- **Navy Hover** (`#0E3A56`): One step lighter than Deep Navy. Used exclusively as the hover/pressed state for primary-filled surfaces — never appears at rest.

### Secondary
- **Ocean Blue** (`#146B96`): Body-copy link colour and the active/hover state for nav items and eyebrow labels. A mid-tone between Deep Navy and Sea Blue — legible as a link on white without competing with Sea Blue's accent role.
- **Ocean Blue Deep** (`#0F5378`): Declared alongside Ocean Blue as a darker sibling but not yet consumed by any component — reserved for a future link-visited or link-active state.

### Tertiary — the accent
- **Sea Blue** (`#2E9FD6`): The lightest point of the client's logo mark. Icons, focus rings, and the single surface it is allowed to fill: the accent CTA band ("Send us your packing list"). This is the one place on the page where Sea Blue is a background rather than a line or a glyph — deliberately, so it stays a signal rather than becoming wallpaper.
- **Sea Blue Light** (`#6FC6E9`): A lighter tint used only on dark grounds — the hover state for `.cgs-btn--on-dark`, and text/eyebrow colour inside `.cgs-section--dark` bands, where full-strength Sea Blue would be too saturated against navy.

### Neutral
- **Ink** (`#0E1726`): All heading and body text on light backgrounds.
- **Slate** (`#4A5568`): Secondary/supporting text — descriptions, list copy, captions.
- **Muted Slate** (`#64708A`): Tertiary text — the quietest tone in the system, used sparingly. Darkened from an earlier `#7A869A`, which measured 3.7:1 on white and failed WCAG AA's 4.5:1 minimum for body-weight text (caught by `/impeccable critique` on the home page); this value passes at ~5:1 while keeping the same hue.
- **Hairline** (`#E4E8EF`): Card borders, list dividers, hover-state underlines. This is the system's primary separator — used far more than shadow.
- **Surface Tint** (`#F5F8FC`): The one tinted section background (`.cgs-section--tint`) and the description tile inside the services bento — a whisper of navy, not a distinct colour.
- **White** (`#FFFFFF`): Card and header backgrounds, and all text set on Deep Navy.

### Status (reserved)
- **Success Green** (`#1E9E6A`) and **Danger Red** (`#D64550`) are declared in the token set for future form-validation states (the lead form and admin dashboard are not yet built) but are not consumed by any shipped component today. Do not repurpose them for anything else when they do land.

### Named Rules
**The Single-Fill Rule.** Sea Blue fills a surface in exactly one place on any given page. Everywhere else, it is confined to links, icons, focus rings, and small accents. Its rarity is what makes it read as a signal.

**The No-Warm Rule.** No hue outside the navy-to-sea-blue axis appears anywhere in the system — not in an icon, a badge, or a hover state. This was a deliberate late-stage removal (a provisional amber token was dropped entirely once Maersk's own all-blue palette confirmed the direction); nothing should reintroduce warmth.

## Typography

**Display / Body / Label Font:** Plus Jakarta Sans (with `-apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif` fallback). One family for the entire system — no serif, no mono. Loaded at weights 400/500/600/700/800.

**Character:** Geometric, slightly condensed, and confident at large sizes — tight negative tracking (`-0.025em`) and a near-1.1 line-height on headlines make them feel dense and considered rather than airy. Body copy relaxes to 1.65–1.75 line-height so paragraphs stay readable at the smaller sizes the system uses for supporting text.

### Hierarchy
- **Display** (800, `clamp(32px, 5vw, 56px)`, line-height 1.12): The hero headline only. `text-wrap: balance` and `-0.025em` tracking so a multi-word headline never leaves a widow.
- **Headline** (800, `clamp(26px, 3.4vw, 40px)`, line-height 1.15): Every section heading (`<h2>`) — services, approach, fleet split, closing CTA. Same weight and tracking discipline as Display, one step down in size.
- **Title** (700, 18px, tracking `-0.01em`): Sub-headings inside components — the approach list's bolded step names, card titles.
- **Body** (400, 16px, line-height 1.75): Paragraph copy in section intros and descriptions. Capped around 46–58ch depending on context so lines stay scannable.
- **Label** (600, 14px, tracking `0.08em`, uppercase): Eyebrows and small overline tags — always uppercase, always the widest tracking in the system, so it reads as metadata rather than a heading.

On the home page specifically, headings additionally tighten to `line-height: 1.08` / `letter-spacing: -0.03em` (scoped to `body.page-home #main-content`) — a step further than the rest of the site, carried over from an internal design exploration and treated as the home page's own register rather than the site-wide default.

### Named Rules
**The Balance Rule.** Every multi-word heading in the system carries `text-wrap: balance`. A ragged one-word widow on the last line is treated as a bug, not a rounding error.

## Layout

Sections use Bootstrap's `container-fluid` with fixed horizontal padding (`px-4`) rather than a capped max-width container — content runs edge-to-edge within the padding at any viewport, there is no `1200px`-style ceiling defined anywhere in the system today.

Vertical section rhythm is a single token, `--cgs-section-y: clamp(56px, 7vw, 104px)`, applied as `padding-block` on every `.cgs-section`. Two-column layouts (`.cgs-approach`, `.cgs-split`) use an asymmetric fraction split (5fr/6fr or 6fr/5fr, never an even 1fr/1fr) with a `clamp(32px, 5vw, 72px)` gap, collapsing to a single column at 991.98px. The signature services grid (`.cgs-service-bento`) is a 4-column × 3-row CSS Grid with explicit line-based placement — a deliberately asymmetric bento (one large feature cell, one wide-short cell, one narrow-tall cell, two small stacked cells) rather than a uniform card row; it resets to a natural 2-column then 1-column stack below 991.98px and 575.98px.

Breakpoints follow Bootstrap's own scale as used throughout `cgs.css`: 575.98px, 767.98px, 991.98px, 1200px. The main header and services section additionally distinguish desktop (≥1200px, where the services section scroll-pins) from everything below it, where the same content displays in normal stacked flow instead.

## Elevation & Depth

The system is flat by default and uses shadow only as interaction feedback, not ambient decoration. Two shadow tokens exist (`--cgs-shadow`, `--cgs-shadow-lg`), both tinted navy rather than pure black, plus a handful of component-specific shadows (the primary button's own navy-tinted shadow) that follow the same tint discipline. Hairline borders (`--cgs-line`, `#E4E8EF`) do most of the separating work that a lesser system would hand to shadow — cards sit on a 1px border at rest and only pick up `--cgs-shadow-lg` plus a `-4px` lift on hover.

The one place the system reaches for glass rather than flat or shadowed is the hero's floating facts card (`.cgs-hero__facts`): a translucent navy fill (`rgba(10, 30, 92, .52)`) with `backdrop-filter: blur(10px)` and a 1px translucent white border, sitting on top of the hero photograph as a distinct floating layer. This is the system's only backdrop-filter use — deliberately singular, the same way Sea Blue's fill use is singular.

### Shadow Vocabulary
- **Ambient / resting** (`box-shadow: 0 8px 24px rgba(8, 34, 56, 0.08)`, `--cgs-shadow`): Not actually used at rest anywhere in the current build — see the Flat-at-Rest rule below. Reserved for a low-emphasis elevation need.
- **Hover / lifted** (`box-shadow: 0 16px 48px rgba(8, 34, 56, 0.14)`, `--cgs-shadow-lg`): Cards, the mobile nav panel, and submenu dropdowns on hover/open.
- **Action shadow** (`box-shadow: 0 8px 20px rgba(8, 34, 56, .28)`, deepening to `0 12px 28px rgba(8, 34, 56, .36)` on hover): Unique to `.cgs-btn--primary` — a stronger, more saturated version of the same navy tint, so the primary action visibly sits above the page.

### Named Rules
**The Flat-at-Rest Rule.** Nothing carries a shadow in its default state. Shadow only appears as a direct response to hover, focus, or an open state — it is feedback, not decoration.

**The Tinted-Shadow Rule.** Every shadow in the system is `rgba(8, 34, 56, …)` — navy, at varying opacity — never pure black. A generic `rgba(0,0,0,…)` shadow does not belong in this system.

**The No-Scrim Rule.** Photography (hero, video band, service feature cards) never sits behind a dark gradient overlay to buy text legibility. `text-shadow` does that work instead — a deliberate removal from an earlier iteration, so the client's own photography reads at full brightness.

## Shapes

One radius token, `--cgs-radius: 4px`, applies to every rectangular surface in the system — buttons, cards, the bento tiles, the hero facts card, form-shaped elements. It was chosen to match Maersk's own button radius as industry validation, and reads as industrial/precise rather than the softer 8–16px radii common to consumer SaaS.

Fully-round shapes are the only departure, and they are reserved for a specific role: pills (`border-radius: 100px`) for eyebrow labels and sector tags, and full circles (`border-radius: 50%`) for icon-only buttons — the header call icon, social links, the mobile burger, the floating WhatsApp/call stack. There is no intermediate "soft card" radius anywhere between 4px and fully round.

## Components

### Buttons
- **Shape:** 4px radius (`--cgs-radius`), never rounder.
- **Primary:** Deep Navy fill (`#082238`), white text, `15px 28px` padding, own navy-tinted action shadow. Hover deepens the shadow and swaps fill to Navy Hover (`#0E3A56`) — the brighter Sea Blue never appears as a button fill, keeping one unambiguous "this is the action" colour on any given screen.
- **Hover / Focus:** `translateY(-2px)` lift plus `scale(0.97)` on active, gated behind `(hover: hover) and (pointer: fine)` so touch devices never get a stuck hover state. Icons inside buttons additionally nudge `translateX(3px)` on hover.
- **Ghost:** Transparent fill, Deep Navy text, 1.5px Hairline border; hover swaps both border and text to Ocean Blue.
- **On-dark / Outline-light:** White-fill and outline-only variants for use on navy sections — On-dark hovers to Sea Blue Light, Outline-light hovers to a translucent white fill.

### Chips / Tags
- **Style:** Pill radius (100px), 1px Hairline border, transparent fill, Slate text. Used for sector tags and the hero eyebrow (which additionally carries a translucent navy background and blur, since it sits directly on photography rather than a flat section).
- **State:** Hover swaps border colour to Sea Blue and text to Ink/Deep Navy — no filled "selected" state exists yet, only a hover/link state.

### Cards / Containers
- **Corner Style:** 4px radius, matching every other rectangle in the system.
- **Background:** White on light sections; Deep Navy or Navy Hover on dark/feature cards (the services feature tile).
- **Shadow Strategy:** Flat at rest, `--cgs-shadow-lg` plus a 4px lift on hover — see Elevation & Depth.
- **Border:** 1px Hairline at rest on light cards; feature/dark cards have no border and rely on their fill for edge definition.
- **Internal Padding:** 22–24px, consistent across the generic card, bento cells, and card bodies.

### Navigation
- **Style:** White sticky header, Ink text at 600 weight, Ocean Blue on hover/active with an animated underline (`scaleX` on a 3px gradient bar). The Services dropdown expands as a `.cgs-submenu` positioned directly under its trigger, with an opacity/translateY transition — not an instant show/hide.
- **Mobile treatment:** A dropdown panel (`.cgs-mobile-panel`) that expands from the navbar itself, occupying the same interaction language as the desktop Services dropdown, rather than a side-drawer/offcanvas. A light navy scrim (`rgba(8, 34, 56, .32)`) dims the page behind it without a full-screen takeover.

### Services Bento (signature component)
The home page's services section is the system's most distinctive pattern: one service's content fills an entire asymmetric 4×3 grid (a large photo feature tile, a wide description tile, a tall secondary-image tile, and two small stacked tiles — a CTA and a numbered index like "01 / 05"). On desktop, the section scroll-pins: the grid stays fixed in the viewport while continued scrolling crossfades between services and advances a thin progress track, releasing to the next section once the last service has shown. Below desktop widths or under `prefers-reduced-motion`, the same five tiles simply display/hide per service in normal document flow — no pinning, no crossfade.

## Do's and Don'ts

### Do:
- **Do** keep Sea Blue (`#2E9FD6`) to at most one fill use per page; everywhere else, restrict it to links, icons, and focus rings (the Single-Fill Rule).
- **Do** use `text-shadow` — never a gradient scrim — when white type sits directly on a photograph (the No-Scrim Rule).
- **Do** keep every shadow navy-tinted (`rgba(8, 34, 56, …)`) and reserved for hover/interaction states, never applied at rest (the Flat-at-Rest and Tinted-Shadow Rules).
- **Do** use the 4px radius on every rectangular surface; reserve pill and full-circle shapes for tags, badges, and icon-only buttons.
- **Do** give every pressable element a `scale(0.97)` active state and a hover lift gated behind `(hover: hover) and (pointer: fine)`.
- **Do** route services, fleet items, resources, gallery images, and FAQ copy through the database tables rather than hard-coding them — the CMS exists so the client can edit this content without a developer.

### Don't:
- **Don't** introduce a second accent hue or any warm colour. The system's entire brand axis is Deep Navy to Sea Blue; Maersk's own all-blue palette was used as industry confirmation of this, not as a source to copy from (the No-Warm Rule).
- **Don't** add a dark gradient or scrim behind hero, video-band, or service-card photography — that pattern was deliberately removed in favour of `text-shadow`.
- **Don't** mix border-radius scales. There is one rectangle radius (4px) in the entire system; do not introduce an 8–16px "soft card" variant alongside it.
- **Don't** edit `assets/css/main.css` expecting it to change anything on the live site — it is not linked from any page (`cgs.css` is the only stylesheet that reaches the browser as brand CSS).
- **Don't** use `ease-in` for interface motion. Only `--ease-out` (`cubic-bezier(0.23, 1, 0.32, 1)`) and `--ease-in-out` are defined; `ease-in` delays exactly the first frame the user is watching.
