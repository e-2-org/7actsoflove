# 7 acts of love

## by Oreet Ashery

Say Cheese is artist Oreet Ashery's series of experimental, process based interactions with her alter-ego Marcus Fisher that took place in 7 bedrooms across the world. There is extensive documentation of Say Cheese in the Archive section. Ashery has also created a new piece especially for the web, a permanent interactive sound work that can be found in the ‘god-therapist session’ section.

## Redevelopment October 2026

7 acts of love was originally a PHP and MySQL site that needed the Flash 6 plugin, and it opened most of the archive in popup windows. It has been rebuilt to work in current browsers and on phones, with no server-side code, keeping the original text, images and sound:

- **The god-therapist session** (`session.html`, `js/therapy.js`) runs entirely in the browser. The therapist's voice was taken out of the Flash files into `audio/`. The text of each line, which used to be in a configuration file that no longer exists, was transcribed from the recordings. The branching between questions is the original script's. Sessions are no longer recorded anywhere.
- **The live residency** was a live chat with Marcus Fisher that needed a server, so it isn't part of the rebuilt site. It is still mentioned on the home page.
- **Popups** are now draggable windows over the page, sized like the originals. Galleries step to the next picture when you click on a picture.
- **Videos** were converted from Flash to MP4 (`video/`).
- **Layouts** keep the original pixel positions; on narrow screens the pages are zoomed out to fit, and the home page becomes a single column.
- **The site is built with Jekyll** for GitHub Pages, and the archive content is kept as one file per item:
  - `_letters/`, `_replies/` and `_saycheese_text/` hold the Say Cheese letters, replies and text, one file each, shown in the order given by `order`.
  - `_data/bibliography.yml`, `_data/photos.yml` and `_data/videos.yml` list the press cuttings, the Say Cheese photos by city, and the videos.
  - The bookshelf pages (`archive/index.html`, `archive/background.html`, `archive/saycheese/index.html`) describe their pictures and spines in their front matter, and are drawn by `_layouts/shelf.html`.
  - A link with `data-window="WIDTHxHEIGHT"` opens in a window (`js/site.js`).

To run it locally, use `bundle install` and then `bundle exec jekyll serve`. The site is then at http://localhost:4000/7actsoflove/.
