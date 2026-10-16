/*
therapy.js
7 acts of love - god-therapist session, running entirely in the browser.

Replaces bedroom.php, therapist.php, session.php, sounds.php, start.swf and
therapist/s*_therapist.swf. The audio is the original therapist voice, taken
out of the Flash files; the branching in nextClip() is check_input() from
sevenacts.js, unchanged apart from tidying up the way words are counted.
*/
(function () {
	'use strict';

	var BEDROOMS = 14;
	var STICKS = 7;
	var END = 47;           // "This is the end of the session..."
	var HOME = 'index.html';

	// the original intro (start.mp3, played by start.swf)
	var INTRO = 'The God Therapist will ask you a series of questions. The session will address a separation. Please type your responses in order to generate fictional or real stories of loss and love. The more precisely you respond to the questions, the better the session will flow. Please respond as fully as possible rather than with one-word answers.';

	// what the therapist says in each clip (audio/<n>.mp3) - was $config["therapist_text"]
	// 48-53 and 56 are never reached by the original script
	var LINES = [
		'You said you wanted to talk about the separation.',
		'How long were you together?',
		'How did you meet?',
		'What was the relationship like?',
		'Mmm hmmm...',
		'Tell me more...',
		'Is there anything about the relationship that reminds you of your childhood?',
		'How did the relationship come to an end?',
		'Mmm...',
		'Tell me more about the decision to separate.',
		'How did that make you feel at the time?',                     // 10
		'Mmm hmmm...',
		'Do you feel guilty about the separation?',
		'Do you feel angry?',
		'Mmm hmmm...',
		'Tell me more...',
		'What was the most painful part of the separation?',
		'Mmm hmmm...',
		'And how do you feel about the separation now?',
		'Are you in touch with them now?',
		'Aha...',                                                      // 20
		'Tell me more...',
		'Were there any differences you can think of that cause frictions?',
		'Mmm hmmm... that\'s interesting...',
		'Tell me more...',
		'How did you feel immediately after the relationship ended?',
		'Do you still live in the same city?',
		'Mmm hmmm...',
		'Tell me more about that...',
		'How do you feel now?',
		'Do you still have any of their belongings?',                  // 30
		'Mmm hmmm...',
		'Do you still dream about them?',
		'Mmm hmmm, tell me more...',
		'What is the last dream you can remember?',
		'Uh huh...',
		'Interesting...',
		'Did the separation remind you of your childhood in any way?',
		'Mmm hmmm...',
		'Tell me more...',
		'How traumatic was the separation for you?',                   // 40
		'Do you think you are over it now?',
		'Mmm...',
		'Is there anything positive that came out of the separation for you?',
		'Do you feel the separation changed you?',
		'Mmm...',
		'Is there anything else you would like to tell me?',
		'This is the end of the session. Thank you. See you next time.',
		'How did you feel immediately after our last session?',
		'How have you been since then?',
		'Mmm hmmm...',                                                 // 50
		'Does our communication remind you of your relationship?',
		'Can you tell me about the last dream you had?',
		'Are you in a new relationship now?',
		'Mmm...',
		'Tell me more...',
		'Thank you. That will be it for today.'
	];

	/*
	which clip follows the visitor's answer to clip `id` - from check_input()
	short answers get "Mmm hmmm..." / "tell me more" style probes, and some
	questions branch on a plain "yes" or "no"
	*/
	function nextClip(id, prev, msg) {
		var words = msg.split(/\s+/).length;
		var answer = msg.replace(/[^a-z]/g, '');   // so "Yes." and "no!" still count
		var yes = answer === 'yes', no = answer === 'no';
		switch (id) {
		case 3:  return words <= 5 ? 4 : 6;
		case 4:  return words <= 5 ? 5 : 6;
		case 6:  return (yes || words === 1) ? 15 : no ? 8 : 7;
		case 7:  return words <= 10 ? 38 : 9;
		case 8:  return 7;
		case 9:  return no ? 23 : words <= 10 ? 33 : 10;
		case 10: return words === 1 ? 11 : 12;
		case 12: return yes ? 15 : 13;
		case 13: return yes ? 14 : 16;
		case 14: return 16;
		case 15: return prev === 6 ? 7 : 13;
		case 16: return words <= 10 ? 17 : 18;
		case 18: return words === 1 ? 21 : 19;
		case 19: return words === 1 ? 20 : 22;
		case 20: return 22;
		case 21: return 19;
		case 22: return words <= 10 ? 24 : 25;
		case 23: return prev === 9 ? 10 : 25;
		case 25: return words <= 10 ? 27 : 26;
		case 26: return yes ? 28 : 29;
		case 27: return 26;
		case 29: return words <= 10 ? 33 : 30;
		case 30: return yes ? 31 : 32;
		case 32: return yes ? 33 : 34;
		case 33: return prev === 9 ? 10 : prev === 29 ? 30 : 34;
		case 34: return words <= 10 ? 36 : 37;
		case 37: return yes ? 39 : 40;
		case 38: return 9;
		case 40: return words <= 10 ? 42 : 41;
		case 41: return words === 1 ? 54 : 43;
		case 42: return 41;
		case 43: return yes ? 55 : 44;
		case 44: return words === 1 ? 45 : 46;
		case 54: return 43;
		case 55: return 44;
		default: return id + 1;
		}
	}

	/* ---------- helpers ---------- */

	function $(sel) { return document.querySelector(sel); }

	function el(tag, attrs, children) {
		var e = document.createElement(tag);
		for (var k in attrs) e.setAttribute(k, attrs[k]);
		(children || []).forEach(function (c) {
			e.appendChild(typeof c === 'string' ? document.createTextNode(c) : c);
		});
		return e;
	}

	function show(step) {
		document.querySelectorAll('.step').forEach(function (s) { s.hidden = s.id !== step; });
		window.scrollTo(0, 0);
	}

	// a bedroom photo with an optional stick figure laid over it
	function drawRoom(container, bed, stick) {
		container.textContent = '';
		container.appendChild(el('img', { src: 'images/bedroom' + bed + '.jpg', alt: 'Bedroom ' + bed }));
		if (stick) {
			container.appendChild(el('img', { src: 'images/stick' + stick + '/bedroom' + bed + '.gif', alt: '' }));
		}
	}

	// a row of toggle buttons where only one can be chosen
	function choice(container, count, makeImg, onPick) {
		for (var i = 1; i <= count; i++) {
			var b = el('button', { type: 'button', 'aria-pressed': 'false', 'data-id': i }, [makeImg(i)]);
			container.appendChild(b);
		}
		container.addEventListener('click', function (e) {
			var b = e.target.closest('button');
			if (!b) return;
			container.querySelectorAll('button').forEach(function (o) {
				o.setAttribute('aria-pressed', o === b ? 'true' : 'false');
			});
			onPick(+b.dataset.id);
		});
	}

	/* ---------- step 1: bedroom and name ---------- */

	var bed = 0, stick = 0, nick = '';

	choice($('.beds'), BEDROOMS, function (i) {
		return el('img', { src: 'images/bed_thumbs/bedroom' + i + '.jpg', alt: 'Bedroom ' + i, width: 150 });
	}, function (i) {
		bed = i;
	});

	$('#bedroom-form').addEventListener('submit', function (e) {
		e.preventDefault();
		nick = $('#nick').value.trim();
		var missing = [];
		if (!bed) missing.push('select a bed');
		if (!nick) missing.push('type a name');
		$('#bedroom-error').textContent = missing.length ? 'Please ' + missing.join(' and ') + ' to continue.' : '';
		if (missing.length) {
			if (!nick) $('#nick').focus();
			return;
		}
		stick = 0;
		var sticks = $('.sticks');
		sticks.textContent = '';
		choice(sticks, STICKS, function (i) {
			return el('img', { src: 'images/stick' + i + '.gif', alt: 'Stick figure position ' + i });
		}, function (i) {
			stick = i;
			$('#position-error').textContent = '';
			drawRoom($('#position-room'), bed, stick);
		});
		drawRoom($('#position-room'), bed, 0);
		$('#position-error').textContent = '';
		show('step-position');
	});

	/* ---------- step 2: where to sit ---------- */

	$('#enter').addEventListener('click', function () {
		if (!stick) {
			$('#position-error').textContent = 'Please select a place to sit.';
			return;
		}
		drawRoom($('#session-room'), bed, stick);
		show('step-session');
		startSession();
	});

	/* ---------- step 3: the session ---------- */

	var log = $('#log');
	var input = $('#message');
	// one audio element for everything, so that once the click on "enter" has
	// allowed it to play, every later clip can play too (iOS in particular)
	var audio = new Audio();
	var clip = -1;          // the clip the visitor is answering; -1 during the intro
	var prevClip = 0;
	var replyTimer = 0;
	var textTimer = 0;
	var ended = false;

	function say(who, text) {
		var p = el('p', {}, [el('b', {}, [who + ': ']), text]);
		log.appendChild(p);
		log.scrollTop = log.scrollHeight;
	}

	function note(text) {
		log.appendChild(el('p', { 'class': 'entry' }, [text]));
		log.scrollTop = log.scrollHeight;
	}

	// play a sound; `onStart` runs when the voice actually begins (or straight
	// away if the audio can't play), so the words appear as they are spoken
	function play(src, onStart, onEnd) {
		var started = false;
		function start() {
			if (started) return;
			started = true;
			clearTimeout(textTimer);
			if (onStart) onStart();
		}
		audio.onplaying = start;
		audio.onended = function () { start(); if (onEnd) onEnd(); };
		audio.onerror = function () { start(); if (onEnd) onEnd(); };
		audio.src = src;
		clearTimeout(textTimer);
		textTimer = setTimeout(start, 2000);
		audio.play().catch(function () { start(); if (onEnd) setTimeout(onEnd, 3000); });
	}

	function playClip(id) {
		play('audio/' + id + '.mp3', function () {
			say('god-therapist', LINES[id]);
		}, id === END ? goHome : null);
	}

	function startSession() {
		log.textContent = '';
		clip = -1;
		prevClip = 0;
		ended = false;
		input.value = '';
		input.disabled = false;
		note(nick + ' has just entered the room');
		play('start.mp3', function () {
			note(INTRO);
		}, function () {
			if (ended) return;
			clip = 0;
			playClip(0);
			input.focus();
		});
		input.focus();
	}

	function finish() {
		ended = true;
		clearTimeout(replyTimer);
		input.disabled = true;
		replyTimer = setTimeout(function () { playClip(END); }, 1000);
	}

	function goHome() {
		setTimeout(function () { window.location.href = HOME; }, 3000);
	}

	$('#session-form').addEventListener('submit', function (e) {
		e.preventDefault();
		if (ended) return;
		var msg = input.value.trim();
		if (!msg) {
			input.focus();
			return;
		}
		if (clip < 0) {
			// still in the introduction - the original ignored input until it finished
			return;
		}
		say(nick, msg);
		input.value = '';
		input.focus();
		var next = nextClip(clip, prevClip, msg.toLowerCase());
		prevClip = clip;
		clip = next;
		if (next === END) {
			finish();
			return;
		}
		clearTimeout(replyTimer);
		replyTimer = setTimeout(function () { playClip(next); }, 1000);
	});

	$('#exit').addEventListener('click', function () {
		if (ended) {
			window.location.href = HOME;
			return;
		}
		audio.pause();
		finish();
	});
})();
