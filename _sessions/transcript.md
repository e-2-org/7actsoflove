# Session: porting 7 acts of love to a static site - transcript

**Date:** 16 October 2026
**Branch:** `html-port` (from `master`)
**Commits:** `6758fe2` (the god-therapist session, committed by the user); the Jekyll conversion and clean-up are staged but not yet committed

The summary is in [summary.md](summary.md).

This is a condensed record. The user's messages are quoted; Claude's work is summarised.

## 1. A fix to Contact first

> I've just noticed something in repos/contact/contact.html - when you move the mouse cursor upwards from one box to another just above it, the upper box expands down, but the previous box remains visible - can the upper box expand ABOVE the lower box? perhaps change the z-index temporrarily?

The box that was zooming and the one shrinking back both had `z-index: 100`, so whichever came later in the page was drawn on top. Claude gave `.box.is-zoomed` `z-index: 101` in `contact/css/scape.css`. That change is in the contact repo, not this one.

## 2. The god-therapist session

> I've got another project in repos/7actsoflove which was an interactive chat type thing - very simplistic - where users would enter a room and a therapist would ask them questions - I think they answered using a text box, and depending on their answers anothe audio file would be played. I can't remember the flow exactly. If this can be turned into something which will work in a modern browser with no server-side processing it would be amazing!

Claude worked out the flow from the PHP pages and `js/sevenacts.js`, and found that the file holding the therapist's text was missing. It then:
- wrote a SWF parser and pulled the 57 clips out as MP3s
- transcribed them with Whisper to rebuild the text
- wrote `session.html`, `css/therapy.css` and `js/therapy.js`, keeping the original branching
- pointed the home page menu at the new page

Testing in headless Chrome covered the bedroom and seat checks, the branching, exiting during the intro and the phone layout. The user committed this as `6758fe2` on a new branch, `html-port`.

## 3. Jekyll and clean-up

> I've created as new branch for the work and committed changes - please remove any unused files and convert to a jekyll site so the pages in archive/ are a bit easier to manage

Claude screenshotted the old pages in Chrome to record how they looked, then:
- converted the 8 Flash videos to MP4
- split the letters, replies and Say Cheese text into collections
- moved the bibliography, photos and videos into `_data/`
- wrote the shelf layout, `css/site.css` and `js/site.js`, using the contact site's windows as the model for the popups
- rewrote every archive page and the home page to use them
- deleted the server, Flash and popup files

Checking the built site found a few things, which were fixed:
- a favicon request at the server root, fixed with an icon link that uses the base URL
- a `#hash` that scrolled a letter up under its menu
- the home page text sitting slightly higher than before
- the broken NY press release link and the two photos that were never shown

Finally, Claude deleted the 30 images nothing referenced, added a README in the same style as contact's, and staged the changes.

## 4. Wrap-up

> Can you write up the sessions as before?

This file.
