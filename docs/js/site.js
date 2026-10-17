/*
site.js
7 acts of love - behaviour shared by every page (replaces x.js, sevenacts.js and archive.js)

 - stages (.stage[data-fit]) keep their original pixel layout and are zoomed out
   to fit narrow screens
 - pointing at a link with data-show="key" shows the .group with data-group="key"
   on its stage, in place of the group with data-group="" (the bookshelf hovers)
 - [data-deal] places its children in the listed slots at random, and .drift
   groups move sideways at random, as the bibliography and photos pages did
 - .tabs show one .tab-panel at a time, chosen by the URL's #hash
 - a link with data-window="WIDTHxHEIGHT" opens in a window over the page, in
   place of the original popup windows; a page that is itself in a window opens
   its links in the top page's windows
*/
(function () {
	'use strict';

	document.documentElement.classList.add('js');

	function clamp(v, lo, hi) { return Math.max(lo, Math.min(hi, v)); }
	function viewW() { return document.documentElement.clientWidth; }
	function viewH() { return document.documentElement.clientHeight; }
	function each(sel, fn, root) { Array.prototype.forEach.call((root || document).querySelectorAll(sel), fn); }

	/* ---------- stages ---------- */

	function zoomOf(stage) { return +stage.style.zoom || 1; }

	// the space either side of a stage, in the stage's own pixels
	function sideMargin(stage) {
		var z = zoomOf(stage);
		return Math.max(0, (viewW() - stage.offsetWidth * z) / 2) / z;
	}

	function fitStages() {
		each('.stage[data-fit]', function (stage) {
			var w = parseFloat(stage.style.width);
			var avail = viewW() - 16;
			stage.style.zoom = w > avail ? (avail / w).toFixed(4) : '';
		});
	}

	/* ---------- hover swaps ---------- */

	each('[data-hover-stage]', function (stage) {
		function show(key) {
			each('.group[data-group]', function (g) { g.hidden = g.dataset.group !== key; }, stage);
		}
		each('[data-show]', function (el) {
			var on = function () { show(el.dataset.show); };
			var off = function () { show(''); };
			el.addEventListener('mouseenter', on);
			el.addEventListener('mouseleave', off);
			el.addEventListener('focus', on);
			el.addEventListener('blur', off);
		}, stage);
	});

	/* ---------- random placement ---------- */

	function random(n) { return Math.floor(Math.random() * n); }

	function shuffle(list) {
		for (var i = list.length - 1; i > 0; i--) {
			var j = random(i + 1), t = list[i];
			list[i] = list[j];
			list[j] = t;
		}
		return list;
	}

	// bibliography: deal the blobs into the slots; ones near the edges spill
	// out into the space beside the page
	function deal() {
		each('[data-deal]', function (box) {
			var slots = shuffle(JSON.parse(box.dataset.deal));
			var margin = sideMargin(box.closest('.stage'));
			Array.prototype.forEach.call(box.children, function (el, i) {
				var x = slots[i][0];
				if (x > 300) x += random(margin);
				else if (x < 100) x -= random(margin);
				el.style.left = x + 'px';
				el.style.top = slots[i][1] + 'px';
			});
		});
	}

	// photos: each city drifts to its side by up to the space beside the page
	function drift() {
		each('.drift[data-drift]', function (el) {
			var margin = sideMargin(el.closest('.stage'));
			el.style.translate = (+el.dataset.drift * random(margin)) + 'px 0';
		});
	}

	/* ---------- tabs ---------- */

	each('.tabs', function (tabs) {
		var panels = Array.prototype.slice.call(tabs.querySelectorAll('.tab-panel'));
		function update() {
			var id = decodeURIComponent(location.hash.slice(1));
			var current = panels.filter(function (p) { return p.id === id; })[0] || panels[0];
			var i = panels.indexOf(current);
			panels.forEach(function (p) {
				if (p === current) return;
				p.hidden = true;
				each('video', function (v) { v.pause(); }, p);
			});
			current.hidden = false;
			each('.tab-nav a', function (a) {
				var step = a.dataset.step;
				if (step) {
					var to = panels[i + +step];
					a.hidden = !to;
					if (to) a.setAttribute('href', '#' + to.id);
				} else if (a.getAttribute('href') === '#' + current.id) {
					a.setAttribute('aria-current', 'true');
				} else {
					a.removeAttribute('aria-current');
				}
			}, tabs);
		}
		// the #hash picks a panel; it shouldn't scroll the page under the menu
		function top() { window.scrollTo(0, 0); }
		window.addEventListener('hashchange', function () {
			update();
			top();
		});
		window.addEventListener('load', top);
		update();
	});

	/* ---------- windows (were popups) ---------- */

	// a page in a window hands its windows to the page around it
	var host = null;
	try {
		if (window.parent !== window && window.parent.SevenActs) host = window.parent.SevenActs;
	} catch (e) { /* not ours */ }

	var IMAGE = /\.(gif|jpe?g|png|webp)(\?|#|$)/i;
	var wins = {};
	var zTop = 500;

	function createWin() {
		var w = document.createElement('section');
		w.className = 'win';
		w.setAttribute('role', 'dialog');
		w.innerHTML = '<header class="win-bar"><span class="win-title"></span>' +
			'<button type="button" class="win-close" aria-label="Close">&times;</button></header>' +
			'<div class="win-body"></div>';
		w._bar = w.querySelector('.win-bar');
		w._body = w.querySelector('.win-body');
		w._title = w.querySelector('.win-title');
		w.querySelector('.win-close').addEventListener('click', function () { closeWin(w); });
		w.addEventListener('pointerdown', function () { raise(w); });
		w._body.addEventListener('click', function (e) {
			if (e.target.tagName === 'IMG' && w._o.gallery) step(w, 1);
		});
		makeDraggable(w);
		document.body.appendChild(w);
		return w;
	}

	function raise(w) {
		w.style.zIndex = ++zTop;
	}

	// size a window like the original popup, shrunk to fit the screen; a picture
	// is shown whole unless that would make it too small, when it fills the
	// window's width and scrolls instead
	function layoutWin(w) {
		var o = w._o;
		var availW = viewW() - 16;
		var availH = viewH() - 16 - w._bar.offsetHeight;
		var width, height;
		if (o.image) {
			var s = Math.min(1, availW / o.w, availH / o.h);
			if (s < 0.75) s = Math.min(1, availW / o.w);
			width = Math.floor(o.w * s);
			height = Math.min(Math.ceil(width * o.h / o.w), Math.floor(availH));
		} else {
			width = Math.min(o.w, availW);
			height = Math.min(o.h, availH);
		}
		w._body.style.width = width + 'px';
		w._body.style.height = height + 'px';
		if (w._dragged) {
			moveWin(w, w._left, w._top);
		} else {
			moveWin(w, (viewW() - w.offsetWidth) / 2, (viewH() - w.offsetHeight) / 2);
		}
	}

	function moveWin(w, left, top) {
		w._left = clamp(left, 0, Math.max(0, viewW() - w.offsetWidth));
		w._top = clamp(top, 0, Math.max(0, viewH() - w.offsetHeight));
		w.style.left = Math.round(w._left) + 'px';
		w.style.top = Math.round(w._top) + 'px';
	}

	function fill(w) {
		var o = w._o;
		w._title.textContent = o.title;
		w.setAttribute('aria-label', o.title);
		w._body.classList.toggle('is-gallery', !!o.gallery);
		if (o.image) {
			w._body.innerHTML = '';
			var img = new Image(o.w, o.h);
			img.src = o.url;
			img.alt = o.title;
			w._body.appendChild(img);
		} else {
			var frame = w._body.querySelector('iframe');
			if (!frame) {
				w._body.innerHTML = '';
				frame = document.createElement('iframe');
				w._body.appendChild(frame);
			}
			frame.title = o.title;
			frame.src = o.url;
		}
		w._body.scrollTop = 0;
	}

	// o: url, w, h, title, name, and gallery: a list of more o's to step through
	function openWin(o) {
		if (host) return host.open(o);
		var w = wins[o.name] || (wins[o.name] = createWin());
		clearTimeout(w._blankTimer);
		w._o = o;
		fill(w);
		layoutWin(w);
		raise(w);
		w.classList.add('is-open');
		w.querySelector('.win-close').focus({ preventScroll: true });
	}

	function step(w, by) {
		var g = w._o.gallery;
		var i = (w._o.index + by + g.length) % g.length;
		w._o = Object.assign({}, g[i], { gallery: g, index: i });
		fill(w);
		layoutWin(w);
	}

	function closeWin(w) {
		if (!w.classList.contains('is-open')) return;
		w.classList.remove('is-open');
		// empty the window once it's hidden, so that sound and video stop
		w._blankTimer = setTimeout(function () { w._body.innerHTML = ''; }, 300);
	}

	function closeTop() {
		if (host) return host.closeTop();
		var top = null;
		Object.keys(wins).forEach(function (k) {
			var w = wins[k];
			if (w.classList.contains('is-open') && (!top || +w.style.zIndex > +top.style.zIndex)) top = w;
		});
		if (top) closeWin(top);
	}

	function makeDraggable(w) {
		var start = null;
		w._bar.addEventListener('pointerdown', function (e) {
			if (e.target.closest('button')) return;
			start = { x: e.clientX - w._left, y: e.clientY - w._top };
			w._bar.setPointerCapture(e.pointerId);
		});
		w._bar.addEventListener('pointermove', function (e) {
			if (!start) return;
			w._dragged = true;
			moveWin(w, e.clientX - start.x, e.clientY - start.y);
		});
		function end() { start = null; }
		w._bar.addEventListener('pointerup', end);
		w._bar.addEventListener('pointercancel', end);
	}

	function describe(a) {
		var size = a.dataset.window.split('x');
		return {
			url: a.href,
			w: +size[0],
			h: +size[1],
			image: IMAGE.test(a.href),
			title: a.dataset.title || a.textContent.trim() || (a.querySelector('img') || {}).alt || '',
			name: a.dataset.name || 'console'
		};
	}

	document.addEventListener('click', function (e) {
		var a = e.target.closest('a[data-window]');
		if (!a || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
		e.preventDefault();
		var o = describe(a);
		if (a.dataset.gallery) {
			var all = document.querySelectorAll('a[data-window][data-gallery="' + a.dataset.gallery + '"]');
			o.gallery = Array.prototype.map.call(all, describe);
			o.index = Array.prototype.indexOf.call(all, a);
		}
		openWin(o);
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') closeTop();
	});

	if (!host) window.SevenActs = { open: openWin, closeTop: closeTop };

	/* ---------- start ---------- */

	fitStages();
	deal();
	drift();
	window.addEventListener('resize', function () {
		fitStages();
		Object.keys(wins).forEach(function (k) {
			if (wins[k].classList.contains('is-open')) layoutWin(wins[k]);
		});
	});
})();
