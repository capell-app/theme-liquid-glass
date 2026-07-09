# Liquid Glass

<!-- prettier-ignore-start -->

Translucent panels floating over a soft mint-to-lilac wash, near-black grotesque headlines, and a warm accent for the one action that matters. Liquid Glass is a free theme for launch pages, service pages, and lead journeys — the surface is the design, the copy sits on top of it.

![The Liquid Glass homepage: a near-black grotesque headline "Liquid Glass — A modern glass surface for launch pages" over a pale off-white page, with a thin white masthead carrying the site name above it.](screenshots/liquid-glass-homepage.png)

## Put it on your site

```bash
composer require capell-app/theme-liquid-glass
```

Then, in the admin:

1. Open **System → Themes**. Liquid Glass appears as an installed package definition.
2. Choose **Create theme** to turn that definition into a theme record you can edit.
3. Choose **Preview**, pick the site, page, and preset you want to look at, and open it in a new tab. The live site is untouched at this point.
4. Choose **Apply theme** when you are ready. Set **Activation scope** to **Global** to change the default theme for every site without its own override, or to **Selected sites** to change only the sites you name.

Applying a theme refreshes the frontend cache keys for the sites it affects, so the change shows immediately.

### The three presets

Liquid Glass ships three presets, and the **Preview** dialog lets you pick between them before anything goes live:

- **bright product launches** — the pale, high-contrast surface shown above.
- **editorial glass pages** — the tinted mint wash with panels layered over it.
- **darker graphite surfaces** — the same geometry on dark glass.

All three are Theme Studio tokens, not separate templates, so switching preset restyles the whole site without touching a Blade file.

### Start from the demo content

To get the pages below rather than an empty site:

```bash
php artisan capell:theme-liquid-glass-demo
```

The command takes `--url`, `--languages`, and `--sites`, so you can seed a single site or a whole multilingual set.

## The pages you get

### The lead page

The clearest look at the theme. A four-line headline in heavy grotesque, a mint-and-lilac gradient bleeding behind it, a filled dark-green **Get in touch** button paired with an outlined **View features**, and a raised white glass card holding the supporting copy. The contact route is presentational — no admin fields, package names, or editor URLs leak into the public page.

![The Liquid Glass contact page: a large four-line black headline "A lead path that stays polished through the glass" over a mint-to-lilac gradient, with a dark-green pill button and a white outlined button beneath, and a white glass card on the right showing a large green "G" monogram above the heading "Glass lead route".](screenshots/liquid-glass-contact.png)

### The card grid

Entries sit in white rounded cards inside a larger glass container, each with a landscape photo, a small green **PREVIEW** eyebrow, a title, and one line of summary. A three-column grid falls back to a ragged final row rather than stretching cards to fill it. The footer below splits into Preview, Content, and Support columns.

![The Liquid Glass directory grid: a pale mint page with a rounded glass container headed "Browse preview entries", holding three white cards of office photographs, each labelled PREVIEW with a title and one line of copy, above a three-column footer.](screenshots/liquid-glass-listing.png)

### Listing header

The listing page leads with the same masthead-and-headline block as the homepage, so a browse page and a launch page share one rhythm. The subhead does the work of telling the visitor which of the two they are on.

![The Liquid Glass listing page header: the heading "Listing — Liquid Glass" above the subhead "Browse content cards without leaving the glass" on a pale off-white page.](screenshots/liquid-glass-directory.png)

### Detail

A single content record, given the same headline treatment as the homepage rather than a separate article chrome. Grouped cards and proof sit under it.

![The Liquid Glass detail page: the heading "Marlow Studio launch page — Liquid Glass" above the subhead "Scan result groups inside clear translucent panels" on a pale page.](screenshots/liquid-glass-detail.png)

### Search

A pill-shaped search field with a ring-outline icon, then results as stacked white panels with a dark title bar and a lighter summary line. Everything is rounded to the same radius as the cards.

![The Liquid Glass search page: a rounded pill search field with a circular outline icon, above two stacked white result panels each showing a dark title bar and a grey summary bar, on an off-white background.](screenshots/liquid-glass-landing.png)

### Landing sections

Hero, then a three-up feature row. The hero panel is outlined rather than filled, and the single orange accent button is the only saturated colour on the page — which is exactly the point of it.

![The Liquid Glass landing sections: an outlined white hero panel on a mint background containing a dark title bar, a grey subtitle bar, an orange pill button and a pale green thumbnail, with three empty white feature cards in a row beneath.](screenshots/liquid-glass-search.png)

## On a phone

The masthead keeps just the site name, the headline wraps to three lines without dropping down the type scale, and the panels go full width with the same corner radius.

![The Liquid Glass homepage on a phone: the site name alone in the masthead, the headline wrapped over three lines, subhead and body copy stacked in a single narrow column.](screenshots/liquid-glass-homepage-mobile.png)

## Before you install

Liquid Glass extends the **Foundation** theme (`default`) rather than replacing it, and renders through the shared layout-builder container pipeline. It needs these packages present:

- `capell-app/core`
- `capell-app/theme-foundation`
- `capell-app/frontend`
- `capell-app/layout-builder`

Composer pulls them in for you. Liquid Glass declares no optional pairings and no conflicts.

Colours, fonts, and spacing are Theme Studio tokens, so you can change them under **Customize** without editing a Blade file. It is one of two free themes, alongside Foundation itself.

---

For the package boundary, runtime surfaces, and troubleshooting, see the [package README](../README.md).

<!-- prettier-ignore-end -->
