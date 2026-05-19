---
version: alpha
name: Dailyve Premium Travel Design System (Cal.com Inspired)
description: A clean, premium travel booking interface anchored on a slate-blue canvas, using vibrant brand blue for primary actions, a deep navy-slate footer, and Space Grotesk display typography. Optimized for Vietnamese travelers (price visibility, clear ticket booking cards, trust).

colors:
  primary: '#2196F3'
  primary-active: '#1565C0'
  primary-disabled: '#E2E8F0'
  ink: '#0F172A'
  body: '#334155'
  muted: '#64748B'
  muted-soft: '#94A3B8'
  hairline: '#E2E8F0'
  hairline-soft: '#F1F5F9'
  canvas: '#FFFFFF'
  surface-soft: '#F8FAFC'
  surface-card: '#F1F5F9'
  surface-strong: '#CBD5E1'
  surface-dark: '#0F172A'
  surface-dark-elevated: '#1E293B'
  on-primary: '#FFFFFF'
  on-dark: '#FFFFFF'
  on-dark-soft: '#94A3B8'
  brand-accent: '#F59E0B'
  success: '#16A34A'
  warning: '#F59E0B'
  error: '#DC2626'
  badge-orange: '#FB923C'
  badge-pink: '#EC4899'
  badge-violet: '#8B5CF6'
  badge-emerald: '#34D399'

typography:
  display-xl:
    fontFamily: 'Space Grotesk, sans-serif'
    fontSize: 64px
    fontWeight: 600
    lineHeight: 1.05
    letterSpacing: -2px
  display-lg:
    fontFamily: 'Space Grotesk, sans-serif'
    fontSize: 48px
    fontWeight: 600
    lineHeight: 1.1
    letterSpacing: -1.5px
  display-md:
    fontFamily: 'Space Grotesk, sans-serif'
    fontSize: 36px
    fontWeight: 600
    lineHeight: 1.15
    letterSpacing: -1px
  display-sm:
    fontFamily: 'Space Grotesk, sans-serif'
    fontSize: 28px
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: -0.5px
  title-lg:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 22px
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: -0.3px
  title-md:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 18px
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: 0
  title-sm:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 16px
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: 0
  body-md:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 16px
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: 0
  body-sm:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: 0
  caption:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 13px
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: 0
  code:
    fontFamily: 'JetBrains Mono, ui-monospace, monospace'
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: 0
  button:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 14px
    fontWeight: 600
    lineHeight: 1
    letterSpacing: 0
  nav-link:
    fontFamily: 'Be Vietnam Pro, sans-serif'
    fontSize: 14px
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: 0

rounded:
  xs: 4px
  sm: 6px
  md: 8px
  lg: 12px
  xl: 16px
  pill: 9999px
  full: 9999px

spacing:
  xxs: 4px
  xs: 8px
  sm: 12px
  md: 16px
  lg: 24px
  xl: 32px
  xxl: 48px
  section: 96px

components:
  button-primary:
    backgroundColor: '{colors.primary}'
    textColor: '{colors.on-primary}'
    typography: '{typography.button}'
    rounded: '{rounded.md}'
    padding: 12px 20px
    height: 40px
  button-primary-active:
    backgroundColor: '{colors.primary-active}'
    textColor: '{colors.on-primary}'
    rounded: '{rounded.md}'
  button-primary-disabled:
    backgroundColor: '{colors.primary-disabled}'
    textColor: '{colors.muted}'
    rounded: '{rounded.md}'
  button-secondary:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.primary}'
    typography: '{typography.button}'
    rounded: '{rounded.md}'
    padding: 12px 20px
    height: 40px
  button-icon-circular:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    rounded: '{rounded.full}'
    size: 36px
  button-text-link:
    backgroundColor: transparent
    textColor: '{colors.primary}'
    typography: '{typography.button}'
  text-link:
    backgroundColor: transparent
    textColor: '{colors.primary}'
    typography: '{typography.body-md}'
  top-nav:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    typography: '{typography.nav-link}'
    height: 64px
  nav-pill-group:
    backgroundColor: '{colors.surface-soft}'
    textColor: '{colors.ink}'
    typography: '{typography.nav-link}'
    rounded: '{rounded.pill}'
    padding: 6px
  hero-band:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    typography: '{typography.display-xl}'
    padding: 96px
  hero-app-mockup-card:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    rounded: '{rounded.xl}'
  feature-card:
    backgroundColor: '{colors.surface-card}'
    textColor: '{colors.ink}'
    typography: '{typography.title-md}'
    rounded: '{rounded.lg}'
    padding: 32px
  feature-icon-card:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    typography: '{typography.title-sm}'
    rounded: '{rounded.lg}'
    padding: 24px
  product-mockup-card:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    rounded: '{rounded.lg}'
    padding: 24px
  testimonial-card:
    backgroundColor: '{colors.surface-card}'
    textColor: '{colors.ink}'
    typography: '{typography.body-md}'
    rounded: '{rounded.lg}'
    padding: 24px
  pricing-tier-card:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    typography: '{typography.title-lg}'
    rounded: '{rounded.lg}'
    padding: 32px
  pricing-tier-card-featured:
    backgroundColor: '{colors.surface-dark}'
    textColor: '{colors.on-dark}'
    typography: '{typography.title-lg}'
    rounded: '{rounded.lg}'
    padding: 32px
  text-input:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.ink}'
    typography: '{typography.body-md}'
    rounded: '{rounded.md}'
    padding: 10px 14px
    height: 40px
  text-input-focused:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.primary}'
    rounded: '{rounded.md}'
  category-tab:
    backgroundColor: transparent
    textColor: '{colors.muted}'
    typography: '{typography.nav-link}'
    padding: 8px 14px
    rounded: '{rounded.md}'
  category-tab-active:
    backgroundColor: '{colors.canvas}'
    textColor: '{colors.primary}'
    typography: '{typography.nav-link}'
    rounded: '{rounded.md}'
  avatar-circle:
    backgroundColor: '{colors.surface-card}'
    textColor: '{colors.ink}'
    rounded: '{rounded.full}'
    size: 36px
  badge-pill:
    backgroundColor: '{colors.surface-card}'
    textColor: '{colors.ink}'
    typography: '{typography.caption}'
    rounded: '{rounded.pill}'
    padding: 4px 12px
  rating-stars:
    backgroundColor: transparent
    textColor: '{colors.brand-accent}'
    typography: '{typography.caption}'
  cta-band-light:
    backgroundColor: '{colors.surface-card}'
    textColor: '{colors.ink}'
    typography: '{typography.display-sm}'
    rounded: '{rounded.lg}'
    padding: 48px
  footer:
    backgroundColor: '{colors.surface-dark}'
    textColor: '{colors.on-dark-soft}'
    typography: '{typography.body-sm}'
    padding: 64px
---

## Overview

Dailyve's premium travel booking surface is a clean, friendly modern travel interface — white canvas (`{colors.canvas}` — #ffffff) with vibrant blue primary CTAs (`{colors.primary}` — #2196F3), custom **Space Grotesk** display typography, and `{colors.surface-card}` (#F1F5F9) light-slate cards holding trip and route selection elements. The system reads as confidently engineered, highly trustworthy, and extremely reliable — every band has clear hierarchy, generous whitespace, and a single primary action.

Type voice splits cleanly into two roles: **Space Grotesk** (the brand's custom geometric display face — used for h1, h2, h3, and hero headlines) and **Be Vietnam Pro** (used for everything else — body, buttons, nav, captions, travel info). Space Grotesk uses weight 600 with negative letter-spacing (-0.5px to -2px depending on size) — it feels modern, slightly condensed, distinctly tech-forward, and premium.

Component voltage comes from **travel booking UI fragments shown directly inside cards** — ticket layout grids, trip cards, seat selection previews, route timelines. Dailyve doesn't paint marketing illustrations; it shows the actual travel booking chrome at small scale embedded in the marketing flow.

The footer flips to `{colors.surface-dark}` (#0F172A) — a deep, premium navy-slate that visually closes every long-scroll page. The footer is the only dark surface in the system; everything above stays white-with-light-gray-cards.

**Key Characteristics:**

- White canvas with vibrant blue primary CTA (`{colors.primary}` — #2196F3). Buttons are `{rounded.md}` (8px) with confident weight-600 labels. Standard friendly-SaaS button.
- Custom `Space Grotesk` display typeface for headlines. Negative letter-spacing on display sizes — geometric, precise, slightly condensed.
- Light-slate card surfaces (`{colors.surface-card}` — #F1F5F9) for trip cards, feature cards, testimonials, and standard route listings. The featured/active ticket card flips to `{colors.surface-dark}` (the only dark card on light pages).
- Product UI fragments embedded directly in cards — Dailyve shows real schedule pickers, ticket options, and seat grids inside its marketing cards. Brand voltage from real booking chrome at small scale.
- Nav-pill-group (`{component.nav-pill-group}`) — a small pill-radius wrapper around grouped nav segments (e.g., the sub-nav switcher between vehicle types "Xe khách" / "Tàu hỏa" / "Máy bay"). The pill wrapper is one of the system's signature interactive components.
- Avatars are circular (`{rounded.full}`), 36px diameter, used in review rows and team-listing surfaces.
- Footer is deep navy-slate (`{colors.surface-dark}` — #0F172A) with light text (`{colors.on-dark-soft}` — #94A3B8). The dark footer closes every page even though the body above is white.
- Spacing rhythm is `{spacing.section}` (96px) between major bands — tight enough to feel modern-SaaS but generous enough to breathe.
- Border radius is hierarchical: `{rounded.md}` (8px) for buttons + inputs, `{rounded.lg}` (12px) for content cards, `{rounded.xl}` (16px) for the hero app-mockup container, `{rounded.pill}` for nav-pill-group + badges, `{rounded.full}` for avatars + icon buttons.

## Colors

### Brand & Accent

- **Primary** (`{colors.primary}` — #2196F3): The dominant brand color representing safety, peace, and trust. Used on primary booking CTAs and focus outlines. Press state shifts to `{colors.primary-active}` (#1565C0).
- **Brand Accent** (`{colors.brand-accent}` — #F59E0B): Used sparely on discount badges, urgency warnings ("Còn 2 chỗ!"), and hot deals. It adds energetic color voltage to drive sales conversions.
- **Badge Pastels** — A small pastel set for vehicle type badges and customer story avatars: `{colors.badge-orange}` (#FB923C), `{colors.badge-pink}` (#EC4899), `{colors.badge-violet}` (#8B5CF6), `{colors.badge-emerald}` (#34D399).

### Surface

- **Canvas** (`{colors.canvas}` — #FFFFFF): The default page floor.
- **Surface Soft** (`{colors.surface-soft}` — #F8FAFC): Nav-pill-group background, very-soft section dividers.
- **Surface Card** (`{colors.surface-card}` — #F1F5F9): Trip cards, testimonial cards, badge pills, default avatar fills.
- **Surface Strong** (`{colors.surface-strong}` — #CBD5E1): Hairline border alternative; disabled button background.
- **Surface Dark** (`{colors.surface-dark}` — #0F172A): The footer background — the only dark surface on every page. Also used for the featured pricing tier card.
- **Surface Dark Elevated** (`{colors.surface-dark-elevated}` — #1E293B): Used for nested cards inside the dark footer or featured booking card.
- **Hairline** (`{colors.hairline}` — #E2E8F0): The 1px border tone on light surfaces. Used on input borders, table dividers, content card outlines.
- **Hairline Soft** (`{colors.hairline-soft}` — #F1F5F9): A barely-visible divider used between sections that share the white canvas.

### Text

- **Ink** (`{colors.ink}` — #0F172A): All headlines and primary text. A deep navy-black that looks extremely professional and premium.
- **Body** (`{colors.body}` — #334155): Default running-text color.
- **Muted** (`{colors.muted}` — #64748B): Secondary text — sub-headings, route descriptions, footer body.
- **Muted Soft** (`{colors.muted-soft}` — #94A3B8): Tertiary text — captions, seat labels, fine-print, copyright lines.
- **On Primary / On Dark** (`{colors.on-primary}` / `{colors.on-dark}` — #FFFFFF): Text on primary buttons and dark footer.
- **On Dark Soft** (`{colors.on-dark-soft}` — #94A3B8): Footer body text — slightly muted slate-400 for the link rows.

### Semantic

- **Success** (`{colors.success}` — #16A34A): Confirmed tickets, successful payments, available seat indicator.
- **Warning** (`{colors.warning}` — #F59E0B): Promotional banners, expiring offers.
- **Error** (`{colors.error}` — #DC2626): Cancelled bookings, sold-out tickets.

## Typography

### Font Family

The system runs **Space Grotesk** for display + brand wordmark and **Be Vietnam Pro** (+ Inter) for everything else. Space Grotesk is the brand's geometric display typeface — slightly condensed, weight 600, negative letter-spacing. Be Vietnam Pro handles body, buttons, navigation, captions, and ticket data. The fallback stack walks `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif` for both families.

The split is functional:

- Space Grotesk (display, 600 weight, -0.5 to -2px tracking) — h1, h2, h3
- Be Vietnam Pro / Inter (body + UI, 400-600 weight, 0 letter-spacing) — paragraphs, labels, buttons, nav, prices

### Hierarchy

| Token                     | Size | Weight | Line Height | Letter Spacing | Use                                                                 |
| ------------------------- | ---- | ------ | ----------- | -------------- | ------------------------------------------------------------------- |
| `{typography.display-xl}` | 64px | 600    | 1.05        | -2px           | Homepage h1 ("Đặt vé xe, tàu, máy bay dễ dàng cùng Dailyve") — Space Grotesk |
| `{typography.display-lg}` | 48px | 600    | 1.1         | -1.5px         | Section heads ("Hành trình phổ biến & Hãng vận chuyển") — Space Grotesk |
| `{typography.display-md}` | 36px | 600    | 1.15        | -1px           | Sub-section heads, card titles — Space Grotesk                           |
| `{typography.display-sm}` | 28px | 600    | 1.2         | -0.5px         | CTA-band heads, price numbers — Space Grotesk                      |
| `{typography.title-lg}`   | 22px | 600    | 1.3         | -0.3px         | Ticket/Route names — Be Vietnam Pro                                     |
| `{typography.title-md}`   | 18px | 600    | 1.4         | 0              | Trip card titles, intro paragraphs                               |
| `{typography.title-sm}`   | 16px | 600    | 1.4         | 0              | Small card titles, list labels                                      |
| `{typography.body-md}`    | 16px | 400    | 1.5         | 0              | Default running-text                                                |
| `{typography.body-sm}`    | 14px | 400    | 1.5         | 0              | Footer body, ticket details, fine-print                             |
| `{typography.caption}`    | 13px | 500    | 1.4         | 0              | Badge labels, seat codes, captions                                  |
| `{typography.code}`       | 14px | 400    | 1.5         | 0              | Ticket codes, API examples — JetBrains Mono                        |
| `{typography.button}`     | 14px | 600    | 1.0         | 0              | Standard button labels                                              |
| `{typography.nav-link}`   | 14px | 500    | 1.4         | 0              | Top-nav menu items                                                  |

### Principles

Space Grotesk is the brand voice — every display headline uses it. Be Vietnam Pro handles the supporting type. The boundary is strict: never put body copy in Space Grotesk, never put a display headline in Be Vietnam Pro. Space Grotesk without negative letter-spacing reads as off-brand — the -0.5 to -2px tracking is part of the voice.

Display weight stays at 600 across all sizes — never 700, never 500. The middle weight is what makes Space Grotesk feel modern and confident without becoming bombastic.

### Note on Font Substitutes

If Space Grotesk is unavailable, **Be Vietnam Pro** or **Inter** at weight 700 with -0.04em letter-spacing is a usable approximation.

## Layout

### Spacing System

- **Base unit:** 4px.
- **Tokens:** `{spacing.xxs}` 4px · `{spacing.xs}` 8px · `{spacing.sm}` 12px · `{spacing.md}` 16px · `{spacing.lg}` 24px · `{spacing.xl}` 32px · `{spacing.xxl}` 48px · `{spacing.section}` 96px.
- **Section padding:** `{spacing.section}` (96px) — the universal vertical rhythm between editorial bands.
- **Card internal padding:** `{spacing.xl}` (32px) for content cards and booking selection containers; `{spacing.lg}` (24px) for trip cards, testimonial cards, and seat selectors.
- **Gutters:** `{spacing.lg}` (24px) between cards in 3-up grids; `{spacing.md}` (16px) inside footer columns.

### Grid & Container

- **Max content width:** ~1200px centered on marketing and search pages.
- **Editorial body:** Single 12-column grid; hero band often uses 7/5 split (h1 left, ticket booking card right).
- **Trip card grids:** 3-up at desktop, 2-up at tablet, 1-up at mobile.
- **Booking search grid:** 4-up at desktop, 2-up at tablet, 1-up at mobile.
- **Footer:** 4-column link list at desktop, wrapping to 2-up at tablet, 1-up at mobile.

### Whitespace Philosophy

Dailyve uses generous but not excessive whitespace — section padding sits at 96px (modern-SaaS standard), and card internal padding stays at 32px. The rhythm is calibrated for fast scanning: every band has a single h1 + h2 + supporting cards, never densely packed lists. The result reads as confident-not-shouting.

## Elevation & Depth

| Level              | Treatment                                            | Use                                                                                                                        |
| ------------------ | ---------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| Flat               | No shadow, no border                                 | Body sections, top nav, hero bands                                                                                         |
| Soft hairline      | 1px `{colors.hairline}` border                       | Inputs, table dividers, occasionally on cards                                                                              |
| Card surface       | `{colors.surface-card}` background — no shadow       | Trip cards, testimonials                                                                                                   |
| Subtle drop shadow | Faint shadow at low alpha                            | Booking cards, hover-elevated states (the system uses `0 1px 2px rgba(0,0,0,0.05)` and `0 4px 12px rgba(0,0,0,0.08)`)     |
| Featured tier      | `{colors.surface-dark}` background, no shadow needed | The featured route / luxury limousine ticket inverts to dark surface — color contrast does the elevation work            |

The elevation philosophy is **soft and modern** — small drop shadows on elevated cards, color-block contrast for emphasis. No heavy shadows, no neumorphism, no glassmorphism.

### Decorative Depth

- Booking widgets and trip timeline fragments embedded inside marketing cards carry their own internal shadows from the product UI itself — these are not system tokens, they're product chrome shown as content.
- Avatar circles in testimonial sections sometimes carry pastel fill colors (`{colors.badge-orange}`, `{colors.badge-pink}`, etc.) — adds a small chromatic flourish without breaking the clean brand voice.

## Shapes

### Border Radius Scale

| Token            | Value        | Use                                                                       |
| ---------------- | ------------ | ------------------------------------------------------------------------- |
| `{rounded.xs}`   | 4px          | Almost no use — reserved for badge accents                                |
| `{rounded.sm}`   | 6px          | Small inline buttons, dropdown items                                      |
| `{rounded.md}`   | 8px          | Standard CTA buttons, text inputs, vehicle selectors                      |
| `{rounded.lg}`   | 12px         | Content cards (trip cards, testimonial cards, pricing tier cards)        |
| `{rounded.xl}`   | 16px         | Hero booking-mockup card (a slightly larger radius for the marquee component) |
| `{rounded.pill}` | 9999px       | Nav-pill-group, badge pills                                               |
| `{rounded.full}` | 9999px / 50% | Avatars, icon buttons                                                     |

### Photography Geometry

Avatar photos use `{rounded.full}` (perfect circles) at 36px or 40px. Trip timelines and vehicle icons inside cards retain their native shapes. Hero illustration zones use 16:9 or 4:3 ratios with `{rounded.xl}` corners.

## Components

### Top Navigation

**`top-nav`** — White nav bar pinned to the top of every page. 64px tall, `{colors.canvas}` background. Carries the Dailyve wordmark + logo at left, primary horizontal menu (Đặt vé, Khuyến mãi, Hướng dẫn, Liên hệ, Đối tác) center, right-side cluster with "Đăng nhập" text-link, "Đăng ký" `{component.button-primary}`. Menu items in `{typography.nav-link}` (Be Vietnam Pro 14px / 500).

**`nav-pill-group`** — A small pill-radius wrapper around 2-3 sub-nav segments (e.g., the transport-mode switcher between "Xe khách" / "Tàu hỏa" / "Máy bay"). Background `{colors.surface-soft}` with internal padding 6px, rounded `{rounded.pill}`. Active segment renders as a white-canvas pill with a subtle drop shadow inside the wrapper. The pill-in-pill treatment is one of Dailyve's signature interactive components.

### Buttons

**`button-primary`** — The signature primary CTA. Background `{colors.primary}` (#2196F3), text `{colors.on-primary}`, type `{typography.button}` (Be Vietnam Pro 14px / 600), padding 12px × 20px, height 40px, rounded `{rounded.md}` (8px). Active state `button-primary-active` shifts to `{colors.primary-active}` (#1565C0).

**`button-secondary`** — White button with hairline outline. Background `{colors.canvas}`, text `{colors.primary}`, 1px hairline border in `{colors.primary}`, same padding + height + radius as primary.

**`button-icon-circular`** — 36 × 36px circular icon button. Background `{colors.canvas}`, hairline border, ink-color icon. Used for share, "view more", carousel arrows.

**`button-text-link`** — Inline text button, no background. Used for "Đăng nhập" in the top nav and inline CTA links inside cards.

**`text-link`** — Inline body links in `{colors.primary}`. Underlined on hover.

### Cards & Containers

**`hero-band`** — White-canvas hero with a 7-5 grid: h1 + sub-headline + search selector on the left, `{component.hero-app-mockup-card}` on the right. Vertical padding `{spacing.section}` (96px).

**`hero-app-mockup-card`** — A larger travel-UI mockup card showing the actual Dailyve ticket booking widget with calendar date picker, time slots, and a primary "Tìm chuyến xe" button inside. Background `{colors.canvas}`, 1px hairline border, rounded `{rounded.xl}` (16px), subtle drop shadow. Used as the hero's right-side artifact.

**`feature-card`** — Used in 3-up feature grids ("Đặt vé an toàn và nhanh chóng"). Background `{colors.surface-card}` (#F1F5F9), rounded `{rounded.lg}` (12px), internal padding `{spacing.xl}` (32px). Carries a small icon at top, an `{typography.title-md}` headline, and a body description in `{typography.body-md}`.

**`feature-icon-card`** — A simpler card variant used in 4-up feature grids on lower-density bands. Background `{colors.canvas}` with hairline border, rounded `{rounded.lg}`, padding `{spacing.lg}` (24px). Carries a small icon, `{typography.title-sm}` title, short description.

**`product-mockup-card`** — A card showing actual Dailyve product UI fragments (trip list, seat layout, booking details, payment methods). Background `{colors.canvas}`, rounded `{rounded.lg}`, padding `{spacing.lg}` (24px).

**`testimonial-card`** — Used in customer-quote grids. Background `{colors.surface-card}`, rounded `{rounded.lg}` (12px), padding `{spacing.lg}` (24px). Top row carries a `{component.avatar-circle}` + name + role; below sits the testimonial quote in `{typography.body-md}`.

**`pricing-tier-card`** — Standard ticket option card. Background `{colors.canvas}`, rounded `{rounded.lg}` (12px), padding `{spacing.xl}` (32px). Carries the vehicle type in `{typography.title-lg}`, price in `{typography.display-sm}`, key services in `{typography.body-md}`, and a `{component.button-primary}` at the bottom.

**`pricing-tier-card-featured`** — The premium/luxury option (typically "Limousine VIP"). Background flips to `{colors.surface-dark}` (#0F172A), text inverts to `{colors.on-dark}`. The deep navy surface IS the premium signal — no accent border, no badge, no scale shift.

### Inputs & Forms

**`text-input`** — Standard text input for trip locations or date fields. Background `{colors.canvas}`, text `{colors.ink}`, type `{typography.body-md}`, rounded `{rounded.md}` (8px), padding 10px × 14px, height 40px. 1px hairline border in `{colors.hairline}`.

**`text-input-focused`** — Focus state. Border thickens or shifts to `{colors.primary}` for emphasis.

### Tags / Badges

**`badge-pill`** — Small pill label used for discount tags ("-20%", "Hôm nay", "Bán chạy") and pastel-fill avatar substitutes. Background `{colors.surface-card}` or one of the badge pastels (`{colors.badge-orange}`, `{colors.badge-pink}`, etc.), text `{colors.ink}`, type `{typography.caption}` (13px / 500), rounded `{rounded.pill}`, padding 4px × 12px.

**`avatar-circle`** — 36px diameter, rounded `{rounded.full}`. Either holds a photo or a pastel fill with initials in `{typography.caption}`.

**`rating-stars`** — Inline star rating in `{colors.brand-accent}` (#F59E0B). Used near testimonial avatars to display a 5-star satisfaction score.

### Tab / Filter

**`category-tab`** + **`category-tab-active`** — Used inside the nav-pill-group. Inactive: transparent background, `{colors.muted}` text. Active: `{colors.canvas}` background, `{colors.primary}` text, subtle drop shadow inside the pill-group wrapper. Padding 8px × 14px, rounded `{rounded.md}`.

### CTA / Footer

**`cta-band-light`** — A pre-footer "Trải nghiệm hành trình tuyệt vời" CTA card. Background `{colors.surface-card}`, rounded `{rounded.lg}`, padding `{spacing.xxl}` (48px). Carries an h2 in `{typography.display-sm}`, a sub-line, and a `{component.button-primary}` centered.

**`footer`** — Dark navy footer that closes every page. Background `{colors.surface-dark}` (#0F172A), text `{colors.on-dark-soft}`. 4-column link list at desktop covering Hãng xe / Tuyến phổ biến / Về chúng tôi / Hỗ trợ. Vertical padding 64px. The Dailyve wordmark sits at the top-left in `{colors.on-dark}`. The footer is the only dark surface on every page — the deliberate inversion visually closes the page.

## Do's and Don'ts

### Do

- Reserve `{colors.primary}` (#2196F3) for primary action buttons, book-now CTAs, and active navigation links.
- Use Space Grotesk for every display headline (h1, h2, h3). Pair with Be Vietnam Pro/Inter body. Never blur the boundary.
- Apply negative letter-spacing on display sizes (-0.5 to -2px). Space Grotesk without it reads as off-brand.
- Use `{component.feature-card}` (light slate-gray) and `{component.product-mockup-card}` (white with chrome) deliberately.
- Embed real travel booking UI fragments inside marketing cards. Don't paint cartoon illustrations when you can show the clean booking interface itself.
- Keep avatar circles at 36px, perfect circles, sometimes with pastel fills. Avatars are the only place where badge pastels appear.
- Use `{component.nav-pill-group}` for vehicle/route switches. The pill-in-pill treatment is signature.
- End every page with the deep navy-slate footer (`{colors.surface-dark}` — #0F172A).

### Don't

- Don't use accent colors (`{colors.brand-accent}`, badge pastels) on primary CTAs. The action layer is strictly brand blue and white.
- Don't bold display weight beyond 600. Space Grotesk at 700 reads as bombastic.
- Don't use rounded radius beyond `{rounded.xl}` (16px) on cards. Larger radii read as consumer-app, not professional travel booking software.
- Don't put dark surface cards anywhere except the footer and the featured luxury limousine ticket option. The dark surface is a deliberate, scarce signal.
- Don't repeat the same surface mode in two consecutive bands. Dailyve's pacing alternates white → light-slate → white → product-mockup-card → white → dark-footer.
- Don't add hover state styling beyond what the system already encodes.

## Responsive Behavior

### Breakpoints

| Name    | Width       | Key Changes                                                                                                                    |
| ------- | ----------- | ------------------------------------------------------------------------------------------------------------------------------ |
| Mobile  | < 768px     | Hamburger nav; hero h1 64→32px; hero-app-mockup-card stacks below content; feature grids 1-up; routes 1-up; footer 4 cols → 1   |
| Tablet  | 768–1024px  | Top nav stays horizontal but tightens; nav-pill-group wraps; trip cards 2-up; routes 2-up                                      |
| Desktop | 1024–1440px | Full top-nav with all menu items; 3-up trip cards; 4-up route selectors                                                       |
| Wide    | > 1440px    | Same as desktop with more outer breathing room; max content width caps at 1200px                                               |

### Touch Targets

- `{component.button-primary}` at minimum 40 × 40px.
- `{component.button-icon-circular}` at exactly 36 × 36.
- `{component.text-input}` height is 40px.
- `{component.category-tab}` rendered inside nav-pill-group has 8 × 14 padding; effective tap area meets 44px+ with the surrounding pill.

### Collapsing Strategy

- Top nav collapses to hamburger at < 768px; menu opens as a full-screen sheet.
- Hero band's 7-5 grid collapses to single-column on mobile — h1 + sub-head + buttons first, then the mockup card below.
- Grids reduce columns rather than scaling cards down.
- Ticket options collapse 4 → 2 → 1; featured luxury limousine tier dark surface stays visually distinct at every breakpoint.
- Nav-pill-group wraps to multi-row on tablet if the segments don't fit horizontally.
- Avatar + testimonial card layouts stay grid-aligned at every breakpoint.

### Image Behavior

- Product UI fragments inside cards retain native aspect ratios; the cards themselves resize.
- Avatar photos crop to circles at every breakpoint.
- Hero app-mockup card scales proportionally on mobile.

## Iteration Guide

1. Focus on ONE component at a time. Reference its YAML key directly (`{component.feature-card}`, `{component.pricing-tier-card-featured}`).
2. Variants of an existing component (`-active`, `-disabled`, `-focused`) live as separate entries in `components:`.
3. Use `{token.refs}` everywhere — never inline hex.
4. Never document hover. Default and Active/Pressed states only.
5. Display headlines stay Space Grotesk 600 with negative letter-spacing. Body stays Be Vietnam Pro/Inter 400.
6. The dark footer is the only dark surface on most pages. Don't add other dark cards casually.
7. When in doubt about emphasis: bigger Space Grotesk before bolder Space Grotesk.

## Known Gaps

- Button styles are documented from screenshot ground-truth + standard Space Grotesk / Be Vietnam Pro baselines.
- Space Grotesk is available as a public Google Web Font.
- The badge pastel set is documented from observed avatar fill colors.
- Animation and transition timings (timeline selection, seat picking reveal) are not in scope.
- Form validation states beyond `{component.text-input-focused}` are not extracted.
- Avatar photos in testimonial sections sometimes carry pastel circular fills with initials instead of photographs; both treatments coexist on the same page.
