<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

$email = trim((string) ($_POST['email'] ?? $_GET['email'] ?? ''));
$type  = (string) ($_GET['type'] ?? '');

if ($type === 'team') {
	echo '<div class="form-note form-note-ok">'
		. '<i data-lucide="calendar-check"></i>'
		. '<span><b>Walkthrough request noted.</b> In the live product this opens a booking calendar for facilities and clinics. We\'d follow up within one business day.</span>'
		. '</div>';
	exit;
}

if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	http_response_code(422);
	echo '<div class="form-note form-note-error">'
		. '<i data-lucide="alert-circle"></i>'
		. '<span><b>Hmm, that email doesn\'t look right.</b> Check the spelling and try again &mdash; it only takes a second.</span>'
		. '</div>';
	exit;
}

$safe = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

echo '<div class="form-note form-note-ok">'
	. '<i data-lucide="party-popper"></i>'
	. '<span><b>You\'re in.</b> We\'d send a setup link to <strong>' . $safe . '</strong> right now and help you build your first pet profile. (Demo form &mdash; nothing was stored.)</span>'
	. '</div>';
