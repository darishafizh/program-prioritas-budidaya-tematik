<?php
$files = ["c:/laragon/www/bioflok/resources/views/sppg/create.blade.php", "c:/laragon/www/bioflok/resources/views/sppg/edit.blade.php"];
foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace form wrapper
    $content = preg_replace("/<form([^>]+)>/", "<div class=\"row\">\n    <div class=\"col-md-12\">\n        <div class=\"card shadow-sm border-0\" style=\"border-radius: 12px;\">\n            <div class=\"card-body p-4\">\n                <form$1>", $content);
    
    // Replace closing form
    $content = preg_replace("/<\/form>/", "</form>\n            </div>\n        </div>\n    </div>\n</div>", $content);
    
    // Replace section-card
    $content = str_replace("<div class=\"section-card\">", "<div class=\"mb-5\">", $content);
    
    // Replace section headers
    $content = preg_replace("/<div class=\"section-header\">[\s\S]*?<h3 class=\"section-title\">([^<]+)<\/h3>\s*<\/div>\s*<div class=\"section-body\">/U", "<h5 class=\"mb-3 fw-bold\">$1</h5>\n        <div class=\"\">", $content);
    
    // Replace form-actions
    $content = preg_replace("/<div class=\"form-actions\">[\s\S]*?<a href=\"[^\"]+\" class=\"btn btn-outline\">Batal<\/a>[\s\S]*?<button type=\"submit\" class=\"btn btn-success\">[\s\S]*?<\/button>\s*<\/div>/U", "<div class=\"d-flex justify-content-end gap-2 mt-4 pt-3 border-top\">\n        <a href=\"{{ route('sppg.index') }}\" class=\"btn btn-light\">Batal</a>\n        <button type=\"submit\" class=\"btn btn-primary\">Simpan Data</button>\n    </div>", $content);
    
    // In edit file, the action button might have "Simpan Perubahan"
    if (strpos($file, "edit") !== false) {
        $content = str_replace("Simpan Data", "Simpan Perubahan", $content);
    }
    
    file_put_contents($file, $content);
    echo "Processed: $file\n";
}
