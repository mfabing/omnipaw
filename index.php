<?php

declare(strict_types=1);

$rootDir = __DIR__;
$htmlPaths = [];

$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $fileInfo) {
	if (!$fileInfo->isFile()) {
		continue;
	}

	if (strtolower($fileInfo->getExtension()) !== 'html') {
		continue;
	}

	$fullPath = $fileInfo->getPathname();
	$relativePath = ltrim(substr($fullPath, strlen($rootDir)), DIRECTORY_SEPARATOR);
	$relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

	$htmlPaths[] = $relativePath;
}

function encodePathForUrl(string $path): string
{
	$segments = explode('/', $path);
	return implode('/', array_map('rawurlencode', $segments));
}

$randomUrl = null;
$randomPath = null;

if (!empty($htmlPaths)) {
	$randomPath = $htmlPaths[random_int(0, count($htmlPaths) - 1)];
	$randomUrl = encodePathForUrl($randomPath);
}
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HTML Random Viewer</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
</head>

<body class="bg-body-tertiary">
	<main>
		<?php if ($randomUrl === null): ?>
			<div class="alert alert-warning" role="alert">
				No <code>.html</code> files found.
			</div>
		<?php else: ?>

			<p class="ps-1"><?= htmlspecialchars($randomPath, ENT_QUOTES, 'UTF-8') ?></p>

			<div class="card shadow-sm">
				<div class="card-body p-0" style="height: calc(100vh - 20px); min-height: 520px;">
					<iframe
						src="<?= htmlspecialchars($randomUrl, ENT_QUOTES, 'UTF-8') ?>"
						title="Random HTML Preview"
						style="width: 100%; height: 100%; border: 0;"
						loading="eager"></iframe>
				</div>
			</div>
		<?php endif; ?>
		<div class="text-center py-3">
			<button type="button" class="btn btn-success" style="position: fixed; top: 0px; right: 10px;" onclick="reloadWithNewDesign()">new design</button>
		</div>
	</main>
	<script>
		function reloadWithNewDesign() {
			window.location.href = 'index.php?r=' + Date.now();
		}
	</script>
</body>

</html>