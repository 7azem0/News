<?php
// Simple PDF generator for articles
// Using basic HTML to PDF conversion

class PdfGenerator {
    
    public static function generateArticlePdf($article) {
        // Create HTML content for PDF
        $html = self::createHtmlContent($article);
        
        // Return HTML content (will be converted to PDF by browser's print function)
        return $html;
    }
    
    private static function createHtmlContent($article) {
        $title = htmlspecialchars($article['title'] ?? 'Untitled');
        $author = htmlspecialchars($article['author'] ?? 'Unknown Author');
        $date = date('F j, Y', strtotime($article['publishedAt'] ?? 'now'));
        $content = nl2br(htmlspecialchars($article['content'] ?? ''));
        $thumbnail = htmlspecialchars($article['thumbnail'] ?? '');
        $currentYear = date('Y');
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $title . '</title>
    <style>
        @media print {
            body { margin: 0; }
            .print-instructions { display: none !important; }
        }
        body {
            font-family: \'Georgia\', serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .site-name {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 32px;
            line-height: 1.2;
            margin: 20px 0;
            font-weight: bold;
        }
        .meta {
            color: #666;
            font-size: 14px;
            margin: 20px 0;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        .content {
            font-size: 16px;
            text-align: justify;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="site-name">DIGITAL NEWSSTAND</div>
    </div>
    
    <h1>' . $title . '</h1>
    
    <div class="meta">
        <strong>By:</strong> ' . $author . ' &nbsp;|&nbsp; <strong>Published:</strong> ' . $date . '
    </div>';
        
        // Add thumbnail image if available
        if (!empty($thumbnail)) {
            $html .= '
    <div class="article-image" style="margin: 30px 0; text-align: center;">
        <img src="' . $thumbnail . '" alt="' . $title . '" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    </div>';
        }
        
        $html .= '
    
    <div class="content">
        ' . $content . '
    </div>
    
    <div class="footer">
        <p>Downloaded from Digital Newsstand</p>
        <p>© ' . $currentYear . ' Digital Newsstand. All rights reserved.</p>
    </div>
    
    <div class="print-instructions" style="position: fixed; top: 20px; right: 20px; background: #667eea; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 1000;">
        <p style="margin: 0 0 10px 0; font-weight: bold;">💾 Save as PDF:</p>
        <ol style="margin: 0; padding-left: 20px; font-size: 14px;">
            <li>In print dialog, select "Save as PDF" or "Microsoft Print to PDF"</li>
            <li>Click "Save" or "Print"</li>
            <li>Choose location and save</li>
        </ol>
        <button onclick="window.print()" style="margin-top: 10px; width: 100%; padding: 8px; background: white; color: #667eea; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Open Print Dialog
        </button>
    </div>
    
    <script>
        // Auto-trigger print dialog after a short delay
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>';
        
        return $html;
    }
}
