<?php

$mainPageXpath = getXpath('https://iryston.tv/category/news/');
foreach ($mainPageXpath->query("//div[contains(@class, 'first_news_block')]//ul[contains(@class, 'cbp_tmtimeline')]") as $item) {
  $title = $mainPageXpath->query(".//time[contains(@class, 'cbp_tmtime')]//h4", $item);
  $date = $mainPageXpath->query(".//time[contains(@class, 'cbp_tmtime')]//div[contains(@class, 'date')]", $item);

  $image = $mainPageXpath->query(".//div[contains(@class, 'photo-content')]", $item);
  $image_url = $image[0]->getAttribute('style');
  if($image[0] !== null) {
    $image_url = getUrl($image_url);
  }

  $link = $mainPageXpath->query(".//a", $item);
  $newsUrl = $link[0]->getAttribute('href');

  $newsText = null;
  $articleXpath = getXpath($newsUrl);
  foreach($articleXpath->query("//div[contains(@class, 'news_full_text')]//p") as $key => $articleElement) {
    if($key == 0)
      	continue;
    $newsText .= $articleElement->textContent."\n";
  }

  echo "<h3>{$title[0]->textContent}</h3>";
  echo "<div><img src=\"{$image_url}\"></div>";
  echo $date[0]->textContent."\n";
  echo $newsText;
}

function getXpath($url) {
  $html = file_get_contents($url);
  $doc = new DOMDocument();
  @$doc->loadHTML($html);
  $xpath = new DOMXpath($doc);
  return $xpath;
}

function getUrl($str_url) {
  $url = "";
  $ind = 0;
  for ($i = 0; $i < strlen($str_url); $i++) {
    if($str_url[$i - 1] == '(')
    {
      $ind = $i;
      break;
    }
  }
  for ($i = $ind; $i < strlen($str_url); $i++) {
    if($str_url[$i] == ')') break;
    $url .= $str_url[$i];
  }
  return $url;
}
