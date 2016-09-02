<?php
/**
 * Project: amadomartinez.mx
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */

$config =   require('config.php');

$feedrc =   simplexml_load_file($config['feed']);

$items  =   [];
foreach($feedrc->channel->item as $item):
    $items[]    =   sprintf("<li><a href=\"%s\">%s</a> » <i><span>%s</span></i></li>",
            $item->link,
            $item->title,
            $item->pubDate
            );
endforeach;

if(empty($items))
    die("Error Ocurred!");

$inout  =   ['index.source.html' => 'index.html'];

foreach($inout as $in => $out)
{
    $template   =   file_get_contents($in);
    $rendered   =   preg_replace('/{posts}/', implode("\n", $items), $template);
    $rendered   =   preg_replace_callback('#\{([^\}]*?)\}#', function ($matches) use ($config) {
        $key    =   $matches[1];
        if(preg_match('#\.php$#', $key))
        {
            if(!file_exists($key)) return '<!-- ' . $key . ' not found. -->';

            ob_start();
            include($key);
            return ob_get_clean();
        }
        return $config[$key];
    }, $rendered);
    file_put_contents($out, $rendered);
}



