<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

$raw = trim((string) ($_GET['item'] ?? ''));
$key = strtolower(preg_replace('/[^a-z ]/i', '', $raw) ?? '');

/** Small demo dataset — informational only. */
$items = [
	'chocolate'      => ['danger',  'alert-triangle', 'Chocolate — high risk', 'Contains theobromine and caffeine. Dark and baking chocolate are the most dangerous. Even small amounts can cause vomiting, tremors, or a racing heart.', "Call your vet or a pet poison helpline now, then bring the wrapper and your pet's weight."],
	'grapes'         => ['danger',  'alert-triangle', 'Grapes & raisins — high risk', 'Can cause sudden kidney injury in dogs. There is no known safe amount, and reactions vary from one dog to another.', 'Treat it as an emergency. Contact your vet immediately.'],
	'raisin'         => ['danger',  'alert-triangle', 'Raisins — high risk', 'Dried fruit is more concentrated than fresh grapes and can cause kidney injury in dogs.', 'Treat it as an emergency. Contact your vet immediately.'],
	'xylitol'        => ['danger',  'alert-triangle', 'Xylitol — emergency', 'A common sweetener in sugar-free gum, mints, toothpaste, and some peanut butters. It causes a rapid insulin release, which can lead to dangerous hypoglycaemia and liver failure.', 'Go to a vet now — do not wait for symptoms.'],
	'onion'          => ['danger',  'alert-triangle', 'Onion & garlic — high risk', 'All forms (raw, cooked, powdered) can damage red blood cells and cause anaemia. Cats are especially sensitive.', 'Contact your vet, especially with larger amounts or repeated exposure.'],
	'garlic'         => ['danger',  'alert-triangle', 'Garlic — high risk', 'Concentrated when powdered, and more toxic than onion per gram. Can damage red blood cells.', 'Contact your vet, especially with larger amounts.'],
	'macadamia'      => ['caution', 'alert-circle', 'Macadamia nuts — moderate risk', 'Usually causes weakness, tremors, vomiting, and overheating within 12 hours. Rarely fatal, but uncomfortable and worth a call.', 'Call your vet for advice and keep your pet cool and calm.'],
	'macadamia nuts' => ['caution', 'alert-circle', 'Macadamia nuts — moderate risk', 'Usually causes weakness, tremors, vomiting, and overheating within 12 hours. Rarely fatal, but uncomfortable and worth a call.', 'Call your vet for advice and keep your pet cool and calm.'],
	'avocado'        => ['caution', 'alert-circle', 'Avocado — mild risk', 'The flesh is usually low risk in small amounts, but the pit, skin, and leaves contain persin and can cause stomach upset. The pit is also a choking hazard.', 'Watch for vomiting or diarrhoea and call your vet if it continues.'],
	'milk'           => ['caution', 'alert-circle', 'Milk & dairy — mild risk', 'Many pets are lactose intolerant. It is rarely toxic, but it commonly causes gas, bloating, and diarrhoea.', 'Offer water, skip the cheese, and call your vet if symptoms persist.'],
	'alcohol'        => ['danger',  'alert-triangle', 'Alcohol — emergency', 'Even small amounts can cause dangerous drops in blood sugar and body temperature, seizures, or coma.', 'Go to an emergency vet immediately.'],
	'caffeine'       => ['danger',  'alert-triangle', 'Caffeine — emergency', 'Found in coffee, tea, energy drinks, and some supplements. Causes restlessness, a racing heart, tremors, and seizures.', 'Contact an emergency vet right away.'],
	'lily'           => ['danger',  'alert-triangle', 'Lilies — emergency (cats)', 'True lilies are highly toxic to cats — even pollen or vase water. Can cause fatal kidney failure.', 'Take your cat to a vet immediately.'],
	'lilies'         => ['danger',  'alert-triangle', 'Lilies — emergency (cats)', 'True lilies are highly toxic to cats — even pollen or vase water. Can cause fatal kidney failure.', 'Take your cat to a vet immediately.'],
	'sago palm'      => ['danger',  'alert-triangle', 'Sago palm — high risk', 'Every part of the plant, especially the seeds, can cause severe liver failure.', 'Contact an emergency vet immediately.'],
];

function esc(string $value): string
{
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($raw === '') {
	echo '<p class="toxic-hint">Type a food, plant, or product to check it against our starter database.</p>';
	exit;
}

if (!isset($items[$key])) {
	echo '<div class="toxic-card toxic-safe">'
		. '<span class="tc-ic"><i data-lucide="help-circle"></i></span>'
		. '<span><strong>No match in this demo for &ldquo;' . esc($raw) . '&rdquo;</strong>'
		. '<p>That does not mean it is safe. When in doubt, call your vet or a pet poison helpline before feeding it.</p>'
		. '<small>Demo dataset only &mdash; not veterinary advice.</small></span>'
		. '</div>';
	exit;
}

[$level, $icon, $title, $body, $advice] = $items[$key];

echo '<div class="toxic-card toxic-' . esc($level) . '">'
	. '<span class="tc-ic"><i data-lucide="' . esc($icon) . '"></i></span>'
	. '<span><strong>' . esc($title) . '</strong>'
	. '<p>' . esc($body) . '</p>'
	. '<small>' . esc($advice) . ' Demo only &mdash; always confirm with a vet.</small></span>'
	. '</div>';
