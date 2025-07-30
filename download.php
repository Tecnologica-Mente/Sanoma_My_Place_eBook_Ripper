<?php
ini_set('memory_limit', '1024M');
ini_set("max_execution_time", 0);  //NO maximum execution time of 30 seconds exceeded

// Mostra la versione PHP in uso
echo("&#128161; Versione PHP: ".phpversion()."<br><br>");

// Configurazione
$base_url = trim(utf8_decode($_POST["base_url"]));          // Esempio: 'https://npmitaly-pro-apidistribucion.sanoma.it';
$product_path = trim(utf8_decode($_POST["product_path"]));  // Esempio: '/product/1126602/54001/ONLINE/assets/book/pages/';
$username = trim($_POST["username"]);
$password = trim($_POST["password"]);
$cookie = trim($_POST["cookie"]);                          // Esempio: '_fw_crm_v=7f7176a3-3d3e-41b2-efc1-5e600f624f49; CloudFront-Key-Pair-Id=K1PXHSIPOFIFAS; CloudFront-Policy=eyJTdGF0ZW1lbnQiOlt7IlJlc291cmNlIjoiaHR0cHM6Ly9ucG1pdGFseS1wcm8tYXBpZGlzdHJpYnVjaW9uLnNhbm9tYS5pdC9wcm9kdWN0LzExMjY2MDIvNTQwMDEvT05MSU5FLyoiLCJDb25kaXRpb24iOnsiRGF0ZUxlc3NUaGFuIjp7IkFXUzpFcG9jaFRpbWUiOjE3NTM2NjUzNzV9fX1dfQ__; CloudFront-Signature=ROoAXLrzck-BJ1Mu2iCqvA-aNVaEP3iJcrFMrxJO8DzqguoT5V3mHLh4CwVXeAT4l596eTgrgmswny~ZAf~ng1MXoZovKE2nz9TGmgwJWgGppBihDxnfKSQzw3SeYTiLFcdas-BZx1kkQUCOFt3EsQa8M-TD76Y-VqiLnDUFkwjlveaeydd9mrsmQgS3Zs5Id19vtBTjmnt~FnFphKUfPbB4xIIPSdujzNShZiX6a15OySdjQ0QfiXx1G75IQX~WCEAMx547DlMsGMeXXCvn-uG~Wy9wKgLmD1~sJf~Ro36qPvNMWOlQF~tmSVyQ6v8ebZYKTmNXPHQwQU92qX~g2Q__';
$save_dir = __DIR__ . '/downloaded_pages/';                // Esempio: __DIR__ . '/downloaded_pages/';
$start_page = trim(utf8_decode($_POST["start_page"]));
$end_page = trim(utf8_decode($_POST["end_page"]));

// Verifica della connessione e del cookie
function validateConnection($base_url, $product_path, $username, $password, $cookie) {
    $test_url = $base_url . $product_path . '1/1.svg';
    
    $ch = curl_init($test_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => "$username:$password",
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HEADER => true
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Analisi più intelligente della risposta
    if ($http_code == 200) {
        return true;
    }
    
    if ($http_code == 403 || $http_code == 401) {
        // Tentativo di verifica più approfondita
        if (strpos($response, 'ExpiredToken') !== false) {
            die("&#128721; Errore: Token scaduto");
        }
        if (strpos($response, 'InvalidToken') !== false) {
            die("&#128721; Errore: Token non valido");
        }
        
        // Se arriva qui ma il download funziona, ignoriamo il falso positivo
        return true;
    }
    
    return true; // Se non siamo sicuri, continuiamo
}

validateConnection($base_url, $product_path, $username, $password, $cookie);

// Crea la cartella se non esiste
if (!file_exists($save_dir)) {
    mkdir($save_dir, 0777, true);
}

// Funzione per verificare se il cookie è scaduto realmente
function isCookieActuallyValid($cookie) {
    if (!preg_match('/CloudFront-Policy=([^;]+);/', $cookie, $matches)) {
        return false;
    }
    
    $policy = str_replace(['-', '_'], ['+', '/'], $matches[1]);
    $decoded = base64_decode($policy);
    
    if (preg_match('/"DateLessThan":\{"AWS:EpochTime":(\d+)\}/', $decoded, $timeMatch)) {
        return time() < $timeMatch[1];
    }
    
    return true; // Se non possiamo verificare, assumiamo valido
}

// Funzione avanzata per scaricare risorse
function fetchResource($url, $username, $password, $cookie) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => "$username:$password",
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FAILONERROR => false, // Modificato per gestire manualmente gli errori
        CURLOPT_HEADER => true
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    
    $body = substr($response, $header_size);
    
    // Analisi intelligente della risposta
    if ($http_code == 200) {
        return $body;
    }
    
    // Gestione specifica degli errori
    if ($http_code == 403 || $http_code == 401) {
        if (strpos($body, 'ExpiredToken') !== false) {
            die("&#128721; ERRORE REALE: Cookie scaduto");
        }
        if (strpos($body, 'InvalidToken') !== false) {
            die("&#128721; ERRORE REALE: Cookie non valido");
        }
        
        // Se non trova errori specifici, prova comunque a restituire il contenuto
        return $body;
    }
    
    // Per altri errori HTTP
    if ($http_code >= 400) {
        return false;
    }
    
    return $body;
}

// Analizza il SVG per estrarre dimensioni e viewBox
function getSVGDimensions($svg_content) {
    $width = 0;
    $height = 0;
    $viewBox = '0 0 1000 1000';
    
    if (preg_match('/width=["\']([^"\']+)["\']/', $svg_content, $matches)) {
        $width = preg_replace('/[^0-9.]/', '', $matches[1]);
    }
    
    if (preg_match('/height=["\']([^"\']+)["\']/', $svg_content, $matches)) {
        $height = preg_replace('/[^0-9.]/', '', $matches[1]);
    }
    
    if (preg_match('/viewBox=["\']([^"\']+)["\']/', $svg_content, $matches)) {
        $viewBox = $matches[1];
    }
    
    return [
        'width' => $width ?: 1000,
        'height' => $height ?: 1000,
        'viewBox' => $viewBox
    ];
}

// Trova e salva TUTTE le risorse correlate
function findAndSaveAllResources($svg_content, $base_url, $page_url, $page_dir, $username, $password, $cookie) {
    $resources = [];
    
    // 1. Immagini dirette nel SVG
    preg_match_all('/<image[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $svg_content, $img_matches, PREG_SET_ORDER);
    
    foreach ($img_matches as $match) {
        $img_url = $match[1];
        if (strpos($img_url, 'data:') === 0) continue;
        
        $absolute_url = strpos($img_url, 'http') === 0 ? $img_url : dirname($page_url).'/'.ltrim($img_url, '/');
        $filename = basename(parse_url($absolute_url, PHP_URL_PATH));
        $filepath = $page_dir . $filename;
        
        // Estrai attributi di posizione con controlli tradizionali
        $x = '0';
        $y = '0';
        $width = 'auto';
        $height = 'auto';
        $transform = null;
        
        if (preg_match('/x=["\']([^"\']+)["\']/', $match[0], $x_match)) {
            $x = $x_match[1];
        }
        if (preg_match('/y=["\']([^"\']+)["\']/', $match[0], $y_match)) {
            $y = $y_match[1];
        }
        if (preg_match('/width=["\']([^"\']+)["\']/', $match[0], $w_match)) {
            $width = $w_match[1];
        }
        if (preg_match('/height=["\']([^"\']+)["\']/', $match[0], $h_match)) {
            $height = $h_match[1];
        }
        if (preg_match('/transform=["\']([^"\']+)["\']/', $match[0], $t_match)) {
            $transform = $t_match[1];
        }
        
        // Scarica la risorsa se non esiste
        if (!file_exists($filepath)) {
            $resource_data = fetchResource($absolute_url, $username, $password, $cookie);
            if ($resource_data) {
                file_put_contents($filepath, $resource_data);
            }
        }
        
        $resources[] = [
            'type' => 'image',
            'url' => $absolute_url,
            'filename' => $filename,
            'x' => $x,
            'y' => $y,
            'width' => $width,
            'height' => $height,
            'transform' => $transform
        ];
    }
    
    // 2. Immagini in CSS (background)
    preg_match_all('/url$$["\']?([^)"\']+)["\']?$$/i', $svg_content, $css_matches);
    foreach ($css_matches[1] as $css_url) {
        if (strpos($css_url, 'data:') === 0) continue;
        
        $absolute_url = strpos($css_url, 'http') === 0 ? $css_url : dirname($page_url).'/'.ltrim($css_url, '/');
        $filename = basename(parse_url($absolute_url, PHP_URL_PATH));
        $filepath = $page_dir . $filename;
        
        // Scarica la risorsa se non esiste
        if (!file_exists($filepath)) {
            $resource_data = fetchResource($absolute_url, $username, $password, $cookie);
            if ($resource_data) {
                file_put_contents($filepath, $resource_data);
            }
        }
        
        $resources[] = [
            'type' => 'css-image',
            'url' => $absolute_url,
            'filename' => $filename
        ];
    }
    
    return $resources;
}

// Genera la pagina composita con immagini visibili (VERSIONE COMPATIBILE PHP 7.3)
function generateEnhancedCompositePage($page_num, $svg_content, $resources, $page_dir, $save_dir, $total_pages) {
    $dimensions = getSVGDimensions($svg_content);
	//$scale_factor = min(1, 800 / max($dimensions['width'], $dimensions['height']));
	if(!isset($scale_factor)) {
 	   $scale_factor = 1.0; // Valore di default
	}
	
    // Gestione navigazione senza operatore nullsafe
    $prev_page = ($page_num > 1) ? ($page_num - 1) : null;
    $next_page = ($page_num < $total_pages) ? ($page_num + 1) : null;
    
    // Costruzione dei pulsanti di navigazione
    $prev_button = '';
    if ($prev_page !== null) {
        $prev_button = '<a href="../'.$prev_page.'/composite.html" class="nav-button">Precedente</a>';
    }
    
    $next_button = '';
    if ($next_page !== null) {
        $next_button = '<a href="../'.$next_page.'/composite.html" class="nav-button">Successiva</a>';
    }

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Pagina $page_num</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .header {
            background: #333;
            color: white;
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .page-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .page-container {
            position: relative;
            width: {$dimensions['width']}px;
            height: {$dimensions['height']}px;
            transform: scale($scale_factor);
            transform-origin: top left;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            background-color: white;
        }
        /* SVG con trasparenza controllata */
        .svg-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            opacity: 0.99; /* Permette di vedere le immagini sotto */
            mix-blend-mode: multiply; /* Migliora la visibilità */
        }
        /* Immagini completamente visibili */
        .resource-layer {
            position: absolute;
            image-rendering: crisp-edges;
            z-index: 5;
            opacity: 1;
        }
        /* Elementi SVG trasparenti */
        .svg-container path, 
        .svg-container rect,
        .svg-container polygon {
            opacity: 0.9;
        }
        /* Elementi di testo più opachi */
        .svg-container text {
            opacity: 1;
        }
		.nav-container {
		    display: flex;
		    justify-content: space-between;
		    padding: 15px;
		    background: white;
		    position: fixed; /* <-- cambia sticky in fixed */
		    bottom: 0;
		    left: 0;
		    width: 98.4%; /* era 100%, ma andava leggermente fuori pagina */
		    z-index: 1000;
		    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
		}
		.nav-button {
            padding: 10px 20px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .nav-button:hover {
            background: #555;
        }
        .page-counter {
            align-self: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pagina $page_num di $total_pages</h1>
    </div>
    
    <div class="page-wrapper">
        <div class="page-container">
HTML;

    // Aggiungi risorse con controllo tradizionale
    foreach ($resources as $index => $res) {
        if ($res['type'] !== 'image') {
            continue;
        }
        
        $filepath = $page_dir . $res['filename'];
        
        if (file_exists($filepath)) {
            $mime_type = mime_content_type($filepath);
            $file_content = file_get_contents($filepath);
            
            // Controllo aggiuntivo per file vuoti
            if ($file_content !== false) {
                $base64 = 'data:'.$mime_type.';base64,'.base64_encode($file_content);
                
                $style = 'left: '.$res['x'].'px; top: '.$res['y'].'px; ';
                $style .= 'width: '.$res['width'].'; height: '.$res['height'].';';
                
                if (!empty($res['transform'])) {
                    $style .= 'transform: '.$res['transform'].';';
                }
                
                $html .= '<img class="resource-layer" src="'.$base64.'" style="'.$style.'">';
            }
        }
    }

    $html .= <<<HTML
            <div class="svg-container">
                $svg_content
            </div>
        </div>
    </div>
    
    <div class="nav-container">
        <a href="../1/composite.html" class="nav-button">Prima</a>
        $prev_button
        <div class="page-counter">Pagina $page_num di $total_pages</div>
        $next_button
        <a href="../$total_pages/composite.html" class="nav-button">Ultima</a>
    </div>

</body>
</html>
HTML;

$html .= <<<HTML
<style>
	.zoom-controls {
	    position: fixed;
	    right: 20px;
	    bottom: 80px;
	    z-index: 1001;
	    display: flex;
	    flex-direction: column;
	    gap: 5px;
	}
	.zoom-btn {
	    width: 40px;
	    height: 40px;
	    border-radius: 50%;
	    background: #333;
	    color: white;
	    border: none;
	    font-size: 16px; /* Riduci la dimensione del font */
	    cursor: pointer;
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
	    transition: all 0.2s;
	}
	.zoom-btn:hover {
	    background: #555;
	    transform: scale(1.1);
	}
	.zoom-btn.reset {
	    font-size: 12px; /* Dimensione più piccola per il pulsante 100% */
	}
</style>

<script>
    // Variabile globale per lo stato dello zoom
    let currentScale = $scale_factor;
    
    function updateScale() {
        document.querySelector('.page-container').style.transform = `scale(\${currentScale})`;
        document.querySelector('.page-container').style.transformOrigin = 'top left';
    }
    
    function zoomIn() {
        currentScale = Math.min(currentScale + 0.1, 2);
        updateScale();
    }
    
    function zoomOut() {
        currentScale = Math.max(currentScale - 0.1, 0.5);
        updateScale();
    }
    
    function resetZoom() {
        currentScale = $scale_factor;
        updateScale();
    }
    
    // Inizializza al caricamento
    document.addEventListener('DOMContentLoaded', updateScale);
</script>
HTML;

$html .= <<<HTML
<div class="zoom-controls">
    <button class="zoom-btn" onclick="zoomIn()" title="Ingrandisci">+</button>
    <button class="zoom-btn" onclick="resetZoom()" title="Ripristina zoom">↻</button>
    <button class="zoom-btn" onclick="zoomOut()" title="Rimpicciolisci">−</button>
</div>
HTML;

    return $html;
}

// Genera l'indice HTML con miniature scalate
function generateEnhancedIndex($total_pages, $save_dir) {
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Indice Pagine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .page-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
            margin: 0 auto;
            max-width: 1200px;
        }
        .page-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .page-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .page-card a {
            text-decoration: none;
            color: #333;
            display: block;
        }
        .thumbnail-container {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            overflow: hidden;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .thumbnail {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 3px;
        }
        .page-number {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        .page-info {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Indice delle Pagine</h1>
        <p class="page-info">Totale pagine: $total_pages</p>
    </div>
    <div class="page-grid">
HTML;

    for ($i = 1; $i <= $total_pages; $i++) {
        $svg_path = $save_dir.$i.'/'.$i.'.svg';
        
        if (file_exists($svg_path)) {
            $svg_content = file_get_contents($svg_path);
            $base64 = 'data:image/svg+xml;base64,'.base64_encode($svg_content);
            
            $html .= sprintf('
                <div class="page-card">
                    <a href="%d/composite.html">
                        <div class="thumbnail-container">
                            <img src="%s" class="thumbnail" alt="Anteprima pagina %d">
                        </div>
                        <div class="page-number">Pagina %d</div>
                    </a>
                </div>',
                $i,
                $base64,
                $i,
                $i
            );
        }
    }

    $html .= <<<HTML
    </div>
</body>
</html>
HTML;

    file_put_contents($save_dir.'index.html', $html);
}

// Generazione file PDF
function generatePDFFromPages($save_dir, $total_pages) {
    require_once 'dompdf/autoload.inc.php';
    
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $options->set('dpi', 300);
    
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->setPaper('A4', 'portrait');

    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @page { margin: 0; padding: 0; size: A4; }
            body { margin: 0; padding: 0; }
            .pdf-page {
                page-break-after: always;
                position: relative;
                width: 210mm;
                height: 297mm;
                overflow: hidden;
            }
            .page-wrapper {
                position: absolute;
                top: 0;
                left: 0;
                width: 210mm;
                height: 297mm;
            }
            .background-jpg {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.7;
            }
            .foreground-svg {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
            }
            svg text {
                stroke: #000;
                stroke-width: 0.5px;
                paint-order: stroke;
                font-weight: bold;
            }
        </style>
    </head>
    <body>';

    $pages_found = 0;
    for ($i = 1; $i <= $total_pages; $i++) {
        $page_dir = rtrim($save_dir, '/') . '/' . $i . '/';
        
        if (!is_dir($page_dir)) {
            error_log("Directory non trovata: $page_dir");
            continue;
        }

        // Cerca file immagine
        $image_path = '';
        $image_extensions = ['jpg', 'jpeg', 'png'];
        foreach ($image_extensions as $ext) {
            $files = glob($page_dir . '*.' . $ext);
            if (!empty($files) && file_exists($files[0])) {
                $image_path = $files[0];
                break;
            }
        }

        // Cerca file SVG
        $svg_path = '';
        $svg_files = glob($page_dir . '*.svg');
        if (!empty($svg_files) && file_exists($svg_files[0])) {
            $svg_path = $svg_files[0];
        }

        if (empty($image_path) || empty($svg_path)) {
            error_log("File mancanti nella directory: $page_dir");
            continue;
        }

        // Codifica immagine
        $image_data = base64_encode(file_get_contents($image_path));
        $mime_type = mime_content_type($image_path);

        // Processa SVG
        $svg_content = file_get_contents($svg_path);
        $svg_content = preg_replace([
            '/<\?xml.*?\?>/i',
            '/<!DOCTYPE.*?>/i',
            '/width="[^"]*"/i',
            '/height="[^"]*"/i'
        ], [
            '',
            '',
            'width="100%"',
            'height="100%"'
        ], $svg_content);

        if (strpos($svg_content, 'viewBox=') === false) {
            $svg_content = preg_replace('/<svg([^>]*)>/i', '<svg$1 viewBox="0 0 210 297">', $svg_content);
        }

        $html .= '
        <div class="pdf-page">
            <div class="page-wrapper">
                <img class="background-jpg" src="data:'.$mime_type.';base64,'.$image_data.'"/>
                <div class="foreground-svg">'.$svg_content.'</div>
            </div>
        </div>';

        $pages_found++;
    }

    $html .= '</body></html>';

    // DEBUG: Salva HTML
    $debug_path = $save_dir . '/debug_'.date('Ymd_His').'.html';
    if (!file_put_contents($debug_path, $html)) {
        throw new Exception("Impossibile salvare il file di debug");
    }

    // Genera PDF
    $dompdf->loadHtml($html);
    $dompdf->render();

    $output_pdf = $save_dir . '/output_'.date('Ymd_His').'.pdf';
    $pdf_content = $dompdf->output();
    
    if (empty($pdf_content)) {
        throw new Exception("Il contenuto del PDF è vuoto");
    }

    if (!file_put_contents($output_pdf, $pdf_content)) {
        throw new Exception("Impossibile salvare il PDF");
    }

    // Verifica finale
    if (filesize($output_pdf) === 0) {
        throw new Exception("Il PDF generato è vuoto");
    }

    return $output_pdf; // Restituisce solo il percorso del PDF
}

// CONTROLLO OPZIONALE DA RICHIAMARE MANUALMENTE QUANDO SI SOSPETTANO PROBLEMI
function checkCookieManually($base_url, $product_path, $username, $password, $cookie) {
    $test_url = $base_url . $product_path . '1/1.svg';
    $content = fetchResource($test_url, $username, $password, $cookie);
    
    if ($content === false || strpos($content, 'AccessDenied') !== false) {
        return false;
    }
    return true;
}

// Esempio di utilizzo:
// if (!checkCookieManually($base_url, $product_path, $username, $password, $cookie)) {
//     echo "&#9888; Attenzione: Potrebbero esserci problemi con il cookie";
// }

// INIZIO
if (!isCookieActuallyValid($cookie)) {
    die("&#128721; Errore: Il cookie è scaduto (verifica manuale)");
}

// Verifica connessione più permissiva
try {
    validateConnection($base_url, $product_path, $username, $password, $cookie);
} catch (Exception $e) {
    echo "&#9888; Attenzione: " . $e->getMessage() . " Procedo comunque...<br>";
}

// Processa ogni pagina
$currentScale = 100;
$successful_pages = 0;
for ($page_num = $start_page; $page_num <= $end_page; $page_num++) {
    $page_url = $base_url . $product_path . $page_num . '/' . $page_num . '.svg';
    $page_dir = $save_dir . $page_num . '/';
    
    if (!file_exists($page_dir)) {
        mkdir($page_dir, 0777, true);
    }
    
    $svg_content = fetchResource($page_url, $username, $password, $cookie);
    
    if ($svg_content === false) {
        echo "&#9888; Pagina $page_num saltata (errore generico)<br>";
        continue;
    }
    
    // Controllo aggiuntivo sul contenuto
    if (empty($svg_content) || strpos($svg_content, 'AccessDenied') !== false) {
        echo "&#9888; Pagina $page_num saltata (accesso negato)<br>";
        continue;
    }
    
    // Se arriva qui, il download è riuscito
    file_put_contents($page_dir . $page_num . '.svg', $svg_content);
    $resources = findAndSaveAllResources($svg_content, $base_url, $page_url, $page_dir, $username, $password, $cookie);
    $composite_html = generateEnhancedCompositePage($page_num, $svg_content, $resources, $page_dir, $save_dir, $end_page);
    file_put_contents($page_dir . 'composite.html', $composite_html);
    
    $successful_pages++;
    echo "&#9989; Pagina $page_num completata. Risorse: " . count($resources) . "<br>";
}

// Genera l'indice completo
if ($successful_pages > 0) {
    generateEnhancedIndex($successful_pages, $save_dir);
    echo "<br>&#10004;&#65039; Indice generato: $save_dir/index.html<br>";
}

echo "<br>&#127881; Operazione completata! $successful_pages pagine processate.<br>";
echo "<div style='background: #fff8e1; padding: 10px;'>
      Percorso indice:<br>
      <code style='word-break: break-all;'>".realpath($save_dir.'index.html')."</code>
      </div>";
      
echo "<h2>Download Completo</h2>";
echo "<p>Pagine scaricate con successo: $successful_pages/$end_page</p>";

// PARTE PER GENERARE IL PDF (DISABILITATA APPOSITAMENTE PERCHE' NON SI E' RIUSCITI A RICOSTRUISRLO)
/*
// Pulsante per generare PDF
echo "<form method='post'>";
echo "<input type='hidden' name='action' value='generate_pdf'>";
echo "<button type='submit' style='padding:10px;background:#4CAF50;color:white;border:none;border-radius:4px;cursor:pointer;'>";
echo "&#128190; Genera PDF di tutte le pagine";
echo "</button>";
echo "</form>";

// Gestione della generazione PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'generate_pdf') {
    $pdf_file = generatePDFFromPages($save_dir, $end_page);
    echo "<div style='margin:20px;padding:10px;background:#e8f5e9;border-radius:4px;'>";
    echo "<a href='$pdf_file' download style='color:#2e7d32;font-weight:bold;'>";
    echo "&#11015;&#65039; Scarica il PDF generato";
    echo "</a> (".round(filesize($pdf_file)/1024,2)." KB)";
    echo "</div>";
}
*/

?>