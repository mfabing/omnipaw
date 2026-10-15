<?php

declare(strict_types=1);

$rootDir = __DIR__;
$htmlFiles = [];

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

	$htmlFiles[] = [
		'name' => basename($relativePath),
		'path' => $relativePath,
		'modified' => $fileInfo->getMTime(),
	];
}

usort($htmlFiles, static function (array $a, array $b): int {
	return strnatcasecmp($a['path'], $b['path']);
});

function encodePathForUrl(string $path): string
{
	$segments = explode('/', $path);
	$encoded = array_map('rawurlencode', $segments);
	return implode('/', $encoded);
}
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HTML File Index</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.min.css">
</head>

<body class="bg-body-tertiary">
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="card shadow-sm">
					<div class="card-header bg-primary text-white">
						<h1 class="h4 mb-0"><i class="bi bi-file-earmark-code me-2"></i>HTML Files</h1>
					</div>
					<div class="card-body">
						<?php if (empty($htmlFiles)): ?>
							<div class="alert alert-warning mb-0" role="alert">
								<i class="bi bi-exclamation-triangle me-2"></i>
								No <code>.html</code> files found.
							</div>
						<?php else: ?>
							<p class="text-muted mb-3">
								Found <strong><?= count($htmlFiles) ?></strong> HTML file<?= count($htmlFiles) === 1 ? '' : 's' ?>.
							</p>
							<div class="list-group">
								<?php foreach ($htmlFiles as $file): ?>
									<?php
									$url = encodePathForUrl($file['path']);
									$name = htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8');
									$path = htmlspecialchars($file['path'], ENT_QUOTES, 'UTF-8');
									$modified = date('Y-m-d H:i', (int) $file['modified']);
									?>
									<a
										href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>"
										class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
										<div class="me-3">
											<div class="fw-semibold">
												<i class="bi bi-filetype-html text-danger me-2"></i><?= $name ?>
											</div>
											<small class="text-muted"><?= $path ?></small>
										</div>
										<small class="text-muted text-nowrap">
											<i class="bi bi-clock me-1"></i><?= htmlspecialchars($modified, ENT_QUOTES, 'UTF-8') ?>
										</small>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>