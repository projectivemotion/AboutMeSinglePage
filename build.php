<?php
/**
 * Project: amadomartinez.mx
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */

$feed   =   'http://projectivemotion.com/feed/';

$feedrc =   simplexml_load_file($feed);

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
    file_put_contents($out, $rendered);
}



