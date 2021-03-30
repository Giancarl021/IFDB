<?php

function send_json($data, $pretty_print = false)
{
    if (!$pretty_print) {
        echo json_encode($data);
        return;
    }
    echo '<!DOCTYPE html>' .
        '<html lang="en">' .
        '<head>' .
        '<meta charset="UTF-8">' .
        '<meta http-equiv="X-UA-Compatible" content="IE=edge">' .
        '<meta name="viewport" content="width=device-width, initial-scale=1.0">' .
        '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.1/styles/default.min.css">' .
        '<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.1/highlight.min.js"></script>' .
        '<script>hljs.highlightAll();</script>' .
        '<title>JSON Viewer</title>' .
        '</head>' .
        '<body>' .
        '<pre><code class="json">' . json_encode($data, JSON_PRETTY_PRINT) . '</code></pre>' .
        '</body>' .
        '</html>';
}
